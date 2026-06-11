<div>
    <div style="margin-bottom:20px">

        <!-- Header -->
        <div
            style="padding:20px 20px 0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">

            <div>
                <div class="section-title">
                    My Attendance
                </div>
                <div class="section-sub">
                    Track your attendance, work hours, breaks and overtime
                </div>
            </div>

            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <input class="input" type="month" value="2026-06">

                <select class="input">
                    <option>All Status</option>
                    <option>Present</option>
                    <option>Absent</option>
                    <option>Leave</option>
                    <option>Holiday</option>
                    <option>Week Off</option>
                </select>
            </div>
        </div>

        <!-- Today Card -->
        <div style="padding:20px">
            <div class="card">
                <div
                    style="padding:18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">

                    <div>
                        <div style="font-size:13px;color:var(--muted)">
                            Today's Attendance
                        </div>

                        <div style="font-size:22px;font-weight:700;color:var(--text);margin-top:4px">
                            Checked In
                        </div>

                        <div style="font-size:13px;color:var(--muted);margin-top:6px">
                            Thursday, 11 June 2026
                        </div>
                    </div>

                    <div style="display:flex;gap:30px;flex-wrap:wrap">

                        <div>
                            <div style="font-size:12px;color:var(--muted)">
                                Check In
                            </div>
                            <div style="font-weight:600">
                                09:12 AM
                            </div>
                        </div>

                        <div>
                            <div style="font-size:12px;color:var(--muted)">
                                Working
                            </div>
                            <div style="font-weight:600">
                                07h 18m
                            </div>
                        </div>

                        <div>
                            <div style="font-size:12px;color:var(--muted)">
                                Break
                            </div>
                            <div style="font-weight:600">
                                45m
                            </div>
                        </div>
                    </div>

                    <button class="btn-primary">
                        Check Out
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div style="padding:0 20px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px">

            <div class="card">
                <div style="padding:18px">
                    <div style="font-size:13px;color:var(--muted)">
                        Present Days
                    </div>
                    <div style="font-size:30px;font-weight:700;margin-top:6px">
                        21
                    </div>
                    <div style="font-size:12px;color:#16a34a">
                        +2 from last month
                    </div>
                </div>
            </div>

            <div class="card">
                <div style="padding:18px">
                    <div style="font-size:13px;color:var(--muted)">
                        Absent Days
                    </div>
                    <div style="font-size:30px;font-weight:700;margin-top:6px">
                        1
                    </div>
                    <div style="font-size:12px;color:#ef4444">
                        This month
                    </div>
                </div>
            </div>

            <div class="card">
                <div style="padding:18px">
                    <div style="font-size:13px;color:var(--muted)">
                        Leave Days
                    </div>
                    <div style="font-size:30px;font-weight:700;margin-top:6px">
                        2
                    </div>
                    <div style="font-size:12px;color:#f59e0b">
                        Approved
                    </div>
                </div>
            </div>

            <div class="card">
                <div style="padding:18px">
                    <div style="font-size:13px;color:var(--muted)">
                        Work Hours
                    </div>
                    <div style="font-size:30px;font-weight:700;margin-top:6px">
                        176h
                    </div>
                    <div style="font-size:12px;color:var(--muted)">
                        Total this month
                    </div>
                </div>
            </div>

            <div class="card">
                <div style="padding:18px">
                    <div style="font-size:13px;color:var(--muted)">
                        Overtime
                    </div>
                    <div style="font-size:30px;font-weight:700;margin-top:6px">
                        08h
                    </div>
                    <div style="font-size:12px;color:#2563eb">
                        Extra worked
                    </div>
                </div>
            </div>

        </div>

        <!-- Attendance Table -->
        <div class="card" style="overflow-x:auto;margin:0 20px">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Work Hours</th>
                        <th>Break</th>
                        <th>Overtime</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>11 Jun 2026</td>
                        <td>09:12 AM</td>
                        <td>06:08 PM</td>
                        <td>08h 11m</td>
                        <td>45m</td>
                        <td>11m</td>
                        <td>
                            <span class="badge badge-success">Present</span>
                        </td>
                    </tr>

                    <tr>
                        <td>10 Jun 2026</td>
                        <td>09:18 AM</td>
                        <td>06:05 PM</td>
                        <td>08h 02m</td>
                        <td>45m</td>
                        <td>02m</td>
                        <td>
                            <span class="badge badge-success">Present</span>
                        </td>
                    </tr>

                    <tr>
                        <td>09 Jun 2026</td>
                        <td>09:25 AM</td>
                        <td>06:00 PM</td>
                        <td>07h 50m</td>
                        <td>45m</td>
                        <td>-</td>
                        <td>
                            <span class="badge badge-warning">Late</span>
                        </td>
                    </tr>

                    <tr>
                        <td>08 Jun 2026</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                            <span class="badge badge-danger">Absent</span>
                        </td>
                    </tr>

                    <tr>
                        <td>07 Jun 2026</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                            <span class="badge badge-info">Week Off</span>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</div>
