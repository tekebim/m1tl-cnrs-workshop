// tailwind.config.js
module.exports = {
  purge: {
    enabled: false,
    content: [
      './**/*.php',
      './src/**/*.js',
    ]
  },
  // purge: ['./dist/*.html'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [],
}
