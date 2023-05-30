<?php

namespace Galaxy\LaravelExchange1C\Services;

use Galaxy\LaravelExchange1C\Config;

class SaleService
{
    protected XmlService $xml;
    protected Config $config;

    public function __construct(XmlService $xmlService)
    {
        $this->xml = $xmlService;
    }

    public function query()
    {
        $path = $this->config->getFullPath('orders.xml');

        $data = file_exists($path) ? file_get_contents($path) : [];

        return $this->xml->response($data);
    }
}