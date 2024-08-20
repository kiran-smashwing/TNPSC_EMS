@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- [ Pre-loader ] start -->
<div class="page-loader">
    <div class="bar"></div>
</div>
<!-- [ Pre-loader ] End -->

<!-- [ Sidebar Menu ] start -->
@include('partials.sidebar')
<!-- [ Sidebar Menu ] end -->

<!-- [ Header Topbar ] start -->
@include('partials.header')
<!-- [ Header Topbar ] end -->



<!-- [ Main Content ] start -->
<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <!-- Breadcrumb Title or other content can go here -->
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->


        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Centers List</h5><small></small>
                    </div>
                    <div class="card-body">
                        <table id="basic-btn" class="display table table-striped table-hover dt-responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Center Code</th>
                                    <th>District</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/user/avatar-1.jpg" alt="user image" class="img-radius wid-40">
                                            </div>
                                            <!-- <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">Airi Satou</h6>
                                            </div> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">Airi Satou</h6>
                                        </div>
                                    </td>
                                    <td>AD</td>
                                    <td>00101</td>
                                    <td>Velloer</td>
                                    <td>CMC,Vellore, Tamil Nadu 632001</td>
                                    <td>
                                        <a href="#" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-eye f-20"></i></a>
                                        <a href="#" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>
                                        <a href="#" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-trash f-20"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- Config table end --><!-- `New` Constructor table start -->
           
           
        </div>
    </div>
    
@include('partials.footer')
@include('partials.theme')
    @endsection

    