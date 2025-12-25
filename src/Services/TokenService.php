<?php

namespace App\Services;

class TokenService
{
    private const TOKEN_LENGTH = 64;
    private const TOKEN_EXPIRY_HOURS = 24;

    public static function generateToken(): string
    {
        return bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
    }

    public static function getExpiryTime(): string
    {
        return date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_EXPIRY_HOURS . ' hours'));
    }

    public static function isTokenValid(string $expiryTime): bool
    {
        return strtotime($expiryTime) > time();
    }
}

