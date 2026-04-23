/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/Views/**/*.php',
    // ... file lainnya
  ],
  theme: {
    extend: {},
  },
  plugins: [
    // Ini dia onderdil barunya!
    require('@tailwindcss/typography'),
  ],
};
