

<aside id="sidebar" class="fixed top-0 left-0 h-full bg-white dark:bg-gray-800 shadow-lg rounded-r-2xl transition-all duration-300 hover:shadow-xl overflow-hidden z-50 w-64"
       data-sidebar-expanded="true" aria-label="Sidebar navigation">
    <div class="p-4 flex flex-col justify-between h-full">
        <div>
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-xl font-extrabold tracking-tight text-primary flex items-center gap-2 transition-all duration-300 hover:text-blue-600 dark:hover:text-blue-400">
                    <div class="relative">
                        <i class="fa-solid fa-screwdriver-wrench text-3xl text-blue-500 animate-bounce" aria-hidden="true"></i>
                        <i class="fa-solid fa-hammer absolute -bottom-1 -right-1 text-xs bg-yellow-400 rounded-full p-1 text-white" aria-hidden="true"></i>
                    </div>
                    <span class="md:block bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent sidebar-text">JD Repair</span>
                </h1>
                <!-- Bouton toggle sidebar désactivé intentionnellement -->
                <!-- <button id="toggle-sidebar-desktop" onclick="toggleSidebar()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none" aria-label="Toggle sidebar">
                    <i class="fa-solid fa-chevron-left"></i>
                </button> -->
            </div>

            <nav class="space-y-3" role="navigation" aria-label="Menu principal">
                <a href="/JD_REPAIR/admin/dashboard.php" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 group hover:shadow-md hover:-translate-y-1 border-l-4 border-transparent hover:border-blue-500" tabindex="0">
                    <i class="fa-solid fa-chart-line text-xl group-hover:text-blue-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                    <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300 sidebar-text">Tableau de bord</span>
                </a>

                <a href="/JD_REPAIR/admin/demande/index.php" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 group hover:shadow-md hover:-translate-y-1 border-l-4 border-transparent hover:border-green-500" tabindex="0">
                    <i class="fa-solid fa-file-circle-plus text-xl group-hover:text-green-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                    <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300 sidebar-text">Demandes</span>
                </a>

                <a href="/JD_REPAIR/admin/traitement/index.php" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 group hover:shadow-md hover:-translate-y-1 border-l-4 border-transparent hover:border-purple-500" tabindex="0">
                    <i class="fa-solid fa-hand-holding-medical text-xl group-hover:text-purple-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                    <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300 sidebar-text">Traitements</span>
                </a>

                <a href="/JD_REPAIR/admin/reparation/index.php" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 group hover:shadow-md hover:-translate-y-1 border-l-4 border-transparent hover:border-yellow-500" tabindex="0">
                    <div class="relative">
                        <i class="fa-solid fa-screwdriver-wrench text-xl group-hover:text-yellow-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                        <!-- <i class="fa-solid fa-bolt absolute -bottom-1 -right-1 text-xs bg-orange-400 rounded-full p-0.5 text-white" aria-hidden="true"></i> -->
                    </div>
                    <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors duration-300 sidebar-text">Réparations</span>
                </a>

                <a href="/JD_REPAIR/admin/facture/index.php" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 group hover:shadow-md hover:-translate-y-1 border-l-4 border-transparent hover:border-red-500" tabindex="0">
                    <i class="fa-solid fa-receipt text-xl group-hover:text-red-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                    <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-300 sidebar-text">Factures</span>
                </a>

                <a href="/JD_REPAIR/admin/utilisateurs/index.php" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 group hover:shadow-md hover:-translate-y-1 border-l-4 border-transparent hover:border-indigo-500" tabindex="0">
                    <i class="fa-solid fa-user-gear text-xl group-hover:text-indigo-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                    <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300 sidebar-text">Utilisateurs</span>
                </a>
            </nav>
        </div>

        <div class="space-y-3 mt-6">
            <button onclick="toggleTheme()" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 group hover:shadow-md transform hover:-translate-y-0.5" aria-label="Changer le thème">
                <i id="themeButtonIcon" class="fa-solid fa-moon text-xl group-hover:text-yellow-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors duration-300 sidebar-text">Thème</span>
            </button>

            <!-- <button class="w-full flex items-center gap-3 px-3 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 group hover:shadow-md transform hover:-translate-y-0.5" aria-label="Paramètres">
                <i class="fa-solid fa-sliders text-xl group-hover:text-blue-500 transition-colors duration-300 w-8 text-center animate-spin-slow" aria-hidden="true"></i>
                <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300 sidebar-text">Paramètres</span>
            </button> -->

            <a href="/JD_REPAIR/admin/logout.php" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-red-100 dark:hover:bg-red-700 transition-all duration-300 group hover:shadow-md transform hover:-translate-y-0.5" aria-label="Déconnexion">
                <i class="fa-solid fa-right-from-bracket text-xl group-hover:text-red-500 transition-colors duration-300 w-8 text-center" aria-hidden="true"></i>
                <span class="md:block font-medium text-gray-700 dark:text-gray-200 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-300 sidebar-text">Déconnexion</span>
            </a>
        </div>
    </div>

    <script>
        // Fonction pour garder le hover sur le lien actif du sidebar
        document.addEventListener('DOMContentLoaded', function () {
            // Récupère tous les liens du menu
            const links = document.querySelectorAll('#sidebar nav a');
            // Récupère le chemin courant (sans query string)
            const currentPath = window.location.pathname.replace(/\/+$/, '');

            links.forEach(link => {
                // Normalise le href pour la comparaison
                const linkPath = link.getAttribute('href').replace(/\/+$/, '');
                if (linkPath === currentPath) {
                    // Ajoute les classes de hover et d'active
                    link.classList.add('bg-blue-50', 'dark:bg-gray-700', 'shadow-md', '-translate-y-1');
                    // Ajoute la bordure colorée selon la couleur du hover
                    if (link.className.includes('hover:border-blue-500')) {
                        link.classList.add('border-blue-500');
                    } else if (link.className.includes('hover:border-green-500')) {
                        link.classList.add('border-green-500');
                    } else if (link.className.includes('hover:border-purple-500')) {
                        link.classList.add('border-purple-500');
                    } else if (link.className.includes('hover:border-yellow-500')) {
                        link.classList.add('border-yellow-500');
                    } else if (link.className.includes('hover:border-red-500')) {
                        link.classList.add('border-red-500');
                    } else if (link.className.includes('hover:border-indigo-500')) {
                        link.classList.add('border-indigo-500');
                    }
                }
            });
        });
    </script>
</aside>
