<?php

namespace app\components;

class SignatureGenerator
{
    public const DEFAULT_SUFFIX = 'credy';

    /**
     * @param int $timestamp
     * @param string $suffix
     * @return string
     */
    public static function generateSignature(int $timestamp, string $suffix = self::DEFAULT_SUFFIX): string
    {
        $toHash = $timestamp . $suffix;

        return hash('sha1', $toHash);
    }
}
