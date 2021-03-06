// tailwind.config.js
const colors = require('tailwindcss/colors')

module.exports = {
  purge: {
    enabled: true,
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
    extend: {
      colors: {
        'primary': {
          light: 'var(--color-primary-lighten)',
          DEFAULT: 'var(--color-primary)',
          dark: 'var(--color-primary-darken)'
        },
        'secondary': {
          light: 'var(--color-secondary-lighten)',
          DEFAULT: 'var(--color-secondary)',
          dark: 'var(--color-secondary-darken)'
        },
      },
      stroke: theme => ({
        'primary': theme('colors.primary'),
        'secondary': theme('colors.secondary'),
      })
    },
    screens: {
      sm: '480px',
      md: '768px',
      lg: '976px',
      xl: '1440px',
    },
    fontFamily: {
      'sans': ['Asap', 'Arial', 'sans-serif'],
      'ipa': ['Voces', 'Arial', 'sans-serif']
    }
  },
  plugins: [],
}
