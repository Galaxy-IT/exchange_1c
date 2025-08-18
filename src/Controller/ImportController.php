<?php

/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace GalaxyIT\LaravelExchange1C\Controller;

use Exception;
use LogicException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use GalaxyIT\LaravelExchange1C\Events\ExchangeEvent;
use GalaxyIT\LaravelExchange1C\Services\SaleService;
use GalaxyIT\LaravelExchange1C\Jobs\CatalogServiceJob;
use GalaxyIT\LaravelExchange1C\Services\CatalogService;
use GalaxyIT\LaravelExchange1C\Exceptions\Exchange1CException;

/**
 * Class ImportController.
 */
class ImportController extends Controller
{
    protected CatalogService $catalog;
    protected SaleService $sale;

    public function __construct(CatalogService $catalogService, SaleService $saleService)
    {
        $this->catalog = $catalogService;
        $this->sale = $saleService;
    }
    /**
     * @param Request        $request
     * @param CatalogService $service
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function request(Request $request)
    {
        $mode = $request->get('mode');
        $type = $request->get('type');

        $response = 'failure';

        \Log::info([$mode, $type]);

        try {
            $this->check($mode, $type);

            if ($type == 'catalog' && !in_array($mode, ['init', 'checkauth', 'file', 'import'])) {
                \Log::info('Job Mode ' . $mode);

                // CatalogServiceJob::dispatch(
                //     $request->all(),
                //     $request->session()->all()
                // )->onQueue(config('exchange1c.queue'));

                $response = "success\n";
            } elseif ($type == 'catalog' && $mode == 'import') {
                $response = 'success';
            } else {
                $response = $this->$type->$mode();
            }
        } catch (Exception $e) {
            $response = "failure\n";
            $response .= $e->getMessage() . "\n";
        }


        if (is_string($response)) {
            \log::info($response);
            \Log::info('-------------------------------------------------------------------');

            event(new ExchangeEvent($type, $mode, $response));

            return response($response, $this->isSuccess($response) ? 200 : 400, ['Content-Type', 'text/plain']);
        }

        \Log::info('-------------------------------------------------------------------');

        return $response;
    }

    private function isSuccess($response)
    {
        return !is_string($response) || !str_starts_with($response, 'failure');
    }

    private function check(string $mode, string $type): void
    {
        if (!in_array($type, ['catalog', 'sale'])) {
            throw new Exchange1CException('Incorrect request, type \'' . $type . '\' is not implemented');
        }

        if (!method_exists($this->$type, $mode)) {
            throw new Exchange1CException('Incorrect request, mode \'' . $mode . '\' is not implemented');
        }
    }
}
