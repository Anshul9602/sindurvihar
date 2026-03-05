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

    public function privacy()
    {
        return view('layout/header')
            . view('portal/privacy')
            . view('layout/footer');
    }

    public function terms()
    {
        return view('layout/header')
            . view('portal/terms')
            . view('layout/footer');
    }
}


