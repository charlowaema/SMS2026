
<div class="col-lg-2 school-sidebar p-0">

    <div class="offcanvas-lg offcanvas-start"
         tabindex="-1"
         id="sidebar"
         aria-labelledby="sidebarLabel">

        {{-- Sidebar Header --}}
        <div class="offcanvas-header border-bottom">

            <h5 class="offcanvas-title fw-bold sidebar-brand"
                id="sidebarLabel">

                <i class="bi bi-mortarboard-fill me-2"></i>
                Student System

            </h5>

            <button type="button"
                    id="sidebarClose"
                    class="btn btn-light border rounded-circle d-lg-none"
                    aria-label="Close sidebar"
                    style="width: 40px; height: 40px;">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        {{-- Sidebar Body --}}
        <div class="offcanvas-body d-flex flex-column p-3">

            {{-- Desktop Sidebar Branding --}}
            <h4 class="d-none d-lg-block fw-bold text-white">

                <i class="bi bi-mortarboard-fill me-2"></i>
                Student System

            </h4>

            <hr class="d-none d-lg-block">

            {{-- Navigation --}}
            <ul class="nav nav-pills flex-column sidebar-nav">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard

                    </a>
                </li>

                {{-- Academic Setup Heading --}}
                <li class="nav-item mt-4 mb-2 px-3">
                    <small class="fw-bold text-uppercase"
                        style="color: #93c5fd; letter-spacing: 1px;">
                        Academic Setup
                    </small>
                </li>

                {{-- Academic Years --}}
                <li class="nav-item">
                    <a href="{{ route('academic-years.index') }}"
                       class="nav-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}">

                        <i class="bi bi-calendar3 me-2"></i>
                        Academic Years

                    </a>
                </li>

                {{-- Semesters --}}
                <li class="nav-item">
                    <a href="{{ route('semesters.index') }}"
                       class="nav-link {{ request()->routeIs('semesters.*') ? 'active' : '' }}">

                        <i class="bi bi-calendar-range me-2"></i>
                        Semesters

                    </a>
                </li>

                {{-- Courses --}}
                <li class="nav-item">
                    <a href="{{ route('courses.index') }}"
                       class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">

                        <i class="bi bi-book me-2"></i>
                        Courses

                    </a>
                </li>

                {{-- Student Management Heading --}}
                <li class="nav-item mt-4 mb-2 px-3">
                    <small class="fw-bold text-uppercase"
                        style="color: #93c5fd; letter-spacing: 1px;">
                        Student Management
                    </small>
                </li>

                {{-- Students --}}
                <li class="nav-item">
                    <a href="{{ route('students.index') }}"
                       class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">

                        <i class="bi bi-people me-2"></i>
                        Students

                    </a>
                </li>

                {{-- Enrollments --}}
                <li class="nav-item">
                    <a href="{{ route('enrollments.index') }}"
                       class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">

                        <i class="bi bi-person-check me-2"></i>
                        Enrollments

                    </a>
                </li>

                {{-- Finance Heading --}}
                <li class="nav-item mt-4 mb-2 px-3">
                    <small class="fw-bold text-uppercase"
                        style="color: #93c5fd; letter-spacing: 1px;">
                        Finance
                    </small>
                </li>

                {{-- Fee Structures --}}
                <li class="nav-item">
                    <a href="{{ route('fee-structures.index') }}"
                       class="nav-link {{ request()->routeIs('fee-structures.*') ? 'active' : '' }}">

                        <i class="bi bi-cash-stack me-2"></i>
                        Fee Structures

                    </a>
                </li>

                {{-- Payments --}}
                <li class="nav-item">
                    <a href="{{ route('payments.index') }}"
                       class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">

                        <i class="bi bi-credit-card me-2"></i>
                        Payments

                    </a>
                </li>

                {{-- Reports --}}
                <li class="nav-item">
                    <a href="{{ route('reports.payments') }}"
                       class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">

                        <i class="bi bi-bar-chart me-2"></i>
                        Reports

                    </a>
                </li>

                {{-- Profile --}}
                <li class="nav-item mt-3">
                    <a href="#" class="nav-link">

                        <i class="bi bi-person-circle me-2"></i>
                        Profile

                    </a>
                </li>

            </ul>

        </div>

    </div>

</div>
