<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserGuideController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function index(){
        // dd("I am Here");
        return view('user_gudie.index');
    }
}
