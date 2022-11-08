const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1280px',
            'xl': '1536px',
            '2xl': '1536px',
        },
        extend: {
            colors: {
                'red-semparar': '#7d0c12',
                'gray-semparar': '#505050',
                'lightgray-semparar': '#edeff0',
                'green-semparar': '#9d963a',
                'blue-semparar': '#406979',
            },
            fontFamily: {
                sans: ['Ubuntu', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
