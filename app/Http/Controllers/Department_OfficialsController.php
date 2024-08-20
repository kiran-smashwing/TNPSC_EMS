<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Department_OfficialsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('department_officials.index');
    }

    public function create()
    {
        
        return view('department_officials.create');
    }

    public function edit()
    {
        
        return view('department_officials.edit');
    }
}