<?php

namespace Galaxy\LaravelExchange1C\Services;

use Galaxy\LaravelExchange1C\Config;

class SaleService
{
    protected XmlService $xml;
    protected Config $config;
    protected AuthService $auth;
    protected CatalogService $catalog;

    public function __construct(XmlService $xmlService, Config $config, AuthService $authService, CatalogService $catalogService)
    {
        $this->xml = $xmlService;
        $this->config = $config;
        $this->auth = $authService;
        $this->catalog = $catalogService;
    }

    public function query()
    {
        $this->auth->auth();

        $path = $this->config->getFullPath('orders.xml');

        $data = file_exists($path) ? file_get_contents($path) : [];

        return $this->xml->response($data);
    }

    public function checkauth(): string
    {
        return $this->auth->checkAuth();
    }

    public function init(): string
    {
        return $this->catalog->init();
    }

    public function success()
    {
         return 'success';
    }

    public function file()
    {
        return 'success';
    }

}