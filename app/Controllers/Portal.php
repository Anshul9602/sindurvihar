<?php

namespace App\Controllers;

class Portal extends BaseController
{
    public function index()
    {
        return view('layout/header')
            . view('portal/home')
            . view('layout/footer');
    }
}


