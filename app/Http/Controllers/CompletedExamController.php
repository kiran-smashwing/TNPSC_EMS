<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CompletedExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('completed_exam.index');
    }
    public function task()
    {
        return view('completed_exam.task');
    }
    public function edit()
    {
        return view('completed_exam.edit');
    }
}