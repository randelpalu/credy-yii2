<?php

namespace app\services;

interface CredyApplicationServiceInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function sendApplication(array $data): string;
}
