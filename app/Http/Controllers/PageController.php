<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    /**
     * Welcome page
     */
    public function index()
    {
        return view('welcome');
    }
}
