import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

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
        screens: {
            'xs':'440px',
            ...defaultTheme.screens,
        },
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
                'secondary': ['Roboto', 'sans-serif'],
                'primary': ['Montserrat', 'sans-serif']
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
                secondary: {
                    dark: '#333333',
                    hover: '#dcd9cc',
                    default: '#f2f1ec',
                    '50': '#f8f7f4',
                    '100': '#f2f1ec',
                    '200': '#dcd9cc',
                    '300': '#c5c0ac',
                    '400': '#ada38a',
                    '500': '#9d8f72',
                    '600': '#907f66',
                    '700': '#786956',
                    '800': '#635749',
                    '900': '#51473d',
                    '950': '#2b251f',
                },
                app: {
                    'primary': '#398435',
                    'hover': '#398435',
                    'default': '#00d495',
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
            flex: {
                '2': '2 2 0%',
                '3': '3 3 0%',
                '4': '4 4 0%',
                '9': '9 9 0%',
            }
        },
    },
    plugins: [forms],
}
