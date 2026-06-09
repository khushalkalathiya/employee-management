window.showToast = function (message, type = 'success') {

    if (!window.toastContainer) {

        window.toastContainer = document.createElement('div');

        window.toastContainer.className =
            'fixed top-5 right-5 z-[99999] flex flex-col gap-3';

        document.body.appendChild(window.toastContainer);
    }

    const icons = {
        success: `
            <svg class="h-5 w-5 text-green-500"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24">
                <path
                    d="M5 13l4 4L19 7"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>
            </svg>
        `,
        error: `
            <svg class="h-5 w-5 text-red-500"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24">
                <path
                    d="M6 18L18 6M6 6l12 12"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>
            </svg>
        `,
        warning: `
            <svg class="h-5 w-5 text-yellow-500"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24">
                <path
                    d="M12 9v4m0 4h.01"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>
                <path
                    d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>
            </svg>
        `,
        info: `
            <svg class="h-5 w-5 text-blue-500"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24">
                <path
                    d="M13 16h-1v-4h-1m1-4h.01"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>
                <circle
                    cx="12"
                    cy="12"
                    r="10"/>
            </svg>
        `,
    };

    const toast = document.createElement('div');

    toast.className = `
        flex min-w-[340px] max-w-md items-start gap-3
        rounded-2xl border
        border-gray-200 dark:border-gray-700
        bg-white dark:bg-gray-900
        px-4 py-3
        text-gray-800 dark:text-gray-100
        shadow-xl
        transition-all duration-300
        translate-x-10 opacity-0
    `;

    toast.innerHTML = `
        <div class="mt-0.5">
            ${icons[type] || icons.info}
        </div>

        <div class="flex-1">
            <p class="text-sm font-medium">
                ${message}
            </p>
        </div>

        <button
            type="button"
            class="
                text-gray-400
                transition
                hover:text-gray-700
                dark:hover:text-white
            ">
            ✕
        </button>
    `;

    window.toastContainer.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove(
            'translate-x-10',
            'opacity-0'
        );
    });

    const removeToast = () => {

        toast.classList.add(
            'translate-x-10',
            'opacity-0'
        );

        setTimeout(() => {

            toast.remove();

            if (
                window.toastContainer &&
                !window.toastContainer.children.length
            ) {
                window.toastContainer.remove();
                window.toastContainer = null;
            }

        }, 300);
    };

    toast
        .querySelector('button')
        .addEventListener('click', removeToast);

    setTimeout(removeToast, 5000);
};

window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
window.currentConfirmUrl = null;
window.openConfirmModal = function ({title = 'Delete Record', description = 'Are you sure you would like to do this?', url = null,} = {}) {
    const modal = document.getElementById('deleteItemConfirmModel');
    if (!modal) return;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = description;
    window.currentConfirmUrl = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.closeConfirmModal = function () {
    const modal = document.getElementById('deleteItemConfirmModel');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    window.currentConfirmUrl = null;
};

window.confirmDelete = async function () {
    const button = document.getElementById('confirmActionBtn');
    if (!window.currentConfirmUrl || !button) {
        return;
    }
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `
        <span class="flex items-center justify-center gap-2">
            <svg class="h-4 w-4 animate-spin"
                viewBox="0 0 24 24"
                fill="none">
                <circle
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="3"
                    opacity=".25"
                />
                <path
                    d="M22 12a10 10 0 00-10-10"
                    stroke="currentColor"
                    stroke-width="3"
                />
            </svg>
            Deleting...
        </span>
    `;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const response = await fetch(window.currentConfirmUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        });
        const result = await response.json();
        closeConfirmModal();
        Livewire.dispatch('refresh-table')
        if(result.success) {
            showToast(
                result.message || 'Deleted successfully',
                'success'
            );
        } else {
            console.log(result.message);
            showToast(
                result.message || 'Something went wrong',
                'error'
            );
        }
    } catch (error) {
        console.log(error);
        showToast(
            'Something went wrong',
            'error'
        );
    } finally {
        button.disabled = false;
        button.innerHTML = originalText;
    }
};

