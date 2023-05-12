<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // return view('welcome_message');
        // return view('uts/templates/menu_kiri').view('uts/index');
        return view('uts/index');
    }
}
