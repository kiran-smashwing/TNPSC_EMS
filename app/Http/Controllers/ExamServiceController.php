<?php

namespace App\Http\Controllers;

use App\Models\Collectorate;

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
    return view('exam_service.index');
    }

    public function create()
    {
        return view('exam_service.create');
    }

    public function edit()
    {
        return view('exam_service.edit');
    }

}