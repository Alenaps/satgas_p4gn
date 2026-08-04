import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        // Dynamic classes used in program cards @foreach loop (welcome.blade.php & dashboard.blade.php)
        'text-blue-400', 'text-emerald-400',
        'bg-blue-500/10', 'bg-emerald-500/10',
        'hover:border-blue-500/20', 'hover:border-emerald-500/20',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
