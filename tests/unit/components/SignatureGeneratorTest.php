<?php

namespace tests\unit\components;

use app\components\SignatureGenerator;

class SignatureGeneratorTest extends \Codeception\Test\Unit
{
    public function testGenerateSignature(): void
    {
        $timestamp = time();
        $suffix = 'some_suffix';
        $expectedHash = hash('sha1', $timestamp . $suffix);

        $actualHash = SignatureGenerator::generateSignature($timestamp, $suffix);

        $this->assertEquals($expectedHash, $actualHash);
    }

    public function testGenerateSignatureWithDefaultSuffix(): void
    {
        $timestamp = time();
        $expectedHash = hash('sha1', $timestamp . SignatureGenerator::DEFAULT_SUFFIX);

        $actualHash = SignatureGenerator::generateSignature($timestamp);

        $this->assertEquals($expectedHash, $actualHash);
    }

}
