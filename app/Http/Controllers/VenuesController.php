<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VenuesController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('masters.venues.venue.index');
    }

    public function create()
    {
        
        return view('masters.venues.venue.create');
    }

    public function edit()
    {
        
        return view('masters.venues.venue.edit');
    }
}