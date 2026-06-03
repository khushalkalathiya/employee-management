const html = document.documentElement;

const savedTheme = localStorage.getItem('theme');
let isDark = savedTheme === 'dark';

if (isDark) {
    html.classList.add('dark');
    html.classList.remove('light');
} else {
    html.classList.remove('dark');
    html.classList.add('light');
}

window.toggleTheme = function () {
    isDark = !isDark;

    const label = document.getElementById('themeLabel');
    const sun = document.getElementById('iconSun');
    const moon = document.getElementById('iconMoon');

    if (isDark) {
        html.classList.add('dark');
        html.classList.remove('light');

        if (label) label.textContent = 'Light Mode';
        if (sun) sun.style.display = 'block';
        if (moon) moon.style.display = 'none';

        localStorage.setItem('theme', 'dark');
    } else {
        html.classList.remove('dark');
        html.classList.add('light');

        if (label) label.textContent = 'Dark Mode';
        if (sun) sun.style.display = 'none';
        if (moon) moon.style.display = 'block';

        localStorage.setItem('theme', 'light');
    }
};

window.togglePassword = function () {
    const field = document.getElementById('pwInput');
    const show = document.getElementById('eyeShow');
    const hide = document.getElementById('eyeHide');
    const btn = document.getElementById('eyeBtn');

    if (!field) return;

    if (field.type === 'password') {
        field.type = 'text';

        if (show) show.style.display = 'none';
        if (hide) hide.style.display = 'block';
        if (btn) btn.setAttribute('aria-label', 'Hide password');
    } else {
        field.type = 'password';

        if (show) show.style.display = 'block';
        if (hide) hide.style.display = 'none';
        if (btn) btn.setAttribute('aria-label', 'Show password');
    }
};
