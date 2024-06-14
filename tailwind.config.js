import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')

module.exports = {
    darkMode: 'class',
    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js'),
        require('tailwind-scrollbar'),
    ],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php'
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    'default': '#6fbd6b',
                    '50': '#f4faf3',
                    '100': '#e4f5e3',
                    '200': '#caeac8',
                    '300': '#86cd82',
                    '400': '#6fbd6b',
                    '500': '#4ba146',
                    '600': '#398435',
                    '700': '#2f682d',
                    '800': '#295427',
                    '900': '#234522',
                    '950': '#0e250e',
                },
            },
            fontSize: {
                xxs: '0.6rem',
            },
            fontFamily: {
                'oswald': ['Oswald', 'sans-serif'],
                'roboto': ['Roboto', 'sans-serif']
            },
            animation: {
                'infinite-scroll': 'infinite-scroll 25s linear infinite',
            },
            keyframes: {
                'infinite-scroll': {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-100%)' },
                }
            },
            zIndex: {
                '1': '1',
                '2': '2',
                '3': '3',
                '4': '4',
                '5': '5',
                '100': '100',
            },
            colors: {
                app: {
                    'default': '#6fbd6b',
                    '50': '#f4faf3',
                    '100': '#e4f5e3',
                    '200': '#caeac8',
                    '300': '#86cd82',
                    '400': '#6fbd6b',
                    '500': '#4ba146',
                    '600': '#398435',
                    '700': '#2f682d',
                    '800': '#295427',
                    '900': '#234522',
                    '950': '#0e250e',
                }
            },

        },
    },
    plugins: [forms],
}
