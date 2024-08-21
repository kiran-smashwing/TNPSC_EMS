<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ChiefInvigilatorsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('masters.venues.chief_invigilator.index');
    }

    public function create()
    {
        
        return view('masters.venues.chief_invigilator.create');
    }

    public function edit()
    {
        
        return view('masters.venues.chief_invigilator.edit');
    }
}