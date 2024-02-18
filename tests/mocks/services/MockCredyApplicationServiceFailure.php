<?php

namespace tests\mocks\services;

use app\services\CredyApplicationServiceInterface;

class MockCredyApplicationServiceFailure implements CredyApplicationServiceInterface
{
    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function sendApplication(array $data): string
    {
        throw new \Exception('cURL Request Failed !');
    }
}
