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
          600: '#0d57ed',
          500: '#246dff',
        },
        cyan: '#38c7ff',
        green: '#39c977',
        red: '#ff5a55',
        yellow: '#ffb915',
        purple: '#7557ea',
        ink: '#0d1d4a',
        muted: '#65718d',
        soft: '#f5f9ff',
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
