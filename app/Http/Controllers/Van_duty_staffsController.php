<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Van_duty_staffsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('van_duty_staffs.index');
    }

    public function create()
    {
        
        return view('van_duty_staffs.create');
    }

    public function edit()
    {
        
        return view('van_duty_staffs.edit');
    }
}