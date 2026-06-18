<div class="sb-section-label">Profile Information</div>
<!-- Basic Information -->
<a class="nav-item {{ request()->routeIs('profile.basic-information.*') ? 'active' : '' }}"
    href="{{ route('profile.basic-information.edit') }}">
    <span class="nav-icon">
        <svg fill="none" height="18" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="18">
            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </span>
    <span class="nav-label">Basic Information</span>
    <span class="nav-tooltip">Basic Information</span>
</a>

<!-- Personal Details -->
<a class="nav-item {{ request()->routeIs('profile.personal-details.*') ? 'active' : '' }}"
    href="{{ route('profile.personal-details.edit') }}">
    <span class="nav-icon">
        <svg fill="none" height="18" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="18">
            <path
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <span class="nav-label">Personal Details</span>
    <span class="nav-tooltip">Personal Details</span>
</a>

<!-- Family Information -->
<a class="nav-item {{ request()->routeIs('profile.family-information.*') ? 'active' : '' }}"
    href="{{ route('profile.family-information.edit') }}">
    <span class="nav-icon">
        <svg fill="none" height="18" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="18">
            <path
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <span class="nav-label">Family Information</span>
    <span class="nav-tooltip">Family Information</span>
</a>

<!-- Documents -->
<a class="nav-item {{ request()->routeIs('profile.documents.*') ? 'active' : '' }}"
    href="{{ route('profile.documents.edit') }}">
    <span class="nav-icon">
        <svg fill="none" height="18" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="18">
            <path
                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <span class="nav-label">Documents</span>
    <span class="nav-tooltip">Documents</span>
</a>

<!-- Education -->
<a class="nav-item {{ request()->routeIs('profile.education.*') ? 'active' : '' }}"
    href="{{ route('profile.education.edit') }}">
    <span class="nav-icon">
        <svg fill="none" height="18" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="18">
            <path d="M12 14l9-5-9-5-9 5 9 5z" stroke-linecap="round" stroke-linejoin="round" />
            <path
                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <span class="nav-label">Education</span>
    <span class="nav-tooltip">Education</span>
</a>

<!-- Experience -->
<a class="nav-item {{ request()->routeIs('profile.experience.*') ? 'active' : '' }}"
    href="{{ route('profile.experience.edit') }}">
    <span class="nav-icon">
        <svg fill="none" height="18" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="18">
            <path
                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <span class="nav-label">Experience</span>
    <span class="nav-tooltip">Experience</span>
</a>

<!-- Bank Account -->
<a class="nav-item {{ request()->routeIs('profile.bank-account.*') ? 'active' : '' }}"
    href="{{ route('profile.bank-account.edit') }}">
    <span class="nav-icon">
        <svg fill="none" height="18" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" width="18">
            <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>
    <span class="nav-label">Bank Account</span>
    <span class="nav-tooltip">Bank Account</span>
</a>
