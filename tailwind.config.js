/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')

module.exports = {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    'default': '#FFDF1B'
                }
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
                primary: {
                    'default': '#FFDF1B',
                    '100': '#fff75e',
                    '200': '#fff056',
                    '300': '#ffe94e',
                    '400': '#fff6c4',
                    '500': '#FBE813',
                    '600': '#ffd53e',
                    '700': '#fecf3e',
                    '800': '#fdc43f',
                    '900': '#04464d',
                },
                secondary: '#ecc94b'
            },

        },
    },
    plugins: [],
}
