<?php

namespace Domains\Auth\Actions;

use Domains\Auth\Contracts\RegisterUserContract;
use Domains\Auth\DataTransferObjects\RegisterUserDTO;
use Domains\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction implements RegisterUserContract
{
    public function __invoke(RegisterUserDTO $dto): void
    {
        $user = User::query()->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);

        event(new Registered($user));
        auth()->login($user);
    }
}
