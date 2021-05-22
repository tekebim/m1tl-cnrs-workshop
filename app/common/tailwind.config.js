// tailwind.config.js
module.exports = {
  purge: {
    enabled: false,
    content: [
      './**/*.php',
      './**/*.twig',
      './*.php',
      './*.twig',
      './src/**/*.js',
    ]
  },
  // purge: ['./dist/*.html'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    screens: {
      sm: '480px',
      md: '768px',
      lg: '976px',
      xl: '1440px',
    },
    fontFamily: {
      'sans': ['Asap', 'Arial', 'sans-serif']
    }
  },
  variants: {},
  plugins: [],
}
