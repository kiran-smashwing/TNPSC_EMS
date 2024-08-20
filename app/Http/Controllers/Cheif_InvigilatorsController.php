<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Cheif_invigilatorsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('cheif_invigilator.index');
    }

    public function create()
    {
        
        return view('cheif_invigilator.create');
    }

    public function edit()
    {
        
        return view('cheif_invigilator.edit');
    }
}