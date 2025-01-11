<?php

namespace Domains\Auth\Contracts;

use Domains\Auth\DataTransferObjects\RegisterUserDTO;

interface RegisterUserContract
{
    public function __invoke(RegisterUserDTO $dto): void;
}
