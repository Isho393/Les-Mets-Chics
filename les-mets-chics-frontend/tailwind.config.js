/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app.vue",
    "./error.vue"
  ],
  theme: {
    extend: {
      colors: {
        // Palette Premium Gastronomique
        cream: {
          50: '#FFFEF9',
          100: '#FFF6E5', // Crème Vanille - fond principal
          200: '#FFF0D1',
          300: '#FFE8B8',
          400: '#FFDC9E',
          500: '#F5D085',
        },
        bordeaux: {
          50: '#FDF2F4',
          100: '#FCE7EA',
          200: '#F8CDD2',
          300: '#F2A8B1',
          400: '#E97284',
          500: '#DC3F58',
          600: '#C62D47',
          700: '#A52238',
          800: '#7A1E2D', // Bordeaux Gourmet - identité
          900: '#5C1722',
        },
        gold: {
          50: '#FEFCF3',
          100: '#FDF8E1',
          200: '#FAF0C4',
          300: '#F6E49C',
          400: '#F0D670',
          500: '#E8C547',
          600: '#D4B23A',
          700: '#C6A75E', // Or Doux - luxe subtil
          800: '#A08B4E',
          900: '#7A6B3C',
        },
        charcoal: {
          50: '#F8F8F8',
          100: '#F0F0F0',
          200: '#E4E4E4',
          300: '#D1D1D1',
          400: '#B4B4B4',
          500: '#9A9A9A',
          600: '#8A8A8A', // Gris Chaud - texte secondaire
          700: '#6F6F6F',
          800: '#4A4A4A',
          900: '#1B1B1B', // Noir Charbon - texte & contrastes
        }
      },
      fontFamily: {
        'display': ['Playfair Display', 'serif'],
        'body': ['Inter', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
        sans: ['Inter', 'sans-serif']
      },
      animation: {
        'fade-in': 'fadeIn 0.6s ease-out',
        'slide-up': 'slideUp 0.8s ease-out',
        'scale-in': 'scaleIn 0.5s ease-out',
        'float': 'float 3s ease-in-out infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        scaleIn: {
          '0%': { opacity: '0', transform: 'scale(0.9)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
      },
      boxShadow: {
        'soft': '0 4px 20px rgba(0, 0, 0, 0.08)',
        'premium': '0 8px 40px rgba(122, 30, 45, 0.12)',
        'gold': '0 4px 20px rgba(198, 167, 94, 0.25)',
      },
      backdropBlur: {
        'xs': '2px',
      },
    },
  },
  plugins: [],
}