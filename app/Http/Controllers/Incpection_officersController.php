<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Incpection_officersController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('incpection_officers.index');
    }

    public function create()
    {
        
        return view('incpection_officers.create');
    }

    public function edit()
    {
        
        return view('incpection_officers.edit');
    }
}