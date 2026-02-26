<?php
declare(strict_types=1);

namespace App\Framework;

final class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::KEY];
    }

    public static function inputField(): string
    {
        $t = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="_csrf" value="'.$t.'">';
    }

    public static function validate(?string $postedToken): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sessionToken = $_SESSION[self::KEY] ?? '';
        if ($sessionToken === '' || $postedToken === null) {
            return false;
        }

        return hash_equals($sessionToken, $postedToken);
    }
}
