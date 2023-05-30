<?php

/**
 * This file is part of galaxy-it/exchange_1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Galaxy\LaravelExchange1C\Controller;

use Galaxy\LaravelExchange1C\Events\ExchangeEvent;
use Galaxy\LaravelExchange1C\Exceptions\Exchange1CException;
use Galaxy\LaravelExchange1C\Services\CatalogService;
use Galaxy\LaravelExchange1C\Jobs\CatalogServiceJob;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

/**
 * Class ImportController.
 */
class ImportController extends Controller
{
    /**
     * @param Request        $request
     * @param CatalogService $service
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function request(Request $request, CatalogService $service)
    {
        $mode = $request->get('mode');
        $type = $request->get('type');

        $response = 'failure';

        try {
            if ($type == 'catalog') {
                if (!method_exists($service, $mode)) {
                    throw new Exchange1CException('not correct request, class ExchangeCML not found');
                }

                if ($mode === 'init' or $mode === 'checkauth' or $mode === 'file') {
                    $response = $service->$mode();
                } else {
                    CatalogServiceJob::dispatch(
                        $request->all(),
                        $request->session()->all()
                    )
                        ->onQueue(config('exchange1c.queue'));

                    $response = "success\n";
                }
            } elseif ($type === 'sale') {
                $response = $service->checkauth();
            } else {
                $message = sprintf('Logic for method %s not released', $type);

                throw new \LogicException($message);
            }
        } catch (Exchange1CException $e) {
            $response = "failure\n";
            $response .= $e->getMessage() . "\n";
            $response .= $e->getFile() . "\n";
            $response .= $e->getLine() . "\n";
        }

        event(new ExchangeEvent($type, $mode, $response));

        return response($response, $this->isSuccess($response) ? 200 : 400, ['Content-Type', 'text/plain']);
    }

    private function isSuccess($response)
    {
        return !is_string($response) || !str_starts_with($response, 'failure');
    }
}
