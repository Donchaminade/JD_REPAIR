<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>JD REPAIR | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
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
        (function() {
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

        // Fonction pour basculer la visibilité du sidebar
       // Fonction pour basculer la visibilité du sidebar
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const expanded = sidebar.getAttribute('data-sidebar-expanded') === 'true';
            sidebar.setAttribute('data-sidebar-expanded', !expanded);

            if (expanded) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                mainContent.classList.remove('md:ml-64');
                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('#sidebar nav a').forEach(el => el.classList.replace('gap-3', 'justify-center'));
                document.querySelectorAll('#sidebar nav a i').forEach(el => el.classList.replace('md:text-lg', 'text-xl'));
                const logoIcon = document.querySelector('#sidebar h1 i.fa-screwdriver-wrench');
                if (logoIcon) logoIcon.classList.replace('text-3xl', 'text-4xl');
                // Cacher les textes "Thème" et "Paramètres" du bas
                const bottomBarTexts = sidebar.querySelectorAll('.space-y-3.mt-6 button span.sidebar-text');
                bottomBarTexts.forEach(text => text.classList.add('hidden'));
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                mainContent.classList.add('md:ml-64');
                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('#sidebar nav a').forEach(el => el.classList.replace('justify-center', 'gap-3'));
                document.querySelectorAll('#sidebar nav a i').forEach(el => el.classList.replace('text-xl', 'md:text-lg'));
                const logoIcon = document.querySelector('#sidebar h1 i.fa-screwdriver-wrench');
                if (logoIcon) logoIcon.classList.replace('text-4xl', 'text-3xl');
                // Afficher les textes "Thème" et "Paramètres" du bas
                const bottomBarTexts = sidebar.querySelectorAll('.space-y-3.mt-6 button span.sidebar-text.hidden');
                bottomBarTexts.forEach(text => text.classList.remove('hidden'));
            }
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white transition-colors duration-300 ease-in-out flex h-screen">