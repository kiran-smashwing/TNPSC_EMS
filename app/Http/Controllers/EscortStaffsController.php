<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EscortStaffsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('escort_staffs.index');
    }

    public function create()
    {
        
        return view('escort_staffs.create');
    }

    public function edit()
    {
        
        return view('escort_staffs.edit');
    }
}