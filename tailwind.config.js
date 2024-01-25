/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php"
  ],
  theme: {
    extend: {
      colors: {
        'navbar-300': '#ccccae',
        'navbar-200': '#f5f5d6',
        'primary-bg': '#FFFFE0',
        'primary-yellow' : '#FFD700'
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

