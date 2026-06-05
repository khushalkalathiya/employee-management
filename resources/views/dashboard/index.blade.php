<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}


    <!-- ── Welcome Banner ── -->
    <div class="animate-fu d1" style="margin-bottom:22px">
        <div class="card card-pad"
            style="background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 55%,#1e40af 100%);border-color:transparent;position:relative;overflow:hidden">
            <!-- decorative circles -->
            <div
                style="position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.07);top:-60px;right:80px">
            </div>
            <div
                style="position:absolute;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.05);bottom:-40px;right:20px">
            </div>
            <div
                style="position:absolute;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.08);top:10px;right:220px">
            </div>
            <div
                style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;position:relative;z-index:1">
                <div>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                        <span
                            style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.25);border-radius:99px;padding:3px 12px;font-family:'Outfit',sans-serif;font-size:11px;font-weight:700;color:rgba(255,255,255,.95);letter-spacing:.07em;text-transform:uppercase">
                            ✦ Good Morning
                        </span>
                    </div>
                    <h1
                        style="font-family:'Outfit',sans-serif;font-size:clamp(20px,3vw,26px);font-weight:800;color:white;margin-bottom:5px">
                        Welcome back, James! 👋</h1>
                    <p style="color:rgba(255,255,255,.72);font-size:13.5px">Today is <strong id="todayDate"
                            style="color:rgba(255,255,255,.92)"></strong> — Here's what's
                        happening across your workforce.</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <button class="btn-primary" onclick="return false"
                        style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);box-shadow:none;backdrop-filter:blur(8px)">
                        <svg fill="currentColor" height="14" viewBox="0 0 24 24" width="14">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                        </svg>
                        Add Employee
                    </button>
                    <button class="btn-ghost" onclick="return false"
                        style="background:rgba(255,255,255,.10);border-color:rgba(255,255,255,.25);color:white">
                        <svg fill="currentColor" height="14" viewBox="0 0 24 24" width="14">
                            <path
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                        </svg>
                        View Reports
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Stats Grid ── -->
    <div class="stats-grid animate-fu d2" style="margin-bottom:20px">

        <div class="stat-card blue">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div class="stat-icon blue">
                    <svg fill="currentColor" height="22" viewBox="0 0 24 24" width="22">
                        <path
                            d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 15.17 10.33 14 8 14zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </div>
                <span class="status-pill pill-blue">+12 this month</span>
            </div>
            <div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:32px;font-weight:800;color:var(--text);line-height:1">
                    248</div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:var(--muted);margin-top:3px">
                    Total Employees</div>
            </div>
            <div class="prog-track">
                <div class="prog-fill" style="width:78%;background:linear-gradient(90deg,#3b82f6,#2563eb)"></div>
            </div>
        </div>

        <div class="stat-card green">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div class="stat-icon green">
                    <svg fill="currentColor" height="22" viewBox="0 0 24 24" width="22">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
                <span class="status-pill pill-green">96.4% rate</span>
            </div>
            <div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:32px;font-weight:800;color:var(--text);line-height:1">
                    219</div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:var(--muted);margin-top:3px">
                    Present Today</div>
            </div>
            <div class="prog-track">
                <div class="prog-fill" style="width:88%;background:linear-gradient(90deg,#10b981,#059669)"></div>
            </div>
        </div>

        <div class="stat-card amber">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div class="stat-icon amber">
                    <svg fill="currentColor" height="22" viewBox="0 0 24 24" width="22">
                        <path
                            d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z" />
                    </svg>
                </div>
                <span class="status-pill pill-amber">Needs Review</span>
            </div>
            <div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:32px;font-weight:800;color:var(--text);line-height:1">
                    7</div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:var(--muted);margin-top:3px">
                    Pending Leaves</div>
            </div>
            <div class="prog-track">
                <div class="prog-fill" style="width:35%;background:linear-gradient(90deg,#fbbf24,#d97706)"></div>
            </div>
        </div>

        <div class="stat-card violet">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div class="stat-icon violet">
                    <svg fill="currentColor" height="22" viewBox="0 0 24 24" width="22">
                        <path
                            d="M20 6h-2.18c.07-.44.18-.88.18-1.32C18 3.15 16.85 2 15.5 2h-7C7.15 2 6 3.15 6 4.68c0 .44.11.88.18 1.32H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zM20 19H4V8h16v11z" />
                    </svg>
                </div>
                <span class="status-pill pill-blue">4 due soon</span>
            </div>
            <div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:32px;font-weight:800;color:var(--text);line-height:1">
                    12</div>
                <div
                    style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:var(--muted);margin-top:3px">
                    Active Projects</div>
            </div>
            <div class="prog-track">
                <div class="prog-fill" style="width:60%;background:linear-gradient(90deg,#a78bfa,#7c3aed)"></div>
            </div>
        </div>

    </div>

    <!-- ── Two Column: Activity + Dept Overview ── -->
    <div class="two-col animate-fu d3" style="margin-bottom:20px">

        <!-- Activity Timeline -->
        <div class="card card-pad">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
                <div>
                    <div class="section-title">Recent Activity</div>
                    <div class="section-sub">Live employee actions &amp; events</div>
                </div>
                <button class="btn-ghost" style="font-size:12px;padding:6px 12px">View All</button>
            </div>

            <div style="display:flex;flex-direction:column">
                <!-- Item 1 -->
                <div style="display:flex;gap:12px;padding-bottom:14px">
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                        <div class="timeline-dot" style="background:rgba(37,99,235,.12)">
                            <svg fill="#2563eb" height="17" viewBox="0 0 24 24" width="17">
                                <path
                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm4.24 16L12 15.45 7.77 18l1.12-4.81-3.73-3.23 4.92-.42L12 5l1.92 4.53 4.92.42-3.73 3.23L16.23 18z" />
                            </svg>
                        </div>
                        <div class="tl-line"></div>
                    </div>
                    <div style="flex:1;padding-top:4px">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                            <div
                                style="font-family:'Outfit',sans-serif;font-size:13.5px;font-weight:700;color:var(--text)">
                                John Mitchell checked in</div>
                            <span class="status-pill pill-green">On Time</span>
                        </div>
                        <div style="font-size:12px;color:var(--muted);margin-top:3px">Engineering Dept · 2
                            minutes ago</div>
                    </div>
                </div>
                <!-- Item 2 -->
                <div style="display:flex;gap:12px;padding-bottom:14px">
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                        <div class="timeline-dot" style="background:rgba(217,119,6,.12)">
                            <svg fill="#d97706" height="17" viewBox="0 0 24 24" width="17">
                                <path
                                    d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z" />
                            </svg>
                        </div>
                        <div class="tl-line"></div>
                    </div>
                    <div style="flex:1;padding-top:4px">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                            <div
                                style="font-family:'Outfit',sans-serif;font-size:13.5px;font-weight:700;color:var(--text)">
                                Sarah Connor submitted leave</div>
                            <span class="status-pill pill-amber">Pending</span>
                        </div>
                        <div style="font-size:12px;color:var(--muted);margin-top:3px">HR Department · 18
                            minutes ago</div>
                    </div>
                </div>
                <!-- Item 3 -->
                <div style="display:flex;gap:12px;padding-bottom:14px">
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                        <div class="timeline-dot" style="background:rgba(124,58,237,.12)">
                            <svg fill="#7c3aed" height="17" viewBox="0 0 24 24" width="17">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM17.99 9l-1.41-1.42-6.59 6.59-2.58-2.57-1.42 1.41 4 3.99z" />
                            </svg>
                        </div>
                        <div class="tl-line"></div>
                    </div>
                    <div style="flex:1;padding-top:4px">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                            <div
                                style="font-family:'Outfit',sans-serif;font-size:13.5px;font-weight:700;color:var(--text)">
                                Mike Johnson completed task</div>
                            <span class="status-pill pill-blue">Completed</span>
                        </div>
                        <div style="font-size:12px;color:var(--muted);margin-top:3px">Project Alpha · 45
                            minutes ago</div>
                    </div>
                </div>
                <!-- Item 4 -->
                <div style="display:flex;gap:12px;padding-bottom:14px">
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                        <div class="timeline-dot" style="background:rgba(5,150,105,.12)">
                            <svg fill="#059669" height="17" viewBox="0 0 24 24" width="17">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                        <div class="tl-line"></div>
                    </div>
                    <div style="flex:1;padding-top:4px">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                            <div
                                style="font-family:'Outfit',sans-serif;font-size:13.5px;font-weight:700;color:var(--text)">
                                Emma Williams updated profile</div>
                            <span class="status-pill pill-gray">Updated</span>
                        </div>
                        <div style="font-size:12px;color:var(--muted);margin-top:3px">Design Team · 1 hour
                            ago</div>
                    </div>
                </div>
                <!-- Item 5 -->
                <div style="display:flex;gap:12px">
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                        <div class="timeline-dot" style="background:rgba(239,68,68,.10)">
                            <svg fill="#ef4444" height="17" viewBox="0 0 24 24" width="17">
                                <path
                                    d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                            </svg>
                        </div>
                    </div>
                    <div style="flex:1;padding-top:4px">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                            <div
                                style="font-family:'Outfit',sans-serif;font-size:13.5px;font-weight:700;color:var(--text)">
                                Payroll processing started</div>
                            <span class="status-pill pill-red">Processing</span>
                        </div>
                        <div style="font-size:12px;color:var(--muted);margin-top:3px">Finance Dept · 2
                            hours ago</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Overview -->
        <div class="card card-pad">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
                <div>
                    <div class="section-title">Department Overview</div>
                    <div class="section-sub">Headcount &amp; attendance by dept</div>
                </div>
                <!-- Mini bar chart -->
                <div class="bar-chart" id="miniChart"></div>
            </div>

            <div>
                <div class="dept-row">
                    <div
                        style="width:34px;height:34px;border-radius:10px;background:rgba(37,99,235,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="#2563eb" height="16" viewBox="0 0 24 24" width="16">
                            <path
                                d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z" />
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--text)">Engineering</span>
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:12px;font-weight:600;color:var(--muted)">62
                                / 68</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill"
                                style="width:91%;background:linear-gradient(90deg,#3b82f6,#2563eb)"></div>
                        </div>
                    </div>
                </div>
                <div class="dept-row">
                    <div
                        style="width:34px;height:34px;border-radius:10px;background:rgba(5,150,105,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="#059669" height="16" viewBox="0 0 24 24" width="16">
                            <path
                                d="M20 6h-2.18c.07-.44.18-.88.18-1.32C18 3.15 16.85 2 15.5 2h-7C7.15 2 6 3.15 6 4.68c0 .44.11.88.18 1.32H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zM20 19H4V8h16v11z" />
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--text)">Human
                                Resources</span>
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:12px;font-weight:600;color:var(--muted)">28
                                / 30</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill"
                                style="width:93%;background:linear-gradient(90deg,#10b981,#059669)"></div>
                        </div>
                    </div>
                </div>
                <div class="dept-row">
                    <div
                        style="width:34px;height:34px;border-radius:10px;background:rgba(217,119,6,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="#d97706" height="16" viewBox="0 0 24 24" width="16">
                            <path
                                d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--text)">Finance</span>
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:12px;font-weight:600;color:var(--muted)">18
                                / 22</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill"
                                style="width:82%;background:linear-gradient(90deg,#fbbf24,#d97706)"></div>
                        </div>
                    </div>
                </div>
                <div class="dept-row">
                    <div
                        style="width:34px;height:34px;border-radius:10px;background:rgba(124,58,237,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="#7c3aed" height="16" viewBox="0 0 24 24" width="16">
                            <path
                                d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z" />
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--text)">Design
                                &amp; UX</span>
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:12px;font-weight:600;color:var(--muted)">24
                                / 26</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill"
                                style="width:92%;background:linear-gradient(90deg,#a78bfa,#7c3aed)"></div>
                        </div>
                    </div>
                </div>
                <div class="dept-row">
                    <div
                        style="width:34px;height:34px;border-radius:10px;background:rgba(239,68,68,.10);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg fill="#ef4444" height="16" viewBox="0 0 24 24" width="16">
                            <path
                                d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z" />
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--text)">Operations</span>
                            <span
                                style="font-family:'Outfit',sans-serif;font-size:12px;font-weight:600;color:var(--muted)">42
                                / 52</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill"
                                style="width:81%;background:linear-gradient(90deg,#f87171,#ef4444)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Quick Actions ── -->
    <div class="animate-fu d4" style="margin-bottom:20px">
        <div class="card card-pad">
            <div style="margin-bottom:16px">
                <div class="section-title">Quick Actions</div>
                <div class="section-sub">Common HR tasks at your fingertips</div>
            </div>
            <div class="qa-grid">
                <div class="qa-btn">
                    <div class="qa-icon"><svg fill="currentColor" height="20" viewBox="0 0 24 24"
                            width="20">
                            <path
                                d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg></div>
                    Add Employee
                </div>
                <div class="qa-btn">
                    <div class="qa-icon"><svg fill="currentColor" height="20" viewBox="0 0 24 24"
                            width="20">
                            <path
                                d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm4.24 16L12 15.45 7.77 18l1.12-4.81-3.73-3.23 4.92-.42L12 5l1.92 4.53 4.92.42-3.73 3.23L16.23 18z" />
                        </svg></div>
                    Mark Attendance
                </div>
                <div class="qa-btn">
                    <div class="qa-icon"><svg fill="currentColor" height="20" viewBox="0 0 24 24"
                            width="20">
                            <path
                                d="M20 6h-2.18c.07-.44.18-.88.18-1.32C18 3.15 16.85 2 15.5 2h-7C7.15 2 6 3.15 6 4.68c0 .44.11.88.18 1.32H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zM20 19H4V8h16v11z" />
                        </svg></div>
                    Create Project
                </div>
                <div class="qa-btn">
                    <div class="qa-icon"><svg fill="currentColor" height="20" viewBox="0 0 24 24"
                            width="20">
                            <path
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                        </svg></div>
                    Generate Report
                </div>
            </div>
        </div>
    </div>

    <!-- ── Attendance Table ── -->
    <div class="card animate-fu d5" style="margin-bottom:20px">
        <div
            style="padding:20px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:4px">
            <div>
                <div class="section-title">Today's Attendance</div>
                <div class="section-sub">Live check-in / check-out overview</div>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
                <button class="btn-ghost" style="font-size:12px;padding:6px 12px">
                    <svg fill="currentColor" height="13" viewBox="0 0 24 24" width="13">
                        <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z" />
                    </svg>
                    Filter
                </button>
                <button class="btn-primary" style="font-size:12px;padding:7px 14px">
                    <svg fill="currentColor" height="13" viewBox="0 0 24 24" width="13">
                        <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                    </svg>
                    Export
                </button>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">JM
                                </div>
                                <div>
                                    <div
                                        style="font-family:'Outfit',sans-serif;font-weight:700;font-size:13.5px;color:var(--text)">
                                        John Mitchell</div>
                                    <div style="font-size:11.5px;color:var(--muted)">EMP-0042</div>
                                </div>
                            </div>
                        </td>
                        <td>Engineering</td>
                        <td>09:02 AM</td>
                        <td>—</td>
                        <td>—</td>
                        <td><span class="status-pill pill-green">● Present</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:linear-gradient(135deg,#7c3aed,#6d28d9)">SC
                                </div>
                                <div>
                                    <div
                                        style="font-family:'Outfit',sans-serif;font-weight:700;font-size:13.5px;color:var(--text)">
                                        Sarah Connor</div>
                                    <div style="font-size:11.5px;color:var(--muted)">EMP-0085</div>
                                </div>
                            </div>
                        </td>
                        <td>Human Resources</td>
                        <td>08:47 AM</td>
                        <td>—</td>
                        <td>—</td>
                        <td><span class="status-pill pill-amber">● On Leave</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:linear-gradient(135deg,#059669,#047857)">MJ
                                </div>
                                <div>
                                    <div
                                        style="font-family:'Outfit',sans-serif;font-weight:700;font-size:13.5px;color:var(--text)">
                                        Mike Johnson</div>
                                    <div style="font-size:11.5px;color:var(--muted)">EMP-0017</div>
                                </div>
                            </div>
                        </td>
                        <td>Design &amp; UX</td>
                        <td>09:15 AM</td>
                        <td>06:30 PM</td>
                        <td>9h 15m</td>
                        <td><span class="status-pill pill-green">● Present</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:linear-gradient(135deg,#d97706,#b45309)">EW
                                </div>
                                <div>
                                    <div
                                        style="font-family:'Outfit',sans-serif;font-weight:700;font-size:13.5px;color:var(--text)">
                                        Emma Williams</div>
                                    <div style="font-size:11.5px;color:var(--muted)">EMP-0103</div>
                                </div>
                            </div>
                        </td>
                        <td>Finance</td>
                        <td>10:30 AM</td>
                        <td>—</td>
                        <td>—</td>
                        <td><span class="status-pill pill-amber">● Late</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:linear-gradient(135deg,#ef4444,#dc2626)">RB
                                </div>
                                <div>
                                    <div
                                        style="font-family:'Outfit',sans-serif;font-weight:700;font-size:13.5px;color:var(--text)">
                                        Ryan Brooks</div>
                                    <div style="font-size:11.5px;color:var(--muted)">EMP-0056</div>
                                </div>
                            </div>
                        </td>
                        <td>Operations</td>
                        <td>—</td>
                        <td>—</td>
                        <td>—</td>
                        <td><span class="status-pill pill-red">● Absent</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:linear-gradient(135deg,#0891b2,#0e7490)">AL
                                </div>
                                <div>
                                    <div
                                        style="font-family:'Outfit',sans-serif;font-weight:700;font-size:13.5px;color:var(--text)">
                                        Anna Lee</div>
                                    <div style="font-size:11.5px;color:var(--muted)">EMP-0071</div>
                                </div>
                            </div>
                        </td>
                        <td>Engineering</td>
                        <td>08:55 AM</td>
                        <td>05:45 PM</td>
                        <td>8h 50m</td>
                        <td><span class="status-pill pill-green">● Present</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">DP
                                </div>
                                <div>
                                    <div
                                        style="font-family:'Outfit',sans-serif;font-weight:700;font-size:13.5px;color:var(--text)">
                                        David Park</div>
                                    <div style="font-size:11.5px;color:var(--muted)">EMP-0038</div>
                                </div>
                            </div>
                        </td>
                        <td>Design &amp; UX</td>
                        <td>09:08 AM</td>
                        <td>—</td>
                        <td>—</td>
                        <td><span class="status-pill pill-blue">● Remote</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            style="padding:12px 20px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);flex-wrap:wrap;gap:8px">
            <span style="font-size:12.5px;color:var(--muted)">Showing 7 of 248 employees</span>
            <div style="display:flex;gap:6px">
                <button class="btn-ghost" style="font-size:12px;padding:5px 12px">← Prev</button>
                <button class="btn-primary" style="font-size:12px;padding:5px 14px">Next →</button>
            </div>
        </div>
    </div>

    <!-- ── Footer ── -->
    <div class="animate-fu d6" style="text-align:center;padding:8px 0 4px">
        <p style="font-size:12px;color:var(--muted2);font-family:'Outfit',sans-serif">
            © 2026 PeopleCore EMS &nbsp;·&nbsp; v5.2.0 &nbsp;·&nbsp; Enterprise Edition
            &nbsp;·&nbsp; <a href="#" style="color:var(--accent);text-decoration:none">Privacy</a>
            &nbsp;·&nbsp; <a href="#" style="color:var(--accent);text-decoration:none">Support</a>
        </p>
    </div>
</x-app-layout>
