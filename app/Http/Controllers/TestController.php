<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class TestController extends Controller
{
    public function index()
    {
        dd(Currency::all());
    }
   
}
