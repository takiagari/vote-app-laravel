import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primaryColor: '#85E9C6', // メインカラー
                primaryBGColor: '#E6FFF6', // メインカラー系グラウンド
                tPrimaryColor: '#286153', 
                backgroundColor: '#f7f7f7', // 共通背景色
                itemBackgroundColor: '#ffffff' // アイテム背景色
            },
        },
    },

    plugins: [forms],
};
