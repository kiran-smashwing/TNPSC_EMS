<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class InspectionOfficersController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('inspection_officers.index');
    }

    public function create()
    {
        
        return view('inspection_officers.create');
    }

    public function edit()
    {
        
        return view('inspection_officers.edit');
    }
}