<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScribeController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('masters.venues.scribe.index');
    }
    public function create()
    {
        
        return view('masters.venues.scribe.create');
    }

    public function edit()
    {
        
        return view('masters.venues.scribe.edit');
    }
}