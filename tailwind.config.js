/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Include all PHP files in app directory
        './app/**/*.php',
        
        // Include all Blade views
        './resources/views/**/*.blade.php',
        
        // Include Livewire components
        './app/Livewire/**/*.php',
        './vendor/livewire/livewire/src/**/*.php',
        
        // Include Flux UI components
        './vendor/livewire/flux/src/**/*.php',
        './vendor/livewire/flux-pro/stubs/**/*.blade.php',
        './vendor/livewire/flux/stubs/**/*.blade.php',
        
        // Include pagination views
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/**/*.blade.php',
        
        // Include JavaScript files if you use Tailwind classes in JS
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                zinc: {
                    50: '#fafafa',
                    100: '#f5f5f5',
                    200: '#e5e5e5',
                    300: '#d4d4d4',
                    400: '#a3a3a3',
                    500: '#737373',
                    600: '#525252',
                    700: '#404040',
                    800: '#262626',
                    900: '#171717',
                    950: '#0a0a0a',
                },
            },
        },
    },
    plugins: [],
};
