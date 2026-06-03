<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-page min-h-screen flex flex-col">
        <header class="relative z-20 flex items-center justify-between px-6 py-4 max-w-7xl mx-auto w-full">
            <div class="flex items-center gap-2.5 animate-fadeUp">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);box-shadow:0 4px 16px rgba(37,99,235,0.40);">
                    <svg width="18" height="18" fill="white" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 6h-2.18c.07-.44.18-.88.18-1.32C18 3.15 16.85 2 15.5 2h-7C7.15 2 6 3.15 6 4.68c0 .44.11.88.18 1.32H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-9.32-1.32c0-.37.3-.68.68-.68h1.28c.37 0 .68.3.68.68C13.32 5.03 13.03 5 13 5h-1.28c-.03 0-.61.03-.04-1.32zM8 4.68C8 3.75 8.75 3 9.68 3h4.64C15.25 3 16 3.75 16 4.68c0 .44-.18.86-.47 1.17C15.26 6.18 14.72 6.4 14 6.4H10c-.72 0-1.26-.22-1.53-.55C8.18 5.54 8 5.12 8 4.68zM20 19H4V8h16v11z"/>
                    </svg>
                </div>
                <span class="font-display font-bold text-base" style="color:var(--text);font-family:'Outfit',sans-serif;">
                    People<span class="text-blue-500">Core</span>
                </span>
            </div>
            <button class="toggle-pill animate-fadeUp" onclick="toggleDarkMode()" id="themeBtn" aria-label="Toggle dark mode" title="Toggle dark/light mode">
            <svg id="iconSun" width="14" height="14" fill="currentColor" viewBox="0 0 24 24" style="display:none;">
                <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .38-.39.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.38.39-1.02 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.38 1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.39 1.03.39 1.41 0l1.06-1.06z"/>
            </svg>
            <svg id="iconMoon" width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/>
            </svg>
            <span id="themeLabel" class="hidden sm:inline">Dark Mode</span>
            </button>
        </header>
        {{ $slot }}
    </body>
</html>
