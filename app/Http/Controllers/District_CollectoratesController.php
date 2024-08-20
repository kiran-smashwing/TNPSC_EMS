<?php

namespace App\Http\Controllers;
use App\Models\Collectorate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class District_CollectoratesController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('district_collectrote.index');
    }

    public function create()
    {
        $districts = Collectorate::all();
        return view('district_collectrote.create', compact('districts'));
    }

    public function edit()
    {
        
        return view('district_collectrote.edit');
    }
}