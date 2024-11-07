@extends('layouts.app')

@section('title', 'Exam Service')

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

<div class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-sm-12">
        <!-- <div class="card">
          <div class="card-body py-0">
             Your content here 
          </div> -->
        </div>
        <div class="tab-content">
          <div>
              <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                  <div class="col-lg-6">
                      <div class="card">
                          <div class="card-header">
                              <h5>Exam Service - <span class="text-primary">Add</span></h5>
                          </div>
                          <div class="card-body">
                            <form action="{{ route('examservice.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                              <div class="row">
                                 
                                 
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="name">Name <span
                                                  class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="name" name="name"
                                              placeholder="GROUP I SERVICES EXAMINATION" required>
                                      </div>
                                  </div>
                                 
                                  <div class="col-sm-6">
                                      <div class="mb-3">
                                          <label class="form-label" for="code">Code <span
                                                  class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="code" name="code"
                                              placeholder="001" required>
                                      </div>
                                  </div>
                                
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-12 text-end btn-page">
                    <a href="{{ route('exam-service') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create</button>
                  </div>
                </form>
              </div>
          </div>
      </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
  </div>
</div>

@include('partials.footer')

@push('scripts')
@include('partials.datatable-export-js')
@endpush

@include('partials.theme')

@endsection
