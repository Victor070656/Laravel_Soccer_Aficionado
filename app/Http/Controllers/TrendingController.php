<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrendingController extends Controller
{
    public function index(Request $request)
    {
        return view('trending.index');
    }
}
