<?php

namespace Domains\Auth\Providers;

use Domains\Auth\Actions\RegisterUserAction;
use Domains\Auth\Contracts\RegisterUserContract;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public $bindings = [
        RegisterUserContract::class => RegisterUserAction::class,
    ];
}
