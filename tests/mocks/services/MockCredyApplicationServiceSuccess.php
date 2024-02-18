<?php

namespace tests\mocks\services;

use app\services\CredyApplicationServiceInterface;

class MockCredyApplicationServiceSuccess implements CredyApplicationServiceInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function sendApplication(array $data): string
    {
        return 'Mocked success message';
    }
}
