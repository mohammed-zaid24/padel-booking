<?php

namespace App\Models;

class UserModel
{
    public ?int $id;
    public string $name;
    public string $email;
    public string $passwordHash;
    public string $role;

    public function __construct(
        ?int $id,
        string $name,
        string $email,
        string $passwordHash,
        string $role = 'user'
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
    }
}
