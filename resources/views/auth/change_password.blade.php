@extends('layouts.app')

@section('title', 'Cheif Invigilator')

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
            <div class="tab-pane" id="profile-4" role="tabpanel" aria-labelledby="profile-tab-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="{{ route('password.update') }}" method="POST" id="password-form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Old Password Input -->
                                    <div class="mb-3">
                                        <label class="form-label">Old Password</label>
                                        <input type="password" id="old_password" name="old_password" class="form-control"
                                            required>
                                        @error('old_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <!-- New Password Input -->
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" id="new_password" name="new_password" class="form-control"
                                            required>
                                        @error('new_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" id="new_password_confirmation"
                                            name="new_password_confirmation" class="form-control" required>

                                        <!-- Display error message if validation fails -->
                                        @error('new_password_confirmation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h5>New password must contain:</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><i
                                                class="ti ti-circle-check text-success f-16 me-2"></i> At least 8 characters
                                        </li>
                                        <li class="list-group-item"><i
                                                class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 lowercase
                                            letter (a-z)</li>
                                        <li class="list-group-item"><i
                                                class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 uppercase
                                            letter (A-Z)</li>
                                        <li class="list-group-item"><i
                                                class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 number
                                            (0-9)</li>
                                        <li class="list-group-item"><i
                                                class="ti ti-circle-check text-success f-16 me-2"></i> At least 1 special
                                            character</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Hidden fields for role and user ID -->
                            <input type="hidden" name="role" value="{{ session('auth_role') }}">
                            <input type="hidden" name="user_id" value="{{ session('auth_id') }}">

                            <div class="card-footer text-end btn-page">
                                <div class="btn btn-outline-secondary">Cancel</div>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>



    @include('partials.footer')

    @push('scripts')
        <script src="../assets/js/plugins/croppr.min.js"></script>
        <script src="../assets/js/pages/page-croper.js"></script>
        <script>
            document.getElementById('triggerModal').addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('cropperModal'));
                modal.show();
            });
        </script>
        {{-- <script>
            document.getElementById('old_password').addEventListener('input', function() {
                const oldPassword = this.value;
                const role = document.getElementById('role').value;
                const userId = document.getElementById('userId').value;
                // alert(userId);

                if (oldPassword.length > 0) {
                    fetch('{{ route('password.check') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                old_password: oldPassword,
                                role: role,
                                userId: userId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            const feedback = document.getElementById('old_password_feedback');
                            if (data.status) {
                                feedback.textContent = data.message;
                                feedback.classList.remove('text-danger');
                                feedback.classList.add('text-success');
                            } else {
                                feedback.textContent = data.message;
                                feedback.classList.remove('text-success');
                                feedback.classList.add('text-danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    document.getElementById('old_password_feedback').textContent = '';
                }
            });
        </script> --}}
    @endpush
    @include('partials.theme')

@endsection
