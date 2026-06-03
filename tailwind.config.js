import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', 'sans-serif'],
                body: ['Manrope', 'sans-serif'],
            },

            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },

                surface: {
                    light: '#f1f5fb',
                    card: '#ffffff',
                    dark: '#0b0f1e',
                    'dark-card': '#111827',
                },
            },

            boxShadow: {
                'glass-light':
                    '0 8px 40px rgba(37,99,235,0.10), 0 2px 8px rgba(0,0,0,0.06)',

                'glass-dark':
                    '0 8px 40px rgba(0,0,0,0.50), 0 2px 8px rgba(0,0,0,0.30)',

                btn:
                    '0 4px 20px rgba(37,99,235,0.40)',

                'btn-hover':
                    '0 8px 32px rgba(37,99,235,0.55)',
            },

            keyframes: {
                fadeUp: {
                    from: {
                        opacity: '0',
                        transform: 'translateY(22px)',
                    },
                    to: {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },

                scaleIn: {
                    from: {
                        opacity: '0',
                        transform: 'scale(0.95)',
                    },
                    to: {
                        opacity: '1',
                        transform: 'scale(1)',
                    },
                },

                shimmer: {
                    '0%': { left: '-100%' },
                    '60%,100%': { left: '160%' },
                },

                spin: {
                    to: {
                        transform: 'rotate(360deg)',
                    },
                },

                floatY: {
                    '0%,100%': {
                        transform: 'translateY(0)',
                    },
                    '50%': {
                        transform: 'translateY(-14px)',
                    },
                },

                floatX: {
                    '0%,100%': {
                        transform: 'translateX(0)',
                    },
                    '50%': {
                        transform: 'translateX(10px)',
                    },
                },

                shake: {
                    '0%,100%': {
                        transform: 'translateX(0)',
                    },
                    '20%': {
                        transform: 'translateX(-6px)',
                    },
                    '40%': {
                        transform: 'translateX(6px)',
                    },
                    '60%': {
                        transform: 'translateX(-4px)',
                    },
                    '80%': {
                        transform: 'translateX(4px)',
                    },
                },
            },

            animation: {
                fadeUp:
                    'fadeUp 0.55s cubic-bezier(.22,1,.36,1) both',

                scaleIn:
                    'scaleIn 0.50s cubic-bezier(.22,1,.36,1) both',

                shimmer:
                    'shimmer 2.8s ease-in-out infinite',

                spin:
                    'spin 0.75s linear infinite',

                floatY:
                    'floatY 8s ease-in-out infinite',

                floatX:
                    'floatX 10s ease-in-out infinite',

                shake:
                    'shake 0.4s ease both',
            },
        },
    },

    plugins: [
        forms,
    ],
};