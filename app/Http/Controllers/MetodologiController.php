<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MetodologiController extends Controller
{
    public function index()
    {
        return view('guru.metodologi');
    }
}
