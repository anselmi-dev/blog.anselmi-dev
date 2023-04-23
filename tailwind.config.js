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
            zIndex: {
                '1': '1',
                '2': '2',
                '3': '3',
                '4': '4',
                '5': '5',
            },
            colors: {
                primary: {
                    'default': '#04BA71',
                    '100': '#fff75e',
                    '200': '#fff056',
                    '300': '#ffe94e',
                    '400': '#ffe246',
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
