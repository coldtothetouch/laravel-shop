<?php

namespace Domains\Auth\Contracts;

use Domains\Auth\DataTransferObjects\RegisterUserDTO;
use Domains\Auth\Models\User;

interface RegisterUserContract
{
    public function __invoke(RegisterUserDTO $dto): User;
}
