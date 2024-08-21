<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MobileTeamStaffsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('mobile_team_staffs.index');
    }

    public function create()
    {
        
        return view('mobile_team_staffs.create');
    }

    public function edit()
    {
        
        return view('mobile_team_staffs.edit');
    }
}