<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CI_AssistantsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('ci_assistants.index');
    }

    public function create()
    {
        
        return view('ci_assistants.create');
    }

    public function edit()
    {
        
        return view('ci_assistants.edit');
    }
}