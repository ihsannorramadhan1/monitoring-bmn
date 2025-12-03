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
                kemenkeu: {
                    primary: '#005FAC', // Navy Blue
                    secondary: '#FAB715', // Golden Yellow
                    green: '#02A54F', // Green
                }
            }
        },
    },

    plugins: [forms],
};
