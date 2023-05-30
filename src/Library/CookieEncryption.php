<?php

namespace Galaxy\LaravelExchange1C\Library;

use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Cookie\Middleware\EncryptCookies;

class CookieEncryption extends EncryptCookies
{
    public static function encryptString(string $str, string $name = null)
    {
        $self = app(self::class);
        $name ??= config('session.cookie');

        return $self->encrypter->encrypt(
            CookieValuePrefix::create($name, $self->encrypter->getKey()) . $str,
            static::serialized($name)
        );
    }
}