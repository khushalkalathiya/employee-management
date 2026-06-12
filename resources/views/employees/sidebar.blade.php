@can('employee.edit')
    <a class="nav-item {{ request()->routeIs('employees.basic-information.*') ? 'active' : '' }}"
        href="{{ route('employees.basic-information.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Basic Information</span>
        <span class="nav-tooltip">Basic Information</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.employment-details.*') ? 'active' : '' }}"
        href="{{ route('employees.employment-details.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Employment Details</span>
        <span class="nav-tooltip">Employment Details</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.address-information.*') ? 'active' : '' }}"
        href="{{ route('employees.address-information.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Address Information</span>
        <span class="nav-tooltip">Address Information</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.family-information.*') ? 'active' : '' }}"
        href="{{ route('employees.family-information.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Family Information</span>
        <span class="nav-tooltip">Family Information</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.bank-details.*') ? 'active' : '' }}"
        href="{{ route('employees.bank-details.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Bank Details</span>
        <span class="nav-tooltip">Bank Details</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.education.*') ? 'active' : '' }}"
        href="{{ route('employees.education.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Education</span>
        <span class="nav-tooltip">Education</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.experience.*') ? 'active' : '' }}"
        href="{{ route('employees.experience.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Experience</span>
        <span class="nav-tooltip">Experience</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.documents.*') ? 'active' : '' }}"
        href="{{ route('employees.documents.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Documents</span>
        <span class="nav-tooltip">Documents</span>
    </a>
    <a class="nav-item {{ request()->routeIs('employees.assets.*') ? 'active' : '' }}"
        href="{{ route('employees.assets.edit', $employee->id) }}">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Assets</span>
        <span class="nav-tooltip">Assets</span>
    </a>
@endcan
