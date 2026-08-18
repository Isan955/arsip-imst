import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#1044D1',
                    50: '#eff4ff',
                    100: '#dbe7ff',
                    200: '#bfd4ff',
                    300: '#93b4ff',
                    400: '#608cff',
                    500: '#1044D1',
                    600: '#1d4ed8',
                    700: '#1e40af',
                    800: '#1e3a8a',
                    900: '#172554',
                },
                secondary: '#F8FAFC',
                accent: '#2563EB',
                bgLight: '#F5F7FB',
                status: {
                    success: '#22C55E',
                    warning: '#F59E0B',
                    danger: '#EF4444',
                }
            }
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin')
    ],
};
