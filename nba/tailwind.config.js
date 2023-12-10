/** @type {import('tailwindcss').Config} */
import withMT from "@material-tailwind/html/utils/withMT";
module.exports = withMT({
    darkMode: 'media',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
})

