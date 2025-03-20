<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GimcanaController extends Controller
{
    public function index()
    {
        return view('admin.gimcana');
    }
}
