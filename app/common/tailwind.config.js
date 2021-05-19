// tailwind.config.js
module.exports = {
  purge: {
    enabled: true,
    content: [
      './src/**/*.php',
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
