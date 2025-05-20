<footer class="mt-auto bg-gray-200 dark:bg-gray-800 text-center text-sm py-4 transition-all duration-200">
    <p class="text-gray-600 dark:text-gray-300">&copy; <?php echo date("Y"); ?> JD Repair. Tous droits réservés.</p>
</footer>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeIcon(isDark);
    }

    function updateThemeIcon(isDark) {
        const icon = document.getElementById('themeIcon');
        if (!icon) return;

        // Réinitialiser
        icon.className = '';
        icon.classList.add('w-5', 'h-5');

        if (isDark) {
            icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 text-yellow-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v1.5m6.364 1.636l-1.06 1.06M21 12h-1.5M18.364 18.364l-1.06-1.06M12 21v-1.5M5.636 18.364l1.06-1.06M3 12h1.5M5.636 5.636l1.06 1.06M12 6.75a5.25 5.25 0 1 1 0 10.5a5.25 5.25 0 0 1 0-10.5z" />
            </svg>`;
        } else {
            icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 text-gray-800">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21.75 15.75a9.75 9.75 0 0 1-12-12a9.75 9.75 0 1 0 12 12z" />
            </svg>`;
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        const isDark = localStorage.getItem('theme') === 'dark';
        if (isDark) document.documentElement.classList.add('dark');
        updateThemeIcon(isDark);

        // Masquer le loader après 2 secondes
        setTimeout(() => {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.style.opacity = 0;
                setTimeout(() => loader.remove(), 500); // Délai pour la transition d'opacité
            }
        }, 2000);
    });
</script>

</body>
</html>