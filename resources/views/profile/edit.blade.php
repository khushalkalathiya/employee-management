<x-app-layout>
    <div class="flex items-center justify-between gap-3 p-5">
        <div>
            <h1 class="section-title">
                Profile
            </h1>

            <p class="section-sub">
                Update profile information and account settings
            </p>
        </div>
        <div>
            <a class="btn-ghost no-underline" href="{{ route('employees.index') }}">
                Back
            </a>
        </div>
    </div>
    <div class="settings-layout">
        <div class="settings-mobile-header">
            <button class="settings-menu-btn" onclick="openSubSidebarMenu()" type="button">
                <svg fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                    <line x1="3" x2="21" y1="6" y2="6"></line>
                    <line x1="3" x2="21" y1="12" y2="12"></line>
                    <line x1="3" x2="21" y1="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <aside class="sub-side-menu">
            @include('profile.sidebar')
        </aside>

        <!-- Content -->
        <div class="settings-content">
            @if ($section === 'basic-information')
                @include('employees.basic-information')
            @elseif ($section === 'personal-details')
                @include('employees.personal-details')
            @elseif ($section === 'family-information')
                @include('employees.family-information')
            @elseif ($section === 'bank-account')
                @include('employees.bank-account')
            @elseif ($section === 'documents')
                @include('employees.employee-documents')
            @elseif ($section === 'education')
                @include('employees.employee-education')
            @elseif ($section === 'experience')
                @include('employees.employee-experience')
            @elseif ($section === 'assets')
                @include('employees.employee-assets')
            @else
                <div class="flex justify-center py-6">
                    <p class="text-gray-500">Select a section to edit</p>
                </div>
            @endif
        </div>
    </div>
    <div class="settings-overlay" id="settingsOverlay" onclick="closeSubSidebarMenu()"></div>
    <div class="settings-mobile-menu" id="settingsMobileMenu">
        <div class="settings-mobile-menu-container">
            <button class="settings-mobile-close" onclick="closeSubSidebarMenu()" type="button">
                ✕
            </button>
            <div class="settings-mobile-scroll">
                @include('employees.sidebar')
            </div>
        </div>
    </div>
</x-app-layout>
