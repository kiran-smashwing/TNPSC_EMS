<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="../dashboard/index.html" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ asset('storage/assets/images/logo-dark.png') }}" class="img-fluid logo-lg" alt="logo" />
                {{-- <span class="badge bg-light-success rounded-pill ms-2 theme-version">v9.4.1</span> --}}
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">

                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . current_user()->profile_image) }}" alt="user-image"
                                class="user-avtar wid-50 rounded-circle" />
                        </div>

                        <div class="flex-grow-1 ms-3 me-2">
                            {{-- Displaying the user's display name --}}
                            <h6 class="mb-0">{{ Str::limit(current_user()->display_name, 15, '...') }}</h6>

                            {{-- Displaying the user's role in uppercase --}}
                            <small>{{ strtoupper(str_replace('_', ' ', session('athu_display_role'))) }}</small>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse"
                            href="#pc_sidebar_userlink">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href={{ route('myaccount') }}>
                                <i class="ti ti-user"></i>
                                <span>My Account</span>
                            </a>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti ti-power"></i>
                                <span>Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href={{route('dashboard')}}>Default</a></li>
                        {{-- <li class="pc-item"><a class="pc-link" href="../dashboard/analytics.html">Analytics</a></li>
                        <li class="pc-item"><a class="pc-link" href="../dashboard/finance.html">Finance</a></li> --}}
                    </ul>
                </li>
               @hasPremission('heading')
                <li class="pc-item pc-caption">
                    <label>Masters</label>
                </li>
                @endhasPermission
                @hasPermission('district-masters')
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-data"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">District Masters</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            @hasPermission('view-all-districts')
                                <li class="pc-item"><a class="pc-link" href="{{ route('district.index') }}">District
                                        Collectorates </a></li>
                            @endhasPermission
                           <li class="pc-item"><a class="pc-link" href="{{ route('centers.index') }}">Centers (Taluks)</a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('treasury-officers.index') }}">Treasury
                                    Officers</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('mobile-team-staffs.index') }}">Mobile
                                    Team Staffs</a></li>
                        </ul>
                    </li>

                @endhasPermission
                @hasPermission('venues-masters')
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-data"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Venues Masters</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            @hasPermission('view-all-venue')
                                <li class="pc-item"><a class="pc-link" href="{{ route('venues.index') }}">Venues</a>
                                </li>
                            @endhasPermission
                            @hasPermission('view-all-chief-invigilators')
                            <li class="pc-item"><a class="pc-link" href="{{ route('chief-invigilators.index') }}">Cheif
                                    Invigilators </a>
                            </li>
                            @endhasPermission
                            <li class="pc-item"><a class="pc-link" href="{{ route('invigilators.index') }}">Invigilators
                                </a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('scribes.index') }}">Scribes </a>
                            </li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('ci-assistant') }}">CI Assistants </a>
                            </li>
                        </ul>
                    </li>
                @endhasPermission
                @hasPermission('departments-masters')
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-data"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Department Masters </span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">

                        <li class="pc-item"><a class="pc-link" href="{{ route('exam-services.index') }}">Examination
                                Services</a>
                        </li>


                        <li class="pc-item"><a class="pc-link" href="{{ route('ci-checklist') }}">CI Checklist</a>
                        </li>

                        <li class="pc-item"><a class="pc-link" href="{{ route('role') }}">Roles</a>
                        </li>
                        <li class="pc-item"><a class="pc-link"
                                href="{{ route('department-officials.index') }}">Department
                                Officials</a>
                        </li>
                    </ul>
                </li>
                @endhasPermission
                <li class="pc-item pc-caption">
                    <label>Exams</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-presentation-chart"></use>
                    </svg>
                </li>
                {{-- <li class="pc-item">
                    <a href="{{ route('my-exam.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-text-align-justify-center"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">My Exams</span>
                    </a>
                </li> --}}
                <li class="pc-item">
                    <a href="{{ route('current-exam.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-text-align-justify-center"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Current Exams</span>
                    </a>
                </li>
                {{-- <li class="pc-item">
                    <a href="{{ route('my-exam.ciTask', '20241126092207') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-text-align-justify-center"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">CI - Current Exams</span>
                    </a>
                </li> --}}
                {{-- <li class="pc-item">
                    <a href="{{ route('current-exam.ciMeeting') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-text-align-justify-center"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">CI - Meeting</span>
                    </a>
                </li> --}}
                {{-- <li class="pc-item">
                <a href="{{ route('current-exam.districtTask') }}" class="pc-link">
                    <span class="pc-micon">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-text-align-justify-center"></use>
                        </svg>
                    </span>
                    <span class="pc-mtext">District - Current Exams</span>
                </a>
            </li> --}}
                {{-- <li class="pc-item">
                    <a href="{{ route('my-exam.centerexamTask') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-text-align-justify-center"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Center - Current Exams</span>
                    </a>
                </li> --}}
                {{-- <li class="pc-item">
                <a href="{{ route('my-exam.mobileTeamTask', '20241126092207') }}" class="pc-link">
                    <span class="pc-micon">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-text-align-justify-center"></use>
                        </svg>
                    </span>
                    <span class="pc-mtext">MobileTeam - Current Exams</span>
                </a>
            </li> --}}
                <li class="pc-item">
                    <a href="{{ route('charted-vehicle-routes.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-data"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Charted Vehicle Routes</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="{{ route('charted-vehicle-routes.downward-journey-routes') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-fatrows"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext"></span>CV Downward Updates
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{ route('completed-exam') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-presentation-chart"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext"></span>Completed Exams</a>
                </li>
                <li class="pc-item pc-caption">
                    <label>Reports</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-layer"></use>
                    </svg>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{route('emergency-alarm-notification.report')}}">Emergency Alarm Notifications</a>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{route('exam-material-discrepancy.report')}}">Exam Materials Discrepancy</a>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{ route('attendance.report') }}">Candidate Attendance</a>
                </li>
                
                <li class="pc-item"><a class="pc-link" href="{{route('omr-account.report')}}">Replacement of OMR/QCA</a>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{route('candidate-remarks.report')}}">Candidate Remarks</a>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{route('consolidated-statement.report')}}">Consolidated Statement </a>
                </li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('expenditure-statment.report') }}">Expenditure Statement</a>
                    </li>
                    <li class="pc-item"><a class="pc-link" href="{{route('ci-attendace.report')}}">CI Meeting Attendance</a>
                    </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-layer"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">OMR/QCA Delivered</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#"> Print to District Treasury</a></li>
                        <li class="pc-item"><a class="pc-link" href="#"> District Treasury to Sub Treasury</a></li>
                        <li class="pc-item"><a class="pc-link" href="#">Sub Treasury to Mobile Team</a></li>
                        <li class="pc-item"><a class="pc-link" href="#"> Mobile Team to Chief Invigilator</a> </li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-layer"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Bundle Collection</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#"> Chief Invigilator to Moible Team</a></li>
                        <li class="pc-item"><a class="pc-link" href="#"> Mobile Team to Sub Treasury </a></li>
                        <li class="pc-item"><a class="pc-link" href="#">Sub Treasury to District Treasury</a></li>
                    </ul>
                </li>
                <li class="pc-item pc-caption">
                    <label> Email</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-sms"></use>
                    </svg>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sms"></use>
                            </svg> </span><span class="pc-mtext">Email Template</span><span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="../elements/icon-feather.html">ID to
                                Collectorate </a></li>

                        <li class="pc-item"><a class="pc-link" href="../elements/icon-custom.html">Center to Venue
                            </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
