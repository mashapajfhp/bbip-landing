/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        navy: '#071a45',
        blue: {
          50: '#e7f0ff',
          100: '#e6efff',
          200: '#d8e4f5',
          300: '#a9c6ff',
          500: '#246dff',
          600: '#0d57ed',
          700: '#0b47d1',
        },
        cyan: '#38c7ff',
        emerald: {
          100: '#e3f8ec',
          500: '#25d366',
          600: '#2fbf70',
        },
        green: '#39c977',
        red: '#ff5a55',
        yellow: {
          100: '#fff3d8',
          200: '#fff0c8',
          600: '#f1a300',
          700: '#e8a500',
        },
        purple: {
          100: '#eee9ff',
          600: '#7456df',
          700: '#7557ea',
        },
        ink: '#0d1d4a',
        muted: '#65718d',
        soft: '#f5f9ff',
        line: '#dfe9f8',
      },
      fontFamily: {
        sans: ['system-ui', 'sans-serif'],
      },
      spacing: {
        xs: '0.5rem',
        sm: '1rem',
        md: '1.5rem',
        lg: '2rem',
        xl: '3rem',
        '2xl': '4rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
