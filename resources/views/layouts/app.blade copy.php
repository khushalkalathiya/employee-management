<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Employee Management Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg: #f1f5fb;
            --card: #ffffff;
            --border: rgba(37, 99, 235, .14);
            --text: #0f172a;
            --muted: #64748b;
            --inp-bg: #f8faff;
            --inp-border: rgba(37, 99, 235, .20);
            --inp-focus: rgba(37, 99, 235, .15);
        }

        .dark {
            --bg: #080c1a;
            --card: #0f1628;
            --border: rgba(59, 130, 246, .18);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --inp-bg: rgba(255, 255, 255, .04);
            --inp-border: rgba(59, 130, 246, .22);
            --inp-focus: rgba(59, 130, 246, .18);
        }

        * {
            box-sizing: border-box
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: .45s;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        .font-display {
            font-family: 'Outfit', sans-serif;
        }

        .bg-gradient-page {
            background:
                radial-gradient(ellipse 70% 55% at 15% 20%, rgba(37, 99, 235, .08) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 85% 80%, rgba(99, 102, 241, .07) 0%, transparent 70%),
                var(--bg);
        }

        .dark .bg-gradient-page {
            background:
                radial-gradient(ellipse 70% 55% at 15% 20%, rgba(30, 58, 138, .35) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 85% 80%, rgba(49, 46, 129, .28) 0%, transparent 70%),
                var(--bg);
        }

        .glass-card {
            background: var(--card);
            border: 1px solid var(--border);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 40px rgba(37, 99, 235, .10), 0 2px 8px rgba(0, 0, 0, .06);
            transition: .35s;
        }

        .dark .glass-card {
            box-shadow: 0 8px 40px rgba(0, 0, 0, .50), 0 2px 8px rgba(0, 0, 0, .30);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .85rem 1rem;
            border-radius: 14px;
            color: var(--muted);
            font-weight: 600;
            transition: .25s;
        }

        .sidebar-link:hover {
            background: rgba(59, 130, 246, .08);
            color: #3b82f6;
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            box-shadow: 0 6px 22px rgba(37, 99, 235, .35);
        }

        .toggle-pill {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem .9rem;
            border-radius: 999px;
            cursor: pointer;
            background: var(--inp-bg);
            border: 1px solid var(--border);
            color: var(--muted);
            transition: .25s;
        }

        .toggle-pill:hover {
            color: #3b82f6;
            border-color: #3b82f6;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .btn-primary {
            padding: .8rem 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #2563eb, #1d4ed8);
            color: #fff;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            box-shadow: 0 4px 20px rgba(37, 99, 235, .40);
            transition: .25s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37, 99, 235, .55);
        }

        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .15);
        }

        ::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 999px;
        }

        .fade-in {
            animation: fade .5s ease;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }
    </style>
</head>

