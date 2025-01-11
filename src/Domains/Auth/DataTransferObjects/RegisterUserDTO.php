<?php

namespace Domains\Auth\DataTransferObjects;

use Illuminate\Http\Request;

readonly class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(Request $request): RegisterUserDTO
    {
        return new self(...$request->validated());
    }
}
