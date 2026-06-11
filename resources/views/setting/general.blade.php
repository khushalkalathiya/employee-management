<x-app-layout>
    <div class="settings-layout">
        <div class="settings-mobile-header">
            <button class="settings-menu-btn" onclick="openSubSidebarMenu()" type="button">
                <svg fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                    <line x1="3" x2="21" y1="6" y2="6"></line>
                    <line x1="3" x2="21" y1="12" y2="12"></line>
                    <line x1="3" x2="21" y1="18" y2="18"></line>
                </svg>
            </button>
            <h2 class="settings-page-title">
                Settings Menu
            </h2>
        </div>

        <aside class="sub-side-menu">
            @include('setting.sidebar')
        </aside>

        <!-- Content -->
        <div class="settings-content">
        </div>
    </div>
    <div class="settings-overlay" id="settingsOverlay" onclick="closeSubSidebarMenu()"></div>
    <div class="settings-mobile-menu" id="settingsMobileMenu">
        <div class="settings-mobile-menu-container">
            <button class="settings-mobile-close" onclick="closeSubSidebarMenu()" type="button">
                ✕
            </button>
            <div class="settings-mobile-scroll">
                @include('setting.sidebar')
            </div>
        </div>
    </div>
</x-app-layout>