<body class="bg-gradient-page min-h-screen">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside
            class="glass-card fixed inset-y-0 left-0 z-50 w-72 translate-x-[-100%] border-r transition-all duration-300 lg:relative lg:translate-x-0"
            id="sidebar">

            <div class="border-b p-6" style="border-color:var(--border)">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-display text-lg font-bold">Employee</h2>
                        <p class="text-sm" style="color:var(--muted)">Management System</p>
                    </div>
                </div>
            </div>

            <nav class="space-y-2 p-4">
                <a class="sidebar-link active" href="#">🏠 Dashboard</a>
                <a class="sidebar-link" href="#">👥 Employees</a>
                <a class="sidebar-link" href="#">🏢 Departments</a>
                <a class="sidebar-link" href="#">🕒 Attendance</a>
                <a class="sidebar-link" href="#">📅 Leave Management</a>
                <a class="sidebar-link" href="#">💰 Payroll</a>
                <a class="sidebar-link" href="#">📂 Projects</a>
                <a class="sidebar-link" href="#">✅ Tasks</a>
                <a class="sidebar-link" href="#">📊 Reports</a>
                <a class="sidebar-link" href="#">⚙️ Settings</a>
            </nav>
        </aside>

        <div class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden" id="overlay"></div>

        <!-- Main -->
        <div class="flex-1">

            <!-- Header -->
            <header class="glass-card sticky top-0 z-30 flex items-center justify-between border-b px-4 py-4 lg:px-8">
                <div class="flex items-center gap-3">

                    <button class="glass-card rounded-xl p-2 lg:hidden" id="menuBtn">
                        ☰
                    </button>

                    <h1 class="font-display text-xl font-bold">
                        Dashboard
                    </h1>
                </div>

                <div class="flex items-center gap-3">

                    <button class="glass-card relative rounded-xl p-3">
                        🔔
                        <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-red-500"></span>
                    </button>

                    <button class="toggle-pill" onclick="toggleTheme()">
                        🌙
                        <span class="hidden sm:block" id="themeLabel">Dark Mode</span>
                    </button>

                    <div class="relative">
                        <button class="glass-card flex items-center gap-3 rounded-2xl px-3 py-2" id="profileBtn">
                            <img class="h-10 w-10 rounded-xl"
                                src="https://ui-avatars.com/api/?name=Employee&background=2563eb&color=fff">
                            <span class="hidden font-semibold md:block">Employee</span>
                        </button>

                        <div class="glass-card fade-in absolute right-0 mt-3 hidden w-64 rounded-2xl p-2"
                            id="profileMenu">
                            <a class="block rounded-xl px-4 py-3 hover:bg-blue-500/10" href="#">My Profile</a>
                            <a class="block rounded-xl px-4 py-3 hover:bg-blue-500/10" href="#">Account
                                Settings</a>
                            <a class="block rounded-xl px-4 py-3 hover:bg-blue-500/10" href="#">Change
                                Password</a>
                            <hr class="my-2" style="border-color:var(--border)">
                            <a class="block rounded-xl px-4 py-3 text-red-500 hover:bg-red-500/10"
                                href="#">Logout</a>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Content -->
            <main class="space-y-6 p-4 md:p-6 lg:p-8">

                <!-- Welcome -->
                <div class="glass-card rounded-3xl p-6">
                    <span
                        class="inline-flex rounded-full bg-blue-500/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-500">
                        HRMS Dashboard
                    </span>

                    <h2 class="mt-4 font-display text-3xl font-bold">
                        Welcome Back, Employee
                    </h2>

                    <p class="mt-2" style="color:var(--muted)">
                        Today's summary: 124 employees present, 8 pending leave requests and 12 active projects.
                    </p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                    <div class="glass-card stat-card rounded-3xl p-5 transition-all">
                        <div class="flex justify-between">
                            <div>
                                <p style="color:var(--muted)">Total Employees</p>
                                <h3 class="mt-2 text-3xl font-bold">248</h3>
                            </div>
                            <div class="text-3xl">👥</div>
                        </div>
                    </div>

                    <div class="glass-card stat-card rounded-3xl p-5 transition-all">
                        <div class="flex justify-between">
                            <div>
                                <p style="color:var(--muted)">Present Today</p>
                                <h3 class="mt-2 text-3xl font-bold">124</h3>
                            </div>
                            <div class="text-3xl">✅</div>
                        </div>
                    </div>

                    <div class="glass-card stat-card rounded-3xl p-5 transition-all">
                        <div class="flex justify-between">
                            <div>
                                <p style="color:var(--muted)">Pending Leaves</p>
                                <h3 class="mt-2 text-3xl font-bold">8</h3>
                            </div>
                            <div class="text-3xl">📅</div>
                        </div>
                    </div>

                    <div class="glass-card stat-card rounded-3xl p-5 transition-all">
                        <div class="flex justify-between">
                            <div>
                                <p style="color:var(--muted)">Active Projects</p>
                                <h3 class="mt-2 text-3xl font-bold">12</h3>
                            </div>
                            <div class="text-3xl">📂</div>
                        </div>
                    </div>

                </div>

                <!-- Grid -->
                <div class="grid gap-6 lg:grid-cols-3">

                    <!-- Activities -->
                    <div class="glass-card rounded-3xl p-6 lg:col-span-1">
                        <h3 class="mb-6 font-display text-xl font-bold">Recent Activities</h3>

                        <div class="space-y-5">

                            <div class="flex gap-4">
                                <div class="timeline-dot"></div>
                                <div>
                                    <p class="font-semibold">John checked in</p>
                                    <span class="text-sm" style="color:var(--muted)">10 minutes ago</span>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="timeline-dot"></div>
                                <div>
                                    <p class="font-semibold">Sarah submitted leave request</p>
                                    <span class="text-sm" style="color:var(--muted)">30 minutes ago</span>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="timeline-dot"></div>
                                <div>
                                    <p class="font-semibold">Mike completed project task</p>
                                    <span class="text-sm" style="color:var(--muted)">1 hour ago</span>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="timeline-dot"></div>
                                <div>
                                    <p class="font-semibold">Emma updated profile</p>
                                    <span class="text-sm" style="color:var(--muted)">2 hours ago</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="glass-card rounded-3xl p-6 lg:col-span-2">
                        <h3 class="mb-6 font-display text-xl font-bold">Quick Actions</h3>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <button class="btn-primary">Add Employee</button>
                            <button class="btn-primary">Mark Attendance</button>
                            <button class="btn-primary">Create Project</button>
                            <button class="btn-primary">Generate Report</button>
                        </div>
                    </div>

                </div>

                <!-- Attendance -->
                <div class="glass-card rounded-3xl p-6">
                    <div class="mb-5 flex items-center justify-between">
                        <h3 class="font-display text-xl font-bold">
                            Attendance Overview
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[800px]">
                            <thead>
                                <tr class="text-left">
                                    <th class="pb-4">Employee</th>
                                    <th class="pb-4">Department</th>
                                    <th class="pb-4">Check In</th>
                                    <th class="pb-4">Check Out</th>
                                    <th class="pb-4">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y" style="border-color:var(--border)">

                                <tr>
                                    <td class="py-4">John Smith</td>
                                    <td>Development</td>
                                    <td>09:01 AM</td>
                                    <td>06:00 PM</td>
                                    <td><span
                                            class="rounded-full bg-green-500/10 px-3 py-1 text-green-500">Present</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="py-4">Sarah Wilson</td>
                                    <td>HR</td>
                                    <td>09:15 AM</td>
                                    <td>06:10 PM</td>
                                    <td><span
                                            class="rounded-full bg-green-500/10 px-3 py-1 text-green-500">Present</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="py-4">Mike Johnson</td>
                                    <td>Projects</td>
                                    <td>08:55 AM</td>
                                    <td>06:05 PM</td>
                                    <td><span
                                            class="rounded-full bg-yellow-500/10 px-3 py-1 text-yellow-500">Late</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="py-4">Emma Davis</td>
                                    <td>Finance</td>
                                    <td>09:05 AM</td>
                                    <td>06:00 PM</td>
                                    <td><span class="rounded-full bg-blue-500/10 px-3 py-1 text-blue-500">Remote</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>

    <script>
        const html = document.documentElement;

        const savedTheme = localStorage.getItem("theme");

        if (savedTheme === "dark") {
            html.classList.add("dark");
        } else {
            html.classList.remove("dark");
        }

        function toggleTheme() {
            html.classList.toggle("dark");

            const dark = html.classList.contains("dark");

            localStorage.setItem("theme", dark ? "dark" : "light");

            document.getElementById("themeLabel").textContent =
                dark ? "Light Mode" : "Dark Mode";
        }

        const menuBtn = document.getElementById("menuBtn");
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");

        menuBtn.addEventListener("click", () => {
            sidebar.classList.remove("-translate-x-full");
            sidebar.style.transform = "translateX(0)";
            overlay.classList.remove("hidden");
        });

        overlay.addEventListener("click", () => {
            sidebar.style.transform = "translateX(-100%)";
            overlay.classList.add("hidden");
        });

        const profileBtn = document.getElementById("profileBtn");
        const profileMenu = document.getElementById("profileMenu");

        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle("hidden");
        });

        document.addEventListener("click", (e) => {
            if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                profileMenu.classList.add("hidden");
            }
        });
    </script>

</body>

</html>
