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
    public function ciTask()
    {
        return view('current_exam.ci-task');
    }

    public function districtCollectrateTask()
    {
        return view('current_exam.district-task');
    }
    public function examActivityTask()
    {
        return view('current_exam.exam-activities-task');
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
    public function sendMailtoCollectorate()
    {
        return view('current_exam.send-mailto-collectorate');
    }

    public function selectSendMailtoVenue()
    {
        return view('current_exam.send-mailto-venue');
    }
    public function confirmVenues()
    {
        return view('current_exam.confirm-venues');
    }
    public function ciReceiveMaterials()
    {
        return view('current_exam.ci-receive-materials');
    }
    public function bundlePackaging()
    {
        return view('current_exam.bundle-packaging');
    }
}