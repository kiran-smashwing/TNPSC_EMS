<?php

namespace App\Http\Controllers;

class ExamServiceController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }
    public function index()
    {
    // Return the view with the centers data
    return view('masters.department.exam_service.index');
    }

    public function create()
    {
        return view('masters.department.exam_service.create');
    }

    public function edit()
    {
        return view('masters.department.exam_service.edit');
    }

}