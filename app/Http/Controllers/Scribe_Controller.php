<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Scribe_Controller extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('scribe.index');
    }
    public function create()
    {
        
        return view('scribe.create');
    }

    public function edit()
    {
        
        return view('scribe.edit');
    }
}