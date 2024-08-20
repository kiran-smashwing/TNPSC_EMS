<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Invigilators_Controller extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('invigilator.index');
    }

    public function create()
    {
        
        return view('invigilator.create');
    }

    public function edit()
    {
        
        return view('invigilator.edit');
    }
}