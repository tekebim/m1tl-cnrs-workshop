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
    // fontFamily: {
    //   'sans': ['ui-sans-serif', 'system-ui', ...],
    //   'serif': ['ui-serif', 'Georgia', ...],
    //   'mono': ['ui-monospace', 'SFMono-Regular', ...],
    //   'display': ['Oswald', ...],
    //   'body': ['Open Sans', ...],
    // }
    extend: {},
  },
  variants: {},
  plugins: [],
}
