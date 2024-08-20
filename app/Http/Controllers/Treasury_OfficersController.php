<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Treasury_OfficersController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('treasury_officers.index');
    }

    public function create()
    {
        
        return view('treasury_officers.create');
    }

    public function edit()
    {
        
        return view('treasury_officers.edit');
    }
}