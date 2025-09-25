<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlatController extends Controller
{
    public function index()
    {
        // Logic to retrieve and return flats
        return view('flats.index');
    }
}
