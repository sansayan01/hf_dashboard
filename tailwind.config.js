import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './resources/**/*.tsx',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: '#1C2434',
                secondary: '#313D4A',
                accent: '#3C50E0',
                success: '#10B981',
                danger: '#FB4848',
                warning: '#F2994A',
                body: '#F1F5F9',
                bodydark: '#8A99AF',
                darkbg: '#0F172A',
                darkcard: '#1E293B',
                darkaccent: '#3C50E0',
                // Shadcn mappings (using project colors)
                background: '#F1F5F9', // body
                foreground: '#1C2434', // primary
                card: '#FFFFFF',
                'card-foreground': '#1C2434',
                popover: '#FFFFFF',
                'popover-foreground': '#1C2434',
                muted: '#F1F5F9',
                'muted-foreground': '#8A99AF', // bodydark
                border: '#E2E8F0',
                input: '#E2E8F0',
                ring: '#3C50E0', // accent
            },
            fontFamily: {
                sans: ['Outfit', 'sans-serif'],
            },
        },
    },
    plugins: [
        require("tailwindcss-animate"),
    ],
};
