/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'naas': {
                    cream: '#F5F0E8',
                    'cream-dark': '#EDE8DE',
                    green: '#1B3A2F',
                    'green-light': '#2D5A4A',
                    terracotta: '#C4704B',
                    'terracotta-dark': '#A85D3A',
                    gold: '#D4A574',
                    dark: '#2C2C2C',
                    'gray-warm': '#8A8278',
                }
            },
            fontFamily: {
                'serif': ['"Playfair Display"', 'Georgia', 'serif'],
                'sans': ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}