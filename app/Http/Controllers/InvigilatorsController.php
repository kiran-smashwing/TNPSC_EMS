<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class InvigilatorsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('masters.venues.invigilator.index');
    }

    public function create()
    {
        
        return view('masters.venues.invigilator.create');
    }

    public function edit()
    {
        
        return view('masters.venues.invigilator.edit');
    }
}