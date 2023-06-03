<?php

namespace Galaxy\LaravelExchange1C\Services;

use Spatie\ArrayToXml\ArrayToXml;

class XmlService
{
    public function response($data, int $status = 200, array $headers = [])
    {
        if (is_array($data)) {
            $data = $this->toXml($data);
        }

        return response($data, $status, array_merge($headers, [
            'Content-Type' => 'application/xml'
        ]));
    }


    public function toXml(array $data): string
    {
        $xml = ArrayToXml::convert($data);

        $xml = html_entity_decode($xml);
        $xml = str_replace("&", "&amp;", $xml);

        return $xml;
    }

    public function parseXml(string $xml)
    {
        $xml = simplexml_load_string($xml);

        $data = json_decode(json_encode($xml), true);

        return $data;
    }
}