/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        'node_modules/preline/dist/*.js',
        './resources/**/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('preline/plugin'),
    ],
}
