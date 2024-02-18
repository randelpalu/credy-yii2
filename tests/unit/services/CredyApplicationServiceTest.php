<?php

namespace tests\unit\services;

use app\components\http\CurlRequest;
use app\services\CredyApplicationService;
use Codeception\Stub;
use Codeception\Stub\Expected;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CredyApplicationServiceTest extends TestCase
{
    public function testRequestSuccess(): void
    {
        $mockCurlRequest = Stub::makeEmpty(CurlRequest::class, [
            'setOptionArray' => Expected::once(),
            'execute' => Expected::once('{"status": "success"}'),
            'getInfo' => Expected::once(200),
            'close' => Expected::once(),
        ]);

        $credyService = Stub::construct(
            CredyApplicationService::class,
            [$mockCurlRequest],
            ['generateJsonx' => '{"notUsed": "notUsed"}']
        );

        $result = $credyService->sendApplication(['data' => ['notUsed' => 'notUsed']]);

        $this->assertEquals('{"status": "success"}', $result);
    }

    public function testResponseErrorException(): void
    {
        $mockCurlRequest = Stub::makeEmpty(CurlRequest::class, [
            'setOptionArray' => Expected::once(),
            'execute' => Expected::once(false),
            'getInfo' => Expected::once(400),
            'close' => Expected::once(),
        ]);

        $credyService = Stub::construct(
            CredyApplicationService::class,
            [$mockCurlRequest],
            ['generateJsonx' => '{"notUsed": "notUsed"}']
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('cURL Request Failed !');

        $credyService->sendApplication(['data' => ['notUsed' => 'notUsed']]);
    }

    public function testResponseUnexpectedStatusCodeException(): void
    {
        $mockCurlRequest = Stub::makeEmpty(CurlRequest::class, [
            'setOptionArray' => Expected::once(),
            'execute' => Expected::once('not sure'),
            'getInfo' => Expected::once(300),
            'close' => Expected::once(),
        ]);

        $credyService = Stub::construct(
            CredyApplicationService::class,
            [$mockCurlRequest],
            ['generateJsonx' => '{"notUsed": "notUsed"}']
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('HTTP Error: 300 - not sure');

        $credyService->sendApplication(['data' => ['notUsed' => 'notUsed']]);
    }

    public function testGenerateJsonxSuccess(): void
    {
        $data = [
            'application' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'fake@example.com',
                'bio' => 'i dont like biology',
                'technologies' => 'PHP,JavaScript',
                'vcsUri' => 'https://github.com/fake',
            ],
            'timestamp' => 1234567890,
            'signature' => 'abcde12345',
        ];

        $expectedXml = '<?xml version="1.0" encoding="UTF-8"?>
        <json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" 
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">
            <json:string name="first_name">John</json:string>
            <json:string name="last_name">Doe</json:string>
            <json:string name="email">fake@example.com</json:string>
            <json:string name="bio">i dont like biology</json:string>
            <json:array name="technologies">
                <json:string>PHP</json:string>
                <json:string>JavaScript</json:string>
            </json:array>
            <json:number name="timestamp">1234567890</json:number>
            <json:string name="signature">abcde12345</json:string>
            <json:string name="vcs_uri">https://github.com/fake</json:string>
        </json:object>';

        $mockCurlRequest = Stub::makeEmpty(CurlRequest::class);

        $service = new CredyApplicationService($mockCurlRequest);
        $reflectionMethod = new ReflectionMethod(CredyApplicationService::class, 'generateJsonx');
        $result = $reflectionMethod->invoke($service, $data);

        $this->assertXmlStringEqualsXmlString($expectedXml, $result);
    }
}
