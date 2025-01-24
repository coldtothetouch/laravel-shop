<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use Domains\Auth\Contracts\RegisterUserContract;
use Domains\Auth\DataTransferObjects\RegisterUserDTO;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(): View
    {
        return view('auth.register');
    }

    public function store(RegisterUserContract $action, RegisterFormRequest $request): RedirectResponse
    {
        $user = $action(RegisterUserDTO::fromRequest($request));

        auth()->login($user);

        return redirect()->route('home');
    }
}
