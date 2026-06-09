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

                <a class="nav-item" href="#" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                            width="18">
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

                <a class="nav-item" href="#" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                            width="18">
                            <path
                                d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                        </svg></span>
                    <span class="nav-label">Payroll</span>
                    <span class="nav-tooltip">Payroll</span>
                </a>

                <div class="sb-section-label">Projects</div>

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

                <a class="nav-item" href="#" onclick="setActive(this);return false">
                    <span class="nav-icon"><svg fill="currentColor" height="18" viewBox="0 0 24 24"
                            width="18">
                            <path
                                d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z" />
                        </svg></span>
                    <span class="nav-label">Settings</span>
                    <span class="nav-tooltip">Settings</span>
                </a>

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
                            <div class="dd-item" role="menuitem">
                                <svg fill="currentColor" height="15" viewBox="0 0 24 24" width="15">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                                My Profile
                            </div>
                            <div class="dd-item" role="menuitem">
                                <svg fill="currentColor" height="15" viewBox="0 0 24 24" width="15">
                                    <path
                                        d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z" />
                                </svg>
                                Account Settings
                            </div>
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

    </div>
    @livewireScriptConfig
</body>

</html>
