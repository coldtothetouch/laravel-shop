<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function store()
    {
        //
    }

    public function sendEmail()
    {
        //
    }

    public function resetPassword()
    {
        return view('auth.reset-password');
    }
}
