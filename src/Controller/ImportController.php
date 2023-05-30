<?php

/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Galaxy\LaravelExchange1C\Controller;

use Exception;
use LogicException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Galaxy\LaravelExchange1C\Events\ExchangeEvent;
use Galaxy\LaravelExchange1C\Jobs\CatalogServiceJob;
use Galaxy\LaravelExchange1C\Services\CatalogService;
use Galaxy\LaravelExchange1C\Exceptions\Exchange1CException;

/**
 * Class ImportController.
 */
class ImportController extends Controller
{
    protected CatalogService $catalog;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalog = $catalogService;
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

        try {
            $this->check($mode, $type);

            if ($type == 'catalog' && !in_array($mode, ['init', 'checkauth', 'file'])) {
                CatalogServiceJob::dispatch(
                    $request->all(),
                    $request->session()->all()
                )->onQueue(config('exchange1c.queue'));

                $response = "success\n";
            } else {
                $response = $this->$type->$mode();
            }
        } catch (Exception $e) {
            $response = "failure\n";
            $response .= $e->getMessage() . "\n";
        }

        
        if(is_string($response)) {
            event(new ExchangeEvent($type, $mode, $response));
            
            return response($response, $this->isSuccess($response) ? 200 : 400, ['Content-Type', 'text/plain']);
        }

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
