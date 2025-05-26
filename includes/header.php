<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>JD REPAIR | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- <script>
        // Configuration Tailwind
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'bounce': 'bounce 2s infinite',
                        'spin-slow': 'spin 3s linear infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#3B82F6',
                            dark: '#2563EB',
                        },
                    }
                }
            }
        }

        // Gestion du thème
        (function toggleTheme() {
            // Vérifie le thème au chargement
            if (localStorage.theme === 'dark' ||
                (!('theme' in localStorage) &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }

            // Fonction pour basculer le thème
            window.toggleTheme = function() {
                if (localStorage.theme === 'dark') {
                    localStorage.theme = 'light'
                    document.documentElement.classList.remove('dark')
                } else {
                    localStorage.theme = 'dark'
                    document.documentElement.classList.add('dark')
                }
            }
        })()

    
    </script> -->
</head>
<body class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-300 ease-in-out flex h-screen">