// tailwind.config.js
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  purge: ['./pages/**/*.{js,ts,jsx,tsx}', './components/**/*.{js,ts,jsx,tsx}'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    screens: {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1400px',
      betterhover: { raw: '(hover: hover)' },
    },
    extend: {
      colors: {
        red: {
          '600': '#e21b43'
        }
      },
      backgroundImage: theme => ({
        'hero-pattern': "url('/images/background_image.jpg')",
      })
    },
    rotate: {
      ...defaultTheme.rotate,
      '-30': '-30deg',
    },
    container: {
      padding: '1rem',
    },
    customForms: theme => ({
      sm: {
        'input, textarea, multiselect, select': {
          fontSize: theme('fontSize.sm'),
          padding: `${theme('spacing.1')} ${theme('spacing.2')}`,
        },
        select: {
          paddingRight: `${theme('spacing.4')}`,
        },
      },
    }),
  },
  variants: {},
  plugins: [require('@tailwindcss/ui')],
}