document.addEventListener('click', (e) => {

    // Profile dropdown close
    if (
        !e.target.closest('#profileBtn') &&
        !e.target.closest('#profileDropdown') &&
        ddOpen
    ) {
        ddOpen = false;
        document.getElementById('profileDropdown').classList.remove('open');
    }

    // Delete confirmation modal
    const deleteBtn = e.target.closest('.js-delete-confirm');
    if (deleteBtn) {
        openConfirmModal({
            title: deleteBtn.dataset.title,
            description: deleteBtn.dataset.description,
            url: deleteBtn.dataset.url,
        });
        return;
    }

    // Close button for any modal
    const closeBtn = e.target.closest('.modal-close-btn');
    if (closeBtn) {
        const modal = closeBtn.closest('.modal');
        if (modal) {
            modalHelper.close(modal.id);
        }
        return;
    }

    // Delete modal backdrop click
    const deleteModal = document.getElementById('deleteItemConfirmModel');
    if (
        deleteModal &&
        e.target === deleteModal &&
        deleteModal.classList.contains('flex')
    ) {
        closeConfirmModal();
        return;
    }

    // Generic modal backdrop click
    const allModal = e.target.closest('.modal');
    if (
        allModal &&
        e.target === allModal &&
        allModal.classList.contains('flex')
    ) {
        modalHelper.close(allModal.id);
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeConfirmModal();
        document.querySelectorAll('.modal.flex').forEach(modal => {
            modalHelper.close(modal.id);
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const confirmBtn = document.getElementById('confirmActionBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', confirmDelete);
    }
});


const html = document.documentElement;
const savedTheme = localStorage.getItem('theme');

let isDark = savedTheme === 'dark';

html.classList.toggle('dark', isDark);
html.classList.toggle('light', !isDark);

window.toggleTheme = function () {

    isDark = !isDark;

    html.classList.toggle('dark', isDark);
    html.classList.toggle('light', !isDark);

    const sun = document.getElementById('iconSun');
    const moon = document.getElementById('iconMoon');

    if (sun) {
        sun.style.display = isDark
            ? 'block'
            : 'none';
    }

    if (moon) {
        moon.style.display = isDark
            ? 'none'
            : 'block';
    }

    localStorage.setItem(
        'theme',
        isDark ? 'dark' : 'light'
    );
};

window.togglePassword = function (button) {

    const wrapper = button.closest('.password-wrapper');

    if (!wrapper) return;

    const input = wrapper.querySelector('.password-input');
    const showIcon = wrapper.querySelector('.eye-show');
    const hideIcon = wrapper.querySelector('.eye-hide');

    if (!input) return;

    const isPassword = input.type === 'password';

    input.type = isPassword ? 'text' : 'password';

    if (showIcon) {
        showIcon.style.display = isPassword
            ? 'none'
            : 'block';
    }

    if (hideIcon) {
        hideIcon.style.display = isPassword
            ? 'block'
            : 'none';
    }
};

let sidebarCollapsed = false;

window.toggleSidebar = function () {
    sidebarCollapsed = !sidebarCollapsed;
    document.getElementById('sidebar').classList.toggle('collapsed', sidebarCollapsed);
}

window.openMobileSidebar = function () {
    document.getElementById('sidebar').classList.add('mobile-open');
    document.getElementById('sbOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

window.closeMobileSidebar = function () {
    document.getElementById('sidebar').classList.remove('mobile-open');
    document.getElementById('sbOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

document.querySelectorAll('.image-upload').forEach((wrapper) => {
    const input = wrapper.querySelector('.image-input');
    const box = wrapper.querySelector('.upload-box');
    const preview = wrapper.querySelector('.preview');
    const placeholder = wrapper.querySelector('.placeholder');
    const removeBtn = wrapper.querySelector('.remove-btn');

    box.addEventListener('click', () => {
        input.click();
    });

    input.addEventListener('change', () => {

        const file = input.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = (e) => {

            preview.src = e.target.result;

            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');

            removeBtn.classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    });

    removeBtn.addEventListener('click', (e) => {

        e.stopPropagation();

        input.value = '';

        preview.src = '';
        preview.classList.add('hidden');

        placeholder.classList.remove('hidden');

        removeBtn.classList.add('hidden');
    });
});

window.closeFlash = function (button) {
    const flash = button.closest('.flash-message');

    if (!flash) return;

    flash.classList.add('opacity-0', '-translate-y-2');

    setTimeout(() => {
        flash.remove();
    }, 300);
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-message').forEach((flash) => {
        setTimeout(() => {
            flash.classList.add('opacity-0', '-translate-y-2');

            setTimeout(() => {
                flash.remove();
            }, 300);
        }, 5000);
    });
});

let ddOpen = false;
window.toggleDropdown = function () {
    ddOpen = !ddOpen;
    const dd = document.getElementById('profileDropdown');
    dd.classList.toggle('open', ddOpen);
    document.getElementById('profileBtn').setAttribute('aria-expanded', String(ddOpen));
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tom-select').forEach((element) => {
        if (element.tomselect) {
            return;
        }
        const placeholder = element.dataset.placeholder || element.querySelector('option[value=""]')?.textContent?.trim() || 'Select option';
        const ts = new TomSelect(element, {
            create: false,
            allowEmptyOption: true,
            placeholder,
        });

        if (element.value) {
            ts.setValue(element.value, true);
        } else {
            ts.clear(true);
        }

    });
});


window.modalHelper = {
    open(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('bg-black/0');
            modal.classList.add('bg-black/60');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    },

    close(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        modal.classList.remove('bg-black/60');
        modal.classList.add('bg-black/0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
};










// Dashborad
/* ── Date ── */
const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const d = new Date();
const todayDate = document.getElementById('todayDate');
if (todayDate) {
    todayDate.textContent =
        `${days[d.getDay()]}, ${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
}

/* ── Mini bar chart ── */
const data = [68, 82, 74, 91, 78, 55, 88];
const colors = ['#3b82f6', '#7c3aed', '#059669', '#d97706', '#3b82f6', '#ef4444', '#0891b2'];
const chart = document.getElementById('miniChart');
if (chart) {
    const max = Math.max(...data);
    data.forEach((v, i) => {
        const bar = document.createElement('div');
        bar.className = 'bar';
        bar.style.cssText =
            `height:${Math.round((v / max) * 50)}px;
             width:12px;
             background:${colors[i]};
             opacity:.7;
             transition:height .4s ${i * .06}s, opacity .2s`;
        bar.title = `${v}%`;
        bar.onmouseover = () => bar.style.opacity = '1';
        bar.onmouseout = () => bar.style.opacity = '.7';
        chart.appendChild(bar);
    });
}