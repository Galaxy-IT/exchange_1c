<?php

namespace Galaxy\LaravelExchange1C\Services;

use Galaxy\LaravelExchange1C\Config;

class SaleService
{
    protected XmlService $xml;
    protected Config $config;
    protected AuthService $auth;

    public function __construct(XmlService $xmlService, Config $config, AuthService $authService)
    {
        $this->xml = $xmlService;
        $this->config = $config;
        $this->auth = $authService;
    }

    public function query()
    {
        $this->auth->auth();

        $path = $this->config->getFullPath('orders.xml');

        $data = file_exists($path) ? file_get_contents($path) : [];

        return $this->xml->response($data);
    }
}