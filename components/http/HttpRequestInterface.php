<?php

namespace app\components\http;

interface HttpRequestInterface
{
    public function setOptionArray(array $options): void;

    public function execute(): bool|string;

    public function getInfo(string $name): mixed;

    public function close(): void;
}
