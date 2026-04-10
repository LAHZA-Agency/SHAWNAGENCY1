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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': 'hsla(var(--color-primary))',
                'primary-light': 'hsla(var(--color-primary-light))',
                'primary-dark': 'hsla(var(--color-primary-dark))',
                'secondary': 'hsla(var(--color-secondary))',
                'secondary-light': 'hsla(var(--color-secondary-light))',
                'main': 'hsla(var(--color-main))',
                'main-light': 'hsla(var(--color-main-light))',
                'main-dark': 'hsla(var(--color-main-dark))',
                'c-border': 'hsla(var(--color-c-border))',
                'c-shadow': 'hsla(var(--color-c-shadow))',
                'accent': 'hsla(var(--color-accent))',
                'accent-light': 'hsla(var(--color-accent-light))',
                'accent-dark': 'hsla(var(--color-accent-dark))',
                "success": "#5bd782",
                "success": "hsla(var(--color-success))",
                "success-dark": "hsla(var(--color-success-dark))",
                "error": "hsla(var(--color-error))",
                "error-dark": "hsla(var(--color-error-dark))",
            },
            
        },
    },
    darkMode: 'class',
    plugins: [forms],

};
