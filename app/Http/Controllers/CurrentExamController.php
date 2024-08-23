<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CurrentExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }

    public function index()
    {
        return view('current_exam.index');
    }
    public function create()
    {
        return view('current_exam.create');
    }
    public function task()
    {
        return view('current_exam.task');
    }
    public function edit()
    {
        return view('current_exam.edit');
    }
    public function increaseCandidate()
    {
        return view('current_exam.increase-candidate');
    }
    public function venueConsent()
    {
        return view('current_exam.venue-consent');
    }

    public function venues()
    {
        return view('current_exam.selectvenues');
    }
    public function add_hall()
    {
        return view('current_exam.addhall');
    }
}