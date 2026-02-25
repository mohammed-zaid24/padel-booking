<?php

namespace App\Services;

interface IAuthService
{
    public function register(string $name, string $email, string $password): void;
    public function login(string $email, string $password): bool;
}
