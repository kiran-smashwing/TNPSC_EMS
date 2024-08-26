<?php

namespace App\Http\Controllers;


class CIChecklistController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth');
    }
    public function index()
    {
    return view('masters.department.ci_checklist.index');
    }

    public function create()
    {
        return view('masters.department.ci_checklist.create');
    }

    public function edit()
    {
        return view('masters.department.ci_checklist.edit');
    }

}