/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");
export default {
    content: [
        // You will probably also need these lines
        "./resources/**/**/*.blade.php",
        "./resources/**/**/*.js",
        "./app/View/Components/**/**/*.php",
        "./app/Livewire/**/**/*.php",
        "./app/Enums/**/*.php",

        // Add mary
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    theme: {
        extend: {},
        fontFamily: {
            opensans: ['"Open Sans"', ...defaultTheme.fontFamily.sans],
            inter: ['"Inter"', ...defaultTheme.fontFamily.sans],
        },
    },
    daisyui: {
        themes: ["cmyk", "dark"],
    },

    // Add daisyUI
    plugins: [require("daisyui"), require("@tailwindcss/typography")],
    darkMode: ["class", '[data-theme="dark"]'],
};
