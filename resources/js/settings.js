window.updateSummary = function (day) {
    const start = document.querySelector(`[name="${day}_start_time"]`)?.value;
    const end = document.querySelector(`[name="${day}_end_time"]`)?.value;

    const breakEnabled = document.querySelector(`[name="${day}_break_enabled"]`)?.checked;
    const breakStart = document.querySelector(`[name="${day}_break_start"]`)?.value;
    const breakEnd = document.querySelector(`[name="${day}_break_end"]`)?.value;
    const breakTimeInput = document.querySelector(`[name="${day}_break_time"]`);
    const breakTime = breakTimeInput ? parseInt(breakTimeInput.value, 10) : 0;
    const breakMode = document.getElementById('breakModeValue')?.value || 'fixed';

    const summary = document.querySelector(`.js-summary[data-day="${day}"]`);

    if (!summary) return;

    if (!start || !end) {
        summary.textContent = '';
        return;
    }

    const working = timeToMinutes(end) - timeToMinutes(start);

    let text = `Working Time: ${formatMinutes(working)}`;

    if (breakEnabled) {
        let breakMinutes = 0;
        if (breakMode === 'fixed') {
            if (breakStart && breakEnd) {
                breakMinutes = timeToMinutes(breakEnd) - timeToMinutes(breakStart);
            }
        } else {
            breakMinutes = isNaN(breakTime) ? 0 : breakTime;
        }

        if (breakMinutes > 0) {
            const required = working - breakMinutes;
            text += ` • Break: ${formatMinutes(breakMinutes)}`;
            text += ` • Required: ${formatMinutes(required)}`;
        }
    }

    summary.textContent = text;
}

window.timeToMinutes = function (time) {
    if (!time) return null;
    const date = new Date(
        `2000-01-01 ${time}`
    );
    return (
        date.getHours() * 60 +
        date.getMinutes()
    );
}
window.formatMinutes = function (minutes) {
    if (!minutes || minutes < 1) {
        return '';
    }
    const h = Math.floor(minutes / 60);
    const m = minutes % 60;
    if (h && m) {
        return `${h} hr ${m} min`;
    }
    if (h) {
        return `${h} hr`;
    }
    return `${m} min`;
}

function setFlatPickerValues() {
    document.querySelectorAll('.js-time-picker').forEach(input => {
        const value = input.getAttribute('value');
        if (!value || !input._flatpickr) {
            return;
        }
        input._flatpickr.setDate(value, false);
    });

    ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
        updateSummary(day);
    });
}
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-break-toggle').forEach(toggle => {
        toggle.addEventListener('change', () => {
            const breakSection = document.getElementById(toggle.dataset.day + 'Schedule')?.querySelector('.js-break-section');
            if (!breakSection) return;
            if (toggle.checked) {
                breakSection.style.maxHeight = breakSection.scrollHeight + 'px';
                breakSection.style.opacity = '1';
            } else {
                breakSection.style.maxHeight = '0px';
                breakSection.style.opacity = '0';
            }
        });
    });

    document.querySelectorAll('.js-day-toggle').forEach(toggle => {
        toggle.addEventListener('change', () => {
            const target = document.getElementById(toggle.dataset.target);
            if (!target) return;
            if (toggle.checked) {
                target.style.maxHeight = target.scrollHeight + 'px';
                target.style.opacity = '1';
            } else {
                target.style.maxHeight = '0px';
                target.style.opacity = '0';
            }
        });
    });

    document.querySelectorAll('[name$="_break_time"]').forEach(input => {
        input.addEventListener('input', () => {
            const day = input.name.split('_')[0];
            updateSummary(day);
        });
    });

    flatpickr('.js-time-picker', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'h:i K',
        time_24hr: false,
        minuteIncrement: 5,
        allowInput: true,
        onChange(selectedDates, dateStr, instance) {
            const input = instance.input;
            const day = input.name.split('_')[0];
            updateSummary(day);
        }
    });
    setFlatPickerValues();
});