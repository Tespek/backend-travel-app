import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                heading: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Super Admin Palette (Navy & Slate Governance)
                'gov': {
                    'primary': '#0F172A',
                    'primary-hover': '#1E293B',
                    'accent': '#1E40AF',
                    'canvas': '#F8FAFC',
                    'card': '#FFFFFF',
                    'border': '#E2E8F0',
                    'text': '#0F172A',
                    'muted': '#64748B',
                },
                // Admin Travel Palette (Teal & Emerald Operational Velocity)
                'operator': {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                    'primary': '#0D9488',
                    'primary-hover': '#0F766E',
                    'accent': '#06B6D4',
                    'canvas': '#F0FDFA',
                    'card': '#FFFFFF',
                    'border': '#CCFBF1',
                    'text': '#134E4A',
                    'muted': '#64748B',
                },
                // Semantic status colors for dispatch & bookings
                'travel-status': {
                    'available': '#10B981',
                    'booked': '#0284C7',
                    'delayed': '#F59E0B',
                    'cancelled': '#EF4444',
                },
            },
            backgroundImage: {
                'auth-mesh': 'linear-gradient(135deg, #0F172A 0%, #1E40AF 45%, #0D9488 100%)',
                'auth-btn': 'linear-gradient(135deg, #0D9488 0%, #0284C7 100%)',
                'operator-hero': 'linear-gradient(135deg, #0F766E 0%, #0D9488 40%, #047857 100%)',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'glow-teal': '0 0 20px -2px rgba(13, 148, 136, 0.4)',
                'glow-blue': '0 0 20px -2px rgba(37, 99, 235, 0.35)',
            },
        },
    },

    plugins: [forms],
};
