<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        // Logic to retrieve and return buildings
        return view('buildings.index');
    }
}
