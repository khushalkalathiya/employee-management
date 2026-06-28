<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width,initial-scale=1.0" name="viewport" />
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>PeopleCore — Dashboard</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Manrope:wght@300;400;500;600&display=swap"
        rel="stylesheet" />

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <!-- ═══════════════ SIDEBAR OVERLAY (mobile) ═══════════════ -->
    <div class="sb-overlay" id="sbOverlay" onclick="closeMobileSidebar()"></div>

    <!-- ═══════════════ APP SHELL ═══════════════ -->
    <div class="app-shell">

        <aside aria-label="Main navigation" class="sidebar" id="sidebar" role="navigation">

            <!-- Toggle button (desktop) -->
            <button aria-label="Toggle sidebar" class="sb-toggle-btn" id="sbToggleBtn" onclick="toggleSidebar()">
                <svg fill="none" height="12" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24"
                    width="12">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>

            <!-- Logo -->
            <div class="sb-logo">
                <div class="sb-logo-icon">
                    <svg fill="white" height="18" viewBox="0 0 24 24" width="18">
                        <path
                            d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 15.17 10.33 14 8 14zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </div>
                <div class="sb-logo-text">
                    <div
                        style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:800;color:var(--text);line-height:1.1">
                        People<span style="color:#3b82f6">Core</span></div>
                    <div
                        style="font-size:10px;color:var(--muted);font-family:'Outfit',sans-serif;font-weight:600;letter-spacing:.04em">
                        EMS Portal</div>
                </div>
            </div>

            <!-- Scrollable nav -->
            <div style="flex:1;overflow-y:auto;overflow-x:hidden;padding-bottom:12px">

                <div class="sb-section-label">Main</div>

                <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                        </svg></span>
                    <span class="nav-label">Dashboard</span>
                    <span class="nav-tooltip">Dashboard</span>
                </a>

                <a class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                    href="{{ route('employees.index') }}">
                    <span class="nav-icon">
                        <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 15.17 10.33 14 8 14zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </span>
                    <span class="nav-label">Employees</span>
                </a>

                <a class="nav-item {{ request()->routeIs('work-logs.*') ? 'active' : '' }}"
                    href="{{ route('work-logs.index') }}">
                    <span class="nav-icon">
                        <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
                        </svg>
                    </span>
                    <span class="nav-label">Work Logs</span>
                </a>

                <div class="sb-section-label">Organization</div>

                <a class="nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}"
                    href="{{ route('departments.index') }}">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z" />
                        </svg></span>
                    <span class="nav-label">Departments</span>
                    <span class="nav-tooltip">Departments</span>
                </a>

                <a class="nav-item {{ request()->routeIs('designations.*') ? 'active' : '' }}"
                    href="{{ route('designations.index') }}">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M20 6h-8.18l-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5l2 2h9v10z" />
                        </svg></span>
                    <span class="nav-label">Designations</span>
                    <span class="nav-tooltip">Designations</span>
                </a>

                <a class="nav-item {{ request()->routeIs('holidays.*') ? 'active' : '' }}"
                    href="{{ route('holidays.index') }}">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z" />
                        </svg></span>
                    <span class="nav-label">Holidays</span>
                    <span class="nav-tooltip">Holidays</span>
                </a>

                <div class="sb-section-label">Workforce</div>

                <a class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
                    href="{{ route('attendance.index') }}">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z" />
                        </svg></span>
                    <span class="nav-label">Attendance</span>
                    <span class="nav-tooltip">Attendance</span>
                </a>

                @can('leave.view')
                    <a class="nav-item {{ request()->routeIs('leaves.*') ? 'active' : '' }}"
                        href="{{ route('leaves.index') }}">
                        <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                                width="18">
                                <path
                                    d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z" />
                            </svg></span>
                        <span class="nav-label">Leave Management</span>
                        <span class="nav-tooltip">Leave Management</span>
                    </a>
                @endcan

                <a class="nav-item {{ request()->routeIs('leave-types.*') ? 'active' : '' }}"
                    href="{{ route('leave-types.index') }}">
                    <span class="nav-icon">
                        <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7v-5z" />
                        </svg>
                    </span>
                    <span class="nav-label">Leave Types</span>
                    <span class="nav-tooltip">Leave Types</span>
                </a>

                {{-- <a class="nav-item" href="#" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                            width="18">
                            <path
                                d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                        </svg></span>
                    <span class="nav-label">Payroll</span>
                    <span class="nav-tooltip">Payroll</span>
                </a> --}}

                @canany(['payroll.view', 'payroll.view.own'])
                    <a class="nav-item {{ request()->routeIs('payroll.*') ? 'active' : '' }}"
                        href="{{ route('payroll.index') }}">
                        <span class="nav-icon">
                            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                                <path
                                    d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                            </svg>
                        </span>
                        <span class="nav-label">Payroll</span>
                        <span class="nav-tooltip">Payroll</span>
                    </a>
                @endcanany

                {{-- <div class="sb-section-label">Projects</div>

                <a class="nav-item" href="#" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                            width="18">
                            <path
                                d="M20 6h-2.18c.07-.44.18-.88.18-1.32C18 3.15 16.85 2 15.5 2h-7C7.15 2 6 3.15 6 4.68c0 .44.11.88.18 1.32H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zM8 4.68C8 3.75 8.75 3 9.68 3h4.64C15.25 3 16 3.75 16 4.68c0 .44-.18.86-.47 1.17C15.26 6.18 14.72 6.4 14 6.4H10c-.72 0-1.26-.22-1.53-.55C8.18 5.54 8 5.12 8 4.68zM20 19H4V8h16v11z" />
                        </svg></span>
                    <span class="nav-label">Projects</span>
                    <span class="nav-badge">12</span>
                    <span class="nav-tooltip">Projects</span>
                </a>

                <a class="nav-item" href="#" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                            width="18">
                            <path
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM17.99 9l-1.41-1.42-6.59 6.59-2.58-2.57-1.42 1.41 4 3.99z" />
                        </svg></span>
                    <span class="nav-label">Tasks</span>
                    <span class="nav-badge" style="background:rgba(217,119,6,.12);color:#d97706">5</span>
                    <span class="nav-tooltip">Tasks</span>
                </a> --}}

                <div class="sb-section-label">Communication</div>

                <a class="nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}"
                    href="{{ route('chat.index') }}">
                    <span class="nav-icon">
                        <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12zM7 9h2v2H7V9zm4 0h2v2h-2V9zm4 0h2v2h-2V9z" />
                        </svg>
                    </span>
                    <span class="nav-label">Messages</span>
                    <span class="nav-tooltip">Messages</span>
                </a>

                <div class="sb-section-label">System</div>

                <a class="nav-item" href="#" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                            width="18">
                            <path
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                        </svg></span>
                    <span class="nav-label">Reports</span>
                    <span class="nav-tooltip">Reports</span>
                </a>

                <a class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                    href="{{ route('roles.index') }}">
                    <span class="nav-icon">
                        <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                            <path
                                d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18L19 6.3V11c0 4.54-2.88 8.79-7 10-4.12-1.21-7-5.46-7-10V6.3l7-3.12zM11 7h2v6h-2V7zm0 8h2v2h-2v-2z" />
                        </svg>
                    </span>
                    <span class="nav-label">Roles & Permission</span>
                </a>

                @php
                    $settingsRoute = null;

                    if (has_permission('settings.general.view')) {
                        $settingsRoute = route('settings.general.index');
                    } elseif (has_permission('settings.work_schedule.view')) {
                        $settingsRoute = route('settings.work-schedule.index');
                    }
                @endphp

                @if ($settingsRoute)
                    <a class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                        href="{{ $settingsRoute }}">

                        <span class="nav-icon">
                            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                                <path
                                    d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z" />
                            </svg>
                        </span>

                        <span class="nav-label">Settings</span>
                        <span class="nav-tooltip">Settings</span>
                    </a>
                @endif

            </div>

            <!-- Sidebar employee -->
            <div
                style="border-top:1px solid var(--border);padding:12px 10px;display:flex;align-items:center;gap:10px;overflow:hidden">
                <div class="avatar" style="width:34px;height:34px;font-size:13px;flex-shrink:0">JD</div>
                <div class="sb-logo-text" style="overflow:hidden;min-width:0">
                    <div
                        style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        James Doe</div>
                    <div style="font-size:11px;color:var(--muted);">HR Administrator</div>
                </div>
            </div>

        </aside>

        <div class="main-area" id="mainArea">

            <nav class="navbar" role="banner">
                <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
                    <!-- Hamburger (mobile) -->
                    <button aria-label="Open menu" class="hamburger-btn" onclick="openMobileSidebar()">
                        <svg fill="none" height="18" stroke-width="2.2" stroke="currentColor"
                            viewBox="0 0 24 24" width="18">
                            <line x1="3" x2="21" y1="6" y2="6" />
                            <line x1="3" x2="21" y1="12" y2="12" />
                            <line x1="3" x2="21" y1="18" y2="18" />
                        </svg>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="breadcrumbs">
                        <span class="bc-item">EMS</span>
                        <span class="bc-sep">›</span>
                        <span class="bc-item active" id="bcActive">Dashboard</span>
                    </div>

                    <!-- Search -->
                    {{-- <div class="search-wrap" style="margin-left:8px">
                        <span class="search-icon">
                            <svg fill="none" height="14" stroke-width="2" stroke="currentColor"
                                viewBox="0 0 24 24" width="14">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" x2="16.65" y1="21" y2="16.65" />
                            </svg>
                        </span>
                        <input aria-label="Search" class="search-inp" placeholder="Search employees, projects…"
                            type="text" />
                    </div> --}}
                </div>

                <div class="navbar-right">
                    <!-- Notification -->
                    <div aria-label="Notifications" class="icon-btn" role="button" tabindex="0"
                        title="Notifications">
                        <svg fill="currentColor" height="17" viewBox="0 0 24 24" width="17">
                            <path
                                d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                        </svg>
                        <span class="notif-dot"></span>
                    </div>

                    {{-- Timer --}}
                    <div aria-label="Timer" class="icon-btn" onclick="openTimerDrawer()" role="button"
                        tabindex="0" title="Timer">
                        <svg fill="currentColor" height="17" viewBox="0 0 24 24" width="17">
                            <path
                                d="M15 1H9v2h6V1zm-3 4C7.03 5 3 9.03 3 14s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7zm.5-11H11v5l4.25 2.52.75-1.23-3.5-2.04V10z" />
                        </svg>
                    </div>

                    <!-- Theme toggle -->
                    <button aria-label="Toggle theme" class="theme-pill" id="themeBtn" onclick="toggleTheme()">
                        <svg fill="currentColor" height="13" id="iconSun" style="display:none"
                            viewBox="0 0 24 24" width="13">
                            <path
                                d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .38-.39.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.38.39-1.02 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.38 1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.39 1.03.39 1.41 0l1.06-1.06z" />
                        </svg>
                        <svg fill="currentColor" height="13" id="iconMoon" viewBox="0 0 24 24" width="13">
                            <path
                                d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z" />
                        </svg>
                    </button>

                    <!-- Profile -->
                    <div style="position:relative">
                        <div aria-expanded="false" aria-haspopup="true" class="profile-btn" id="profileBtn"
                            onclick="toggleDropdown()" role="button" tabindex="0">
                            <div
                                class="emp-avatar flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-sm font-semibold text-white">
                                JD</div>
                            <div style="line-height:1.2">
                                <div class="profile-name">James Doe</div>
                                <div class="profile-role">HR Admin</div>
                            </div>
                            <svg fill="none" height="13" stroke-width="2.5" stroke="currentColor"
                                style="color:var(--muted);margin-left:2px" viewBox="0 0 24 24" width="13">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>

                        <!-- Dropdown -->
                        <div class="profile-dropdown" id="profileDropdown" role="menu">
                            <div class="dd-header">
                                <div class="dd-name">James Doe</div>
                                <div class="dd-email">james.doe@worksphere.com</div>
                            </div>
                            <a class="dd-item" href="{{ route('profile.basic-information.edit') }}" role="menuitem">
                                <svg fill="currentColor" height="15" viewBox="0 0 24 24" width="15">
                                    <path
                                        d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z" />
                                </svg>
                                Account Settings
                            </a>
                            <div class="dd-item" role="menuitem">
                                <svg fill="currentColor" height="15" viewBox="0 0 24 24" width="15">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                                Change Password
                            </div>
                            <div class="dd-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf

                                <button class="dd-item danger w-full text-left" role="menuitem" type="submit">
                                    <svg fill="currentColor" height="15" viewBox="0 0 24 24" width="15">
                                        <path
                                            d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </nav>

            <main class="content" id="mainContent" role="main">
                @if (session('success'))
                    <div
                        class="flash-message mb-4 flex items-center gap-3 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-2 text-green-600 transition-all duration-300 dark:border-green-400/20 dark:bg-green-500/10 dark:text-green-400">

                        <div class="shrink-0 text-lg font-bold">
                            ✓
                        </div>

                        <div class="flex-1 text-sm font-medium">
                            {{ session('success') }}
                        </div>

                        <button class="shrink-0 opacity-50 transition hover:opacity-100" onclick="closeFlash(this)"
                            type="button">
                            ✕
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="flash-message mb-4 flex items-center gap-3 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-red-600 transition-all duration-300 dark:border-red-400/20 dark:bg-red-500/10 dark:text-red-400">

                        <div class="shrink-0 text-lg font-bold">
                            ✕
                        </div>

                        <div class="flex-1 text-sm font-medium">
                            {{ session('error') }}
                        </div>

                        <button class="shrink-0 opacity-50 transition hover:opacity-100" onclick="closeFlash(this)"
                            type="button">
                            ✕
                        </button>
                    </div>
                @endif

                {{ $slot }}

            </main>
        </div>
    </div>


    <div class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
        id="deleteItemConfirmModel">

        <!-- Modal -->
        <div
            class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-950">

            <!-- Close -->
            <button
                class="absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white"
                onclick="closeConfirmModal()" type="button">

                <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

            </button>

            <div class="p-6">

                <!-- Icon -->
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">

                    <svg class="h-6 w-6" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 6h18" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                </div>

                <h3 class="mt-4 text-center text-lg font-semibold text-gray-900 dark:text-white" id="confirmTitle">
                    Delete Record
                </h3>

                <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400" id="confirmMessage">
                    Are you sure you would like to do this?
                </p>

            </div>

            <!-- Footer -->
            <div class="grid grid-cols-2 gap-3 bg-gray-50 p-4 pt-0 dark:bg-gray-950">

                <button
                    class="w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
                    onclick="closeConfirmModal()" type="button">
                    Cancel
                </button>

                <button
                    class="w-full cursor-pointer rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                    id="confirmActionBtn" type="button">
                    Delete
                </button>

            </div>

        </div>

    </div>

    <!-- ═══════════════ ATTENDANCE TIMER MODAL (DRAWER) ═══════════════ -->
    <div class="timer-modal fixed inset-0 z-[9998] hidden bg-black/40 backdrop-blur-sm transition-all duration-300"
        id="timerFormModal">
        <div
            class="timer-drawer absolute right-0 top-0 flex h-full w-full max-w-md translate-x-full flex-col overflow-y-auto border-l border-gray-200 bg-white shadow-2xl transition-transform duration-300 dark:border-gray-800 dark:bg-gray-950">

            <!-- Close button -->
            <div class="absolute right-4 top-4 z-10">
                <button
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    onclick="closeTimerDrawer()" type="button">
                    <svg class="h-4 w-4" fill="none" stroke-width="2.5" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <!-- Profile and Info Header -->
            <div
                class="relative overflow-hidden border-b border-gray-100 bg-gradient-to-b from-blue-50/50 via-white to-white px-6 pb-6 pt-10 dark:border-gray-800/60 dark:from-blue-950/20 dark:via-gray-950 dark:to-gray-950">
                <div class="flex flex-col items-center text-center">
                    @auth
                        @php
                            $user = auth()->user();
                            $avatarUrl =
                                $user->avatar ?:
                                'https://ui-avatars.com/api/?name=' .
                                    urlencode($user->full_name) .
                                    '&background=3b82f6&color=fff&size=128';
                            $userName = $user->full_name;
                            $userRole = $user->role ?: 'Employee';
                        @endphp
                    @else
                        @php
                            $avatarUrl =
                                'https://ui-avatars.com/api/?name=Guest+User&background=3b82f6&color=fff&size=128';
                            $userName = 'Guest User';
                            $userRole = 'Guest';
                        @endphp
                    @endauth

                    <div class="group relative">
                        <div
                            class="absolute -inset-1 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 opacity-20 blur transition duration-300 group-hover:opacity-40">
                        </div>
                        <img alt="Profile Picture"
                            class="relative h-20 w-20 rounded-full border-2 border-white object-cover shadow-md dark:border-gray-900"
                            src="{{ $avatarUrl }}">
                    </div>

                    <h2 class="mt-3 text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $userName }}
                    </h2>

                    <p class="mt-0.5 text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                        {{ $userRole }}
                    </p>

                    <div
                        class="mt-2.5 flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-gray-400 opacity-75"
                                id="statusIndicatorDot"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-gray-400"
                                id="statusIndicatorDotInner"></span>
                        </span>
                        <span class="font-semibold" id="drawerStatusText">Checking status...</span>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Skeleton Loader -->
                <div class="space-y-4" id="attendanceSkeleton">
                    <div class="animate-pulse space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="h-20 rounded-2xl bg-gray-100 dark:bg-gray-800/50"></div>
                            <div class="h-20 rounded-2xl bg-gray-100 dark:bg-gray-800/50"></div>
                        </div>
                        <div class="h-12 rounded-xl bg-gray-100 dark:bg-gray-800/50"></div>
                        <div class="mt-6 h-40 rounded-2xl bg-gray-100 dark:bg-gray-800/50"></div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="hidden" data-url="{{ route('attendance.current-status') }}" id="attendanceContent">

                    <!-- Dual Timer Grid -->
                    <div class="mb-6 grid grid-cols-2 gap-4">

                        <!-- Working Timer (Left) -->
                        <div
                            class="relative overflow-hidden rounded-2xl border border-emerald-100 bg-emerald-50/20 p-4 transition-all duration-300 dark:border-emerald-500/10 dark:bg-emerald-500/5">
                            <div class="flex items-center gap-2">
                                <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">Work
                                    Hours</span>
                            </div>
                            <div class="mt-2 text-2xl font-bold tracking-tight text-emerald-950 dark:text-emerald-200"
                                id="workingTimer">
                                00:00:00
                            </div>
                            <div class="mt-0.5 text-[9px] text-emerald-600 dark:text-emerald-500">Total active time
                            </div>
                        </div>

                        <!-- Break Timer (Right) -->
                        <div
                            class="relative overflow-hidden rounded-2xl border border-amber-100 bg-amber-50/20 p-4 transition-all duration-300 dark:border-amber-500/10 dark:bg-amber-500/5">
                            <div class="flex items-center gap-2">
                                <span class="flex h-2 w-2 rounded-full bg-amber-500"></span>
                                <span
                                    class="text-[10px] font-semibold uppercase tracking-wider text-amber-800 dark:text-amber-400">Break
                                    Time</span>
                            </div>
                            <div class="mt-2 text-2xl font-bold tracking-tight text-amber-950 dark:text-amber-200"
                                id="breakTimer">
                                00:00:00
                            </div>
                            <div class="mt-0.5 text-[9px] text-amber-600 dark:text-amber-500">Total break time</div>
                        </div>

                    </div>

                    <!-- Dynamic Action Buttons Section -->
                    <div class="space-y-3">

                        <!-- State 1: Clock In Button -->
                        <button
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4 font-semibold text-white shadow-lg shadow-blue-500/20 transition-all duration-200 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40 active:scale-[0.98]"
                            id="clockInBtn">
                            <svg class="h-5 w-5" fill="none" stroke-width="2.5" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3h7a3 3 0 013 3v1"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Clock In
                        </button>

                        <!-- State 2 & 3: Side-by-side Controls (Hidden by default) -->
                        <div class="grid hidden grid-cols-2 gap-4" id="activeControls">
                            <!-- Clock Out (Left) -->
                            <button
                                class="flex items-center justify-center gap-2 rounded-xl bg-rose-500 px-5 py-4 font-semibold text-white shadow-lg shadow-rose-500/20 transition-all duration-200 hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500/40 active:scale-[0.98]"
                                id="clockOutBtn">
                                <svg class="h-5 w-5" fill="none" stroke-width="2.5" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Clock Out
                            </button>

                            <!-- Break In / Break Out (Right) -->
                            <button
                                class="flex items-center justify-center gap-2 rounded-xl px-5 py-4 font-semibold text-white transition-all duration-200 active:scale-[0.98]"
                                id="breakToggleBtn">
                                <svg class="h-5 w-5" fill="none" id="breakIcon" stroke-width="2.5"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <!-- Dynamic -->
                                </svg>
                                <span id="breakBtnText">Break In</span>
                            </button>
                        </div>

                    </div>

                    <!-- Attendance Log Section -->
                    <div class="mt-8">
                        <div
                            class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800/60">
                            <h3
                                class="flex items-center gap-1.5 text-sm font-bold tracking-tight text-gray-800 dark:text-gray-200">
                                <svg class="h-4.5 w-4.5 text-gray-500" fill="none" stroke-width="2"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Today's Activity Logs
                            </h3>
                            <span
                                class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400"
                                id="logCountTag">
                                0 logs
                            </span>
                        </div>

                        <!-- Timeline container -->
                        <div class="relative ml-3 space-y-5 border-l border-gray-100 pl-5 dark:border-gray-800/80"
                            id="attendanceLogs">
                            <!-- Populated by JavaScript -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Attendance Logic JavaScript -->
    <script>
        window.setActive = function(el) {
            window.location.href = el.getAttribute('href');
        };
        document.addEventListener('DOMContentLoaded', () => {
            const timerModal = document.getElementById('timerFormModal');
            const skeleton = document.getElementById('attendanceSkeleton');
            const content = document.getElementById('attendanceContent');
            const workingTimerEl = document.getElementById('workingTimer');
            const breakTimerEl = document.getElementById('breakTimer');
            const drawerStatusText = document.getElementById('drawerStatusText');
            const statusIndicatorDot = document.getElementById('statusIndicatorDot');
            const statusIndicatorDotInner = document.getElementById('statusIndicatorDotInner');

            const clockInBtn = document.getElementById('clockInBtn');
            const activeControls = document.getElementById('activeControls');
            const clockOutBtn = document.getElementById('clockOutBtn');
            const breakToggleBtn = document.getElementById('breakToggleBtn');
            const breakBtnText = document.getElementById('breakBtnText');
            const breakIcon = document.getElementById('breakIcon');

            const statusUrl = content.getAttribute('data-url');
            const checkInUrl = "{{ route('attendance.check-in') }}";
            const checkOutUrl = "{{ route('attendance.check-out') }}";
            const breakStartUrl = "{{ route('attendance.break-start') }}";
            const breakEndUrl = "{{ route('attendance.break-end') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            let workingSeconds = 0;
            let breakSeconds = 0;
            let isClockedIn = false;
            let isOnBreak = false;
            let timerInterval = null;

            const logMeta = {
                clock_in: {
                    label: 'Clock In',
                    bgColor: 'bg-emerald-100 dark:bg-emerald-950/40',
                    textColor: 'text-emerald-600 dark:text-emerald-400',
                    icon: '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                },
                clock_out: {
                    label: 'Clock Out',
                    bgColor: 'bg-rose-100 dark:bg-rose-950/40',
                    textColor: 'text-rose-600 dark:text-rose-400',
                    icon: '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                },
                break_start: {
                    label: 'Break In (Paused)',
                    bgColor: 'bg-amber-100 dark:bg-amber-950/40',
                    textColor: 'text-amber-600 dark:text-amber-400',
                    icon: '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                },
                break_end: {
                    label: 'Break Out (Resumed)',
                    bgColor: 'bg-blue-100 dark:bg-blue-950/40',
                    textColor: 'text-blue-600 dark:text-blue-400',
                    icon: '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                }
            };

            function formatSeconds(totalSecs) {
                const hrs = Math.floor(totalSecs / 3600);
                const mins = Math.floor((totalSecs % 3600) / 60);
                const secs = totalSecs % 60;
                return [
                    hrs.toString().padStart(2, '0'),
                    mins.toString().padStart(2, '0'),
                    secs.toString().padStart(2, '0')
                ].join(':');
            }

            function startLocalTicking() {
                if (timerInterval) clearInterval(timerInterval);
                timerInterval = setInterval(() => {
                    if (isClockedIn) {
                        if (isOnBreak) {
                            breakSeconds++;
                            breakTimerEl.textContent = formatSeconds(breakSeconds);
                        } else {
                            workingSeconds++;
                            workingTimerEl.textContent = formatSeconds(workingSeconds);
                        }
                    }
                }, 1000);
            }

            function stopLocalTicking() {
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
            }

            function renderLogs(logs) {
                const logsContainer = document.getElementById('attendanceLogs');
                const logCountTag = document.getElementById('logCountTag');

                if (!logs || logs.length === 0) {
                    logsContainer.innerHTML = `
                        <div class="text-center py-6 text-gray-400 dark:text-gray-600 text-xs">
                            No attendance logs recorded for today.
                        </div>
                    `;
                    logCountTag.textContent = '0 logs';
                    return;
                }

                logCountTag.textContent = `${logs.length} ${logs.length === 1 ? 'log' : 'logs'}`;

                logsContainer.innerHTML = logs.map((log) => {
                    const meta = logMeta[log.type] || {
                        label: log.type,
                        bgColor: 'bg-gray-100 dark:bg-gray-800',
                        textColor: 'text-gray-600 dark:text-gray-400',
                        icon: '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                    };

                    return `
                        <div class="relative pl-6 pb-2 last:pb-0">
                            <!-- Dot with Icon Centered -->
                            <span class="absolute left-[-34px] top-0 flex h-7.5 w-7.5 items-center justify-center rounded-full ${meta.bgColor} ${meta.textColor} ring-4 ring-white dark:ring-gray-950 transition-all duration-300">
                                ${meta.icon}
                            </span>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 transition-colors">${meta.label}</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">${log.display_time}</span>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            function updateUIState() {
                // Formatting timers initially
                workingTimerEl.textContent = formatSeconds(workingSeconds);
                breakTimerEl.textContent = formatSeconds(breakSeconds);

                if (!isClockedIn) {
                    // State 1: Clocked out
                    clockInBtn.classList.remove('hidden');
                    activeControls.classList.add('hidden');

                    // Update indicator
                    drawerStatusText.textContent = "Offline / Clocked Out";
                    statusIndicatorDot.className =
                        "absolute inline-flex h-full w-full rounded-full bg-gray-400 opacity-75";
                    statusIndicatorDotInner.className = "relative inline-flex rounded-full h-2 w-2 bg-gray-400";
                    stopLocalTicking();
                } else {
                    // State 2 & 3: Clocked in
                    clockInBtn.classList.add('hidden');
                    activeControls.classList.remove('hidden');

                    if (isOnBreak) {
                        // State 3: On break
                        drawerStatusText.textContent = "On Break";
                        statusIndicatorDot.className =
                            "animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75";
                        statusIndicatorDotInner.className =
                            "relative inline-flex rounded-full h-2 w-2 bg-amber-400";

                        // Break Toggle Button Style: Green/Emerald for "Break Out" (Resume)
                        breakToggleBtn.className =
                            "flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-500/20 hover:shadow-emerald-600/30 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 active:scale-[0.98] transition-all duration-200";
                        breakBtnText.textContent = "Break Out";
                        breakIcon.innerHTML =
                            '<path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>';
                    } else {
                        // State 2: Active Work
                        drawerStatusText.textContent = "Active / Clocked In";
                        statusIndicatorDot.className =
                            "animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75";
                        statusIndicatorDotInner.className =
                            "relative inline-flex rounded-full h-2 w-2 bg-emerald-500";

                        // Break Toggle Button Style: Amber for "Break In" (Pause)
                        breakToggleBtn.className =
                            "flex items-center justify-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-lg shadow-amber-500/20 hover:shadow-amber-600/30 focus:outline-none focus:ring-2 focus:ring-amber-500/40 active:scale-[0.98] transition-all duration-200";
                        breakBtnText.textContent = "Break In";
                        breakIcon.innerHTML =
                            '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>';
                    }
                    startLocalTicking();
                }
            }

            async function fetchAttendanceStatus(showLoader = false) {
                if (showLoader) {
                    skeleton.classList.remove('hidden');
                    content.classList.add('hidden');
                }

                try {
                    const response = await fetch(statusUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const res = await response.json();
                    if (res.success) {
                        const data = res.data;
                        isClockedIn = data.is_clocked_in;
                        isOnBreak = data.is_on_break;
                        workingSeconds = data.working_seconds || 0;
                        breakSeconds = data.break_seconds || 0;

                        renderLogs(data.logs);
                        updateUIState();
                    }
                } catch (err) {
                    console.error('Failed to            async function handlePostAction(url, button, payload = {}) {
                const originalHtml = button.innerHTML;
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (result.is_late) {
                        button.disabled = false;
                        button.innerHTML = originalHtml;
                        if (window.openLateModal) {
                            window.openLateModal(url, button);
                        }
                        return;
                    }

                    if (result.success) {
                        if (window.showToast) {
                            window.showToast(result.message || 'Updated successfully', 'success');
                        }
                        // Refresh Livewire table components if they are on page
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('refresh-table');
                            Livewire.dispatch('refresh-control-card');
                        }
                        await fetchAttendanceStatus();
                    } else {
                        if (window.showToast) {
                            window.showToast(result.message || 'Operation failed', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Action error:', error);
                    if (window.showToast) {
                        window.showToast('Server error. Please try again.', 'error');
                    }
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                }
            }

            // Event Listeners for actions
            clockInBtn.addEventListener('click', () => handlePostAction(checkInUrl, clockInBtn));
            clockOutBtn.addEventListener('click', () => {
                closeTimerDrawer();
                if (window.location.pathname === '/attendance') {
                    const controlCardOutBtn = document.querySelector('[x-data] button[x-on\\:click="openClockOutModal()"]');
                    if (controlCardOutBtn) {
                        controlCardOutBtn.click();
                    } else {
                        window.location.href = '/attendance?trigger=clockout';
                    }
                } else {
                    window.location.href = '/attendance?trigger=clockout';
                }
            });
            breakToggleBtn.addEventListener('click', () => {
                const targetUrl = isOnBreak ? breakEndUrl : breakStartUrl;
                handlePostAction(targetUrl, breakToggleBtn);
            });

            // Intercept drawer opening to fetch latest status
            const originalOpenTimerDrawer = window.openTimerDrawer;
            window.openTimerDrawer = function() {
                if (originalOpenTimerDrawer) originalOpenTimerDrawer();
                fetchAttendanceStatus(true);
            };

            // Fetch once on page load to initialize background state
            fetchAttendanceStatus(false);
        });

        // Late Clock In Reason Modal logic
        let lateReasonTargetUrl = '';
        let lateReasonTargetBtn = null;

        window.openLateModal = function(url, btn) {
            lateReasonTargetUrl = url;
            lateReasonTargetBtn = btn;
            document.getElementById('lateReasonText').value = '';
            modalHelper.open('lateReasonModal');
        };

        window.closeLateModal = function() {
            modalHelper.close('lateReasonModal');
        };

        window.submitLateReason = async function(e) {
            e.preventDefault();
            const reason = document.getElementById('lateReasonText').value;
            closeLateModal();
            
            if (lateReasonTargetBtn && lateReasonTargetBtn.id === 'clockInBtn') {
                const originalHtml = lateReasonTargetBtn.innerHTML;
                lateReasonTargetBtn.disabled = true;
                lateReasonTargetBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;
                
                try {
                    const response = await fetch(lateReasonTargetUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ late_reason: reason })
                    });
                    const result = await response.json();
                    if (result.success) {
                        if (window.showToast) {
                            window.showToast(result.message || 'Updated successfully', 'success');
                        }
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('refresh-table');
                            Livewire.dispatch('refresh-control-card');
                        }
                        const drawerOpen = !document.getElementById('timerFormModal').classList.contains('hidden');
                        if (drawerOpen) {
                            const skeleton = document.getElementById('attendanceSkeleton');
                            const content = document.getElementById('attendanceContent');
                            skeleton.classList.remove('hidden');
                            content.classList.add('hidden');
                            const statusUrl = content.getAttribute('data-url');
                            const res = await fetch(statusUrl, {
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                            });
                            const data = await res.json();
                            if (data.success) {
                                isClockedIn = data.data.is_clocked_in;
                                isOnBreak = data.data.is_on_break;
                                workingSeconds = data.data.working_seconds || 0;
                                breakSeconds = data.data.break_seconds || 0;
                                const renderLogs = (logs) => {
                                    const container = document.getElementById('logContainer');
                                    container.innerHTML = '';
                                    if (logs && logs.length > 0) {
                                        logs.forEach(log => {
                                            const item = document.createElement('div');
                                            item.className = 'flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-gray-800/20 rounded-xl';
                                            item.innerHTML = `<span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg></span><div><p class="text-xs font-bold text-gray-800 dark:text-gray-200">Clock In</p><p class="text-[10px] text-gray-500 dark:text-gray-400">${log.display_time}</p></div>`;
                                            container.appendChild(item);
                                        });
                                    }
                                };
                                renderLogs(data.data.logs);
                                const workingTimerEl = document.getElementById('workingTimer');
                                const breakTimerEl = document.getElementById('breakTimer');
                                const drawerStatusText = document.getElementById('drawerStatusText');
                                const statusIndicatorDot = document.getElementById('statusIndicatorDot');
                                const statusIndicatorDotInner = document.getElementById('statusIndicatorDotInner');
                                const clockInBtn = document.getElementById('clockInBtn');
                                const activeControls = document.getElementById('activeControls');
                                const breakBtnText = document.getElementById('breakBtnText');
                                const breakIcon = document.getElementById('breakIcon');
                                workingTimerEl.textContent = [Math.floor(workingSeconds/3600), Math.floor((workingSeconds%3600)/60), workingSeconds%60].map(v => String(v).padStart(2, '0')).join(':');
                                breakTimerEl.textContent = [Math.floor(breakSeconds/3600), Math.floor((breakSeconds%3600)/60), breakSeconds%60].map(v => String(v).padStart(2, '0')).join(':');
                                drawerStatusText.textContent = 'Clocked In';
                                drawerStatusText.style.color = '#10b981';
                                statusIndicatorDot.className = 'flex h-2.5 w-2.5 relative';
                                statusIndicatorDotInner.className = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75';
                                statusIndicatorDot.querySelector('span:last-child').className = 'relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500';
                                clockInBtn.classList.add('hidden');
                                activeControls.classList.remove('hidden');
                                breakBtnText.textContent = 'Break In';
                                breakIcon.innerHTML = '<path d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>';
                            }
                        }
                    } else {
                        if (window.showToast) {
                            window.showToast(result.message || 'Operation failed', 'error');
                        }
                    }
                } catch (err) {
                    if (window.showToast) {
                        window.showToast('Server error. Please try again.', 'error');
                    }
                } finally {
                    lateReasonTargetBtn.disabled = false;
                    lateReasonTargetBtn.innerHTML = originalHtml;
                }
            } else if (lateReasonTargetBtn) {
                await window.handleAttendanceAction(lateReasonTargetUrl, lateReasonTargetBtn, { late_reason: reason });
            }
        };
    </script>

    <!-- Late Clock In Reason Modal (matches Holiday form styling, responsiveness, dark mode) -->
    <div id="lateReasonModal" class="modal fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300" x-cloak>
        <div class="modal-content relative w-full max-w-md scale-95 overflow-hidden rounded-2xl border border-gray-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-gray-800 dark:bg-gray-950">
            <button onclick="closeLateModal()" class="modal-close-btn close-icon absolute right-4 top-4 cursor-pointer text-gray-400 transition hover:text-gray-700 dark:hover:text-white" type="button">
                <svg class="h-5 w-5" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-2">
                    <svg class="h-6 w-6 text-amber-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Late Clock In Reason
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    You are clocking in late today. Please explain why you are late.
                </p>
            </div>

            <form id="lateReasonForm" onsubmit="submitLateReason(event)">
                <div class="p-6 pt-0">
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Why are you late? <span class="text-rose-500">*</span></label>
                        <textarea id="lateReasonText" required class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-sm text-gray-900 dark:text-white p-3 outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" placeholder="Please provide your reason..."></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 p-4 dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800/60">
                    <button onclick="closeLateModal()" class="btn-ghost" type="button">
                        Cancel
                    </button>
                    <button class="btn-primary bg-amber-500 hover:bg-amber-600 border-amber-500 text-white" type="submit">
                        Confirm & Clock In
                    </button>
                </div>
            </form>
        </div>
    </div>

    @livewireScriptConfig
</body>

</html>
