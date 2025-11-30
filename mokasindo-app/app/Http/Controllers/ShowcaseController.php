<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowcaseController extends Controller
{
    public function index()
    {
        return view('pages.showcase.index');
    }

    public function showcase()
    {
        return view('pages.showcase.showcase');    
    }
}
