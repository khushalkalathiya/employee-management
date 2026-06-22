function renderAttendance() {

    const data = attendanceData;

    document.getElementById(
        'workingTimer'
    ).dataset.seconds =
        data.working_seconds || 0;

    document.getElementById(
        'breakTimer'
    ).dataset.seconds =
        data.break_seconds || 0;

    if (!data.is_clocked_in) {

        document.getElementById(
            'attendanceStatus'
        ).innerHTML =
            'Not Working';

        document.getElementById(
            'clockBtn'
        ).innerHTML =
            'Clock In';

        document.getElementById(
            'clockBtn'
        ).dataset.action =
            '/attendance/check-in';

        document.getElementById(
            'clockBtn'
        ).className =
            'rounded-xl bg-indigo-600 px-5 py-4 font-semibold text-white';

        document.getElementById(
            'breakBtn'
        ).style.display =
            'none';

    } else {

        document.getElementById(
            'clockBtn'
        ).innerHTML =
            'Clock Out';

        document.getElementById(
            'clockBtn'
        ).dataset.action =
            '/attendance/check-out';

        document.getElementById(
            'clockBtn'
        ).className =
            'rounded-xl bg-red-600 px-5 py-4 font-semibold text-white';

        document.getElementById(
            'breakBtn'
        ).style.display =
            'block';

        if (data.is_on_break) {

            document.getElementById(
                'attendanceStatus'
            ).innerHTML =
                'Break';

            document.getElementById(
                'breakBtn'
            ).innerHTML =
                'Break Out';

            document.getElementById(
                'clockBtn'
            ).disabled =
                true;

        } else {

            document.getElementById(
                'attendanceStatus'
            ).innerHTML =
                'Working';

            document.getElementById(
                'breakBtn'
            ).innerHTML =
                'Break In';

            document.getElementById(
                'clockBtn'
            ).disabled =
                false;
        }
    }

    renderLogs();

    startAttendanceTimers();
}
function renderLogs() {

    let html = '';

    attendanceData.logs.forEach(log => {

        html += `
            <div class="flex items-center justify-between text-sm">

                <span>
                    ${log.type}
                </span>

                <span>
                    ${log.display_time}
                </span>

            </div>
        `;
    });

    document.getElementById(
        'attendanceLogs'
    ).innerHTML = html;
}
function startAttendanceTimers() {

    let workingSeconds =
        attendanceData.working_seconds;

    let breakSeconds =
        attendanceData.break_seconds;

    setInterval(() => {

        if (
            attendanceData.is_clocked_in &&
            !attendanceData.is_on_break
        ) {

            workingSeconds++;

            document.getElementById(
                'workingTimer'
            ).innerHTML =
                formatTime(
                    workingSeconds
                );
        }

        if (
            attendanceData.is_on_break
        ) {

            breakSeconds++;

            document.getElementById(
                'breakTimer'
            ).innerHTML =
                formatTime(
                    breakSeconds
                );
        }

    }, 1000);
}

window.attendanceData = null;
window.loadAttendanceStatus = async function () {

    try {
        const attendanceContent = document.getElementById('attendanceContent');
        const callURL = attendanceContent.dataset.url;
        const response = await fetch(
            callURL,
            {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,
                    'Accept': 'application/json',
                }
            }
        );
        console.log(response);

        const result = await response.json();

        if (!result.success) {

            toastr.error('Unable to load.');

            return;
        }

        attendanceData = result.data;
        document.getElementById('attendanceSkeleton').classList.add('hidden');
        attendanceContent.classList.remove('hidden');

        renderAttendance();

    } catch (error) {

        console.error(error);

        toastr.error('Unable to load attendance.');

    }
};

window.clockInAttendance = async function (button) {
    try {
        button.disabled = true;
        const response = await fetch(
            button.dataset.action,
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,
                    'Accept': 'application/json',
                }
            }
        );

        const result = await response.json();
        if (response.ok) {
            toastr.success(result.message);
            return;
        }

        toastr.error(result.message ?? 'Something went wrong.');
    } catch (error) {
        console.error(error);
        toastr.error('Unable to clock in.');
    } finally {
        button.disabled = false;
    }
};
