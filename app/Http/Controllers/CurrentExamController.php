<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CurrentExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
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
    public function ciMeeting()
    {
        return view('current_exam.ci-meeting');
    }
    public function routeView()
    {
        return view('current_exam.route.route-view');
    }
    public function routeCreate()
    {
        return view('current_exam.route.route-create');
    }
    public function routeEdit()
    {
        return view('current_exam.route.route-edit');
    }
    public function updateMaterialScanDetails()
    {
        return view('current_exam.material-scan-details');
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
    public function mobileTeamReceiveMaterialsFromTreasury()
    {
        return view('current_exam.treasury-to-mobileTeam-materials');
    }
    public function ciReceiveMaterialsFromMobileTeam()
    {
        return view('current_exam.mobileTeam-to-CI-materials');
    }
    public function bundlePackaging()
    {
        return view('current_exam.bundle-packaging');
    }
    public function bundlePackagingverfiy()
    {
        return view('current_exam.bundle-CI-to-mobile-team');
    }
    public function vandutyBundlePackagingverfiy()
    {
        return view('current_exam.bundle-CI-to-van-duty');
    }
    public function vdstotreasuryofficer()
    {
        return view('current_exam.vds-to-treasury-officer');
    }
}