<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function contact()
    {
        return view('pages.contact');
    }

    public function installation()
    {
        return view('pages.installation');
    }

    public function installment()
    {
        return view('pages.installment');
    }
}
