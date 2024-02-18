<?php

namespace tests\mocks\services;

use app\services\CredyApplicationServiceInterface;
use Exception;

class MockCredyApplicationServiceHttpError implements CredyApplicationServiceInterface
{
    /**
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function sendApplication(array $data): string
    {
        throw new Exception('HTTP Error: 300 - not sure');
    }
}
