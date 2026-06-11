@can('settings.general.view')
    <a class="nav-item {{ request()->routeIs('settings.general.*') ? 'active' : '' }}"
        href="{{ route('settings.general.index') }}" onclick="setActive(this);return false">
        <span class="nav-icon">
            <svg fill="currentColor" height="18" viewBox="0 0 24 24" width="18">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
        </span>
        <span class="nav-label">Work Schedule</span>
        <span class="nav-tooltip">Work Schedule</span>
    </a>
@endcan
