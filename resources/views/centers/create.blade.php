@extends('layouts.app')
@section('title', ' Add Centers')

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


    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Add Center</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ breadcrumb ] end -->


        <!-- [ Main Content ] start -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0">Basic Information</h5>
              </div>
              <div class="card-body">
              <form action="{{ route('centers.store') }}" method="POST" enctype="multipart/form-data">

                <div class="row">
                 @csrf
                <div class="col-md-6">
                    <div class="mb-3">
                    <label  class="form-label"  for="center_name">Center Name</label>
                      <input type="text" class="form-control" placeholder="Center Name" id="center_name" name="center_name" required/>
                    </div>
                </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label"  for="center_code">Center Code</label>
                      <input type="text" class="form-control" placeholder="Center Code" id="center_code" name="center_code" required/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label" for="district_id">District</label>
                      <select class="form-select" id="district_id" name="district_id" required>
                      <option  value="#" >Select District</option>
                      @foreach($districts as $district)
                    <option value="{{ $district->id }}">{{ $district->district_name }}</option>
                    @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label" for="status">Status</label>
                      <select class="form-select" id="status" name="status" required>
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="mb-3">
                    <label class="form-label" for="image">Image</label>
                      <input class="form-control" type="file" id="image" name="image"/>
                    </div>
                  </div>
                  <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
                </form>

              </div>
            </div>
          </div>
        </div>
        <!-- [ Main Content ] end -->
      </div>
    </div>
    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @include('partials.theme')

@endsection
