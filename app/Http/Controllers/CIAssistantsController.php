<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CIAssistantsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('masters.venues.ci_assistants.index');
    }

    public function create()
    {
        
        return view('masters.venues.ci_assistants.create');
    }

    public function edit()
    {
        
        return view('masters.venues.ci_assistants.edit');
    }
}