<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DepartmentOfficialsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        return view('masters.department.officials.index');
    }

    public function create()
    {
        
        return view('masters.department.officials.create');
    }

    public function edit()
    {
        
        return view('masters.department.officials.edit');
    }

    public function show()
    {
        
        return view('masters.department.officials.show');
    }
}