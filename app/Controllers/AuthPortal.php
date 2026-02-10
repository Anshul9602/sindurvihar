<?php

namespace App\Controllers;

class AuthPortal extends BaseController
{
    public function login()
    {
        return view('layout/header')
            . view('auth/login')
            . view('layout/footer');
    }

    public function register()
    {
        return view('layout/header')
            . view('auth/register')
            . view('layout/footer');
    }
}


