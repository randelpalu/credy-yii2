<?php

namespace app\components\http;

use CurlHandle;

class CurlRequest implements HttpRequestInterface
{
    private null|false|CurlHandle $handle = null;

    public function __construct($url) {
        $this->handle = curl_init($url);
    }

    public function setOptionArray(array $options): void
    {
        curl_setopt_array($this->handle, $options);
    }

    public function execute(): bool|string
    {
        return curl_exec($this->handle);
    }

    public function getInfo($name): mixed
    {
        return curl_getinfo($this->handle, $name);
    }

    public function close(): void
    {
        curl_close($this->handle);
    }
}
