<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JD Repair - Réparation de téléphones professionnelle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        .delay-1 { animation-delay: 0.5s; }
        .delay-2 { animation-delay: 1s; }
        .delay-3 { animation-delay: 1.5s; }

        /* Custom carousel */
        .carousel {
            scroll-snap-type: x mandatory;
            overflow-x: auto;
            display: flex;
        }
        .carousel-item {
            scroll-snap-align: start;
            flex-shrink: 0;
            width: 100%;
            height: auto; /* Adjust height as needed */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .carousel-item img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover; /* To maintain aspect ratio */
        }

        /* Pulse effect for CTA */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }
        .pulse:hover {
            animation: pulse 1.5s infinite;
        }

        /* WhatsApp button animation */
        @keyframes whatsapp-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .whatsapp-float {
            animation: whatsapp-pulse 2s infinite;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <style>
        body {
            margin: 0; /* Élimine les marges par défaut du navigateur */
            padding: 0; /* Élimine les paddings par défaut du navigateur */
            width: 100%; /* Assure que le body prend toujours 100% de la largeur du viewport */
            min-height: 100vh; /* Assure que le body prend au moins toute la hauteur de la vue */
            box-sizing: border-box; /* S'assure que padding et border sont inclus dans la largeur/hauteur */
            /* display: flex; /* Peut être utile si vous voulez que les sections s'empilent ou se répartissent */
            /* flex-direction: column; */
        }
    </style>
    <a href="https://wa.me/+22892595661" target="_blank" rel="noopener noreferrer"
       class="fixed bottom-8 right-8 bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center text-3xl shadow-lg hover:bg-green-600 transition whatsapp-float z-50">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Bouton de bascule mode sombre/clair -->
    <button id="theme-toggle"
        class="fixed bottom-8 left-8 bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-100 w-16 h-16 rounded-full flex items-center justify-center text-2xl shadow-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition z-50"
        aria-label="Changer le thème">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>
    <script>

    // Fonction pour appliquer le thème
    function setTheme(dark) {
        if (dark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            document.getElementById('theme-toggle-icon').className = 'fas fa-sun';
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            document.getElementById('theme-toggle-icon').className = 'fas fa-moon';
        }
        // Adapter les couleurs du bouton WhatsApp
        const waBtn = document.querySelector('.whatsapp-float');
        if (waBtn) {
            waBtn.classList.toggle('bg-green-500', !dark);
            waBtn.classList.toggle('bg-green-600', dark);
            waBtn.classList.toggle('hover:bg-green-600', !dark);
            waBtn.classList.toggle('hover:bg-green-500', dark);
        }
        // Adapter la couleur du header/nav
        const header = document.querySelector('header');
        if (header) {
            if (dark) {
                header.classList.remove('bg-blue-600');
                header.classList.add('bg-gray-900');
            } else {
                header.classList.remove('bg-gray-900');
                header.classList.add('bg-blue-600');
            }
        }
        // Adapter le menu mobile
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            if (dark) {
                mobileMenu.classList.remove('bg-blue-700');
                mobileMenu.classList.add('bg-gray-800');
            } else {
                mobileMenu.classList.remove('bg-gray-800');
                mobileMenu.classList.add('bg-blue-700');
            }
        }
    }

    // Initialisation du thème selon préférence utilisateur ou système
    (function() {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const savedTheme = localStorage.getItem('theme');
        setTheme(savedTheme === 'dark' || (!savedTheme && prefersDark));
    })();

    // Gestion du clic sur le bouton
    document.getElementById('theme-toggle').addEventListener('click', function() {
        const isDark = document.documentElement.classList.contains('dark');
        setTheme(!isDark);
    });

    // Ajout du support Tailwind pour le mode sombre
    if (!document.documentElement.classList.contains('dark') && !document.documentElement.classList.contains('light')) {
        document.documentElement.classList.add('light');
    }
    </script>

    <!-- Styles personnalisés pour le mode sombre -->
    <style>
            /* Mode sombre personnalisé pour le body et textes */
            .dark body {
                background-color: #18181b !important;
                color: #f3f4f6 !important;
            }
            .dark .bg-white { background-color: #23272f !important; }
            .dark .bg-gray-50 { background-color: #18181b !important; }
            .dark .bg-gray-100 { background-color: #23272f !important; }
            .dark .bg-blue-600, .dark header.bg-blue-600 { background-color: #23272f !important; }
            .dark .bg-blue-700, .dark #mobile-menu.bg-blue-700 { background-color: #18181b !important; }
            .dark .bg-blue-900 { background-color: #1e293b !important; }
            .dark .bg-gray-900, .dark header.bg-gray-900 { background-color: #0f172a !important; }
            .dark .bg-gray-200 { background-color: #23272f !important; }
            .dark .text-gray-700, .dark .text-gray-600, .dark .text-gray-500 { color: #d1d5db !important; }
            .dark .text-blue-600 { color: #60a5fa !important; }
            .dark .text-blue-700 { color: #3b82f6 !important; }
            .dark .text-purple-600 { color: #a78bfa !important; }
            .dark .text-green-600 { color: #6ee7b7 !important; }
            .dark .text-yellow-600 { color: #fde68a !important; }
            .dark .text-red-600 { color: #f87171 !important; }
            .dark .text-indigo-600 { color: #818cf8 !important; }
            .dark .text-pink-600 { color: #f472b6 !important; }
            .dark .text-gray-800 { color: #f3f4f6 !important; }
            .dark .border { border-color: #374151 !important; }
            .dark input, .dark textarea, .dark select {
                background-color: #23272f !important;
                color: #f3f4f6 !important;
                border-color: #374151 !important;
            }
            .dark .shadow-lg, .dark .shadow-md {
                box-shadow: 0 4px 24px 0 rgba(0,0,0,0.7) !important;
            }
            .dark .bg-purple-100 { background-color: #312e81 !important; }
            .dark .bg-blue-100 { background-color: #1e40af !important; }
            .dark .bg-green-100 { background-color: #064e3b !important; }
            .dark .bg-yellow-100 { background-color: #78350f !important; }
            .dark .bg-red-100 { background-color: #7f1d1d !important; }
            .dark .bg-indigo-100 { background-color: #3730a3 !important; }
            .dark .bg-pink-100 { background-color: #831843 !important; }
            .dark .text-white { color: #f3f4f6 !important; }
            .dark .hover\:bg-gray-100:hover { background-color: #374151 !important; }
            .dark .hover\:bg-blue-700:hover { background-color: #1d4ed8 !important; }
            .dark .hover\:bg-blue-600:hover { background-color: #2563eb !important; }
            .dark .hover\:bg-blue-50:hover { background-color: #1e293b !important; }
            .dark .hover\:bg-gray-700:hover { background-color: #23272f !important; }
            .dark .hover\:bg-green-600:hover { background-color: #047857 !important; }
            .dark .hover\:bg-green-500:hover { background-color: #22c55e !important; }
    </style>

    <header class="bg-blue-600 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="jd.png" alt="Logo JD Repair" class="h-12 w-auto rounded-md">
                <!-- <h1 class="text-2xl font-bold">JD Repair</h1> -->
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="#hero" class="hover:text-blue-200 transition flex items-center gap-2">
                    <i class="fas fa-home"></i>
                    Accueil
                </a>
                <a href="#services" class="hover:text-blue-200 transition flex items-center gap-2">
                    <i class="fas fa-tools"></i>
                    Services
                </a>
                <a href="#about" class="hover:text-blue-200 transition flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    À propos
                </a>
                <a href="#reviews" class="hover:text-blue-200 transition flex items-center gap-2">
                    <i class="fas fa-star"></i>
                    Avis
                </a>
                <a href="#contact" class="hover:text-blue-200 transition flex items-center gap-2">
                    <i class="fas fa-envelope"></i>
                    Contact
                </a>
                <a href="#verif-form-block" class="bg-white text-blue-600 px-4 py-2 rounded-full font-bold hover:bg-gray-100 transition flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Vérifier ma demande
                </a>
            </nav>
            <button class="md:hidden text-2xl" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="md:hidden hidden bg-blue-700 pb-4" id="mobile-menu">
            <div class="container mx-auto px-4 flex flex-col space-y-3">
                <a href="#hero" class="py-2 hover:text-blue-200 transition">Accueil</a>
                <a href="#services" class="py-2 hover:text-blue-200 transition">Services</a>
                <a href="#about" class="py-2 hover:text-blue-200 transition">À propos</a>
                <a href="#reviews" class="py-2 hover:text-blue-200 transition">Avis</a>
                <a href="#contact" class="py-2 hover:text-blue-200 transition">Contact</a>
                <a href="#verif-form-block" class="bg-white text-blue-600 px-4 py-2 rounded-full font-bold hover:bg-gray-100 transition text-center mt-2">
                    Vérifier ma demande
                </a>
            </div>
        </div>
    </header>

    <section id="hero" class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900">
        <div class="carousel w-full">
            <div class="carousel-item flex items-center justify-center h-96 md:h-[32rem]">
                <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 text-white text-center md:text-left mb-8 md:mb-0">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4">Réparation rapide de votre téléphone</h2>
                        <p class="text-xl mb-6">Service express en moins de 30 minutes pour la plupart des réparations.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="#contact" class="bg-white text-blue-600 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition inline-block pulse">
                                Demander un devis
                            </a>
                            <a href="#verif-form-block" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-blue-600 transition inline-block">
                                Vérifier ma demande
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 flex justify-center">
                        <img src="rep.png"
                             alt="Réparation de téléphone"
                             class="w-64 md:w-80 float-animation rounded-lg shadow-xl object-cover h-full bg-transparent">
                    </div>
                </div>
            </div>

            <div class="carousel-item flex items-center justify-center h-96 md:h-[32rem]">
                <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 text-white text-center md:text-left mb-8 md:mb-0">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4">Écran cassé? Nous le réparons!</h2>
                        <p class="text-xl mb-6">Remplacement d'écran avec des pièces de qualité premium.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="#devis-form-block" class="bg-white text-purple-600 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition inline-block pulse">
                                Demander un devis
                            </a>
                            <a href="#verif-form-block" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-purple-600 transition inline-block">
                                Vérifier ma demande
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 flex justify-center">
                        <img src="rep1.png"
                             alt="Écran de téléphone cassé"
                             class="w-64 md:w-80 float-animation delay-1 rounded-lg shadow-xl object-cover h-full bg-transparent">
                    </div>
                </div>
            </div>

            <div class="carousel-item flex items-center justify-center h-96 md:h-[32rem]">
                <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 text-white text-center md:text-left mb-8 md:mb-0">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4">Batterie faible? Nous la changeons!</h2>
                        <p class="text-xl mb-6">Remplacement de batterie avec garantie.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="#devis-form-block" class="bg-white text-green-600 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition inline-block pulse">
                                Demander un devis
                            </a>
                            <a href="#verif-form-block" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-green-600 transition inline-block">
                                Vérifier ma demande
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 flex justify-center">
                        <img src="rep2.png"
                             alt="Batterie de téléphone"
                             class="w-64 md:w-80 float-animation delay-2 rounded-lg shadow-xl object-cover h-full bg-transparent">
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel indicators (dots) -->
        <div class="absolute left-0 right-0 bottom-6 flex justify-center z-20">
            <button class="mx-1 w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition" onclick="scrollCarousel(0)"></button>
            <button class="mx-1 w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition" onclick="scrollCarousel(1)"></button>
            <button class="mx-1 w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition" onclick="scrollCarousel(2)"></button>
        </div>
    </section>

        <!-- service -->
    <section id="services" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Nos Services</h2>

            <div id="services-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Service 1 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-blue-600 text-2xl float-animation"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Remplacement d'écran</h3>
                    <p class="text-gray-600 mb-2">Remplacement rapide de votre écran cassé avec des pièces de qualité.</p>
                    <div class="text-blue-700 font-bold mb-2">À partir de 25 000 FCFA</div>
                    <a href="#screen-repair" class="mt-2 text-blue-600 font-semibold hover:text-blue-800 transition inline-block">
                        En savoir plus <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <!-- Service 2 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-battery-three-quarters text-purple-600 text-2xl float-animation delay-1"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Remplacement de batterie</h3>
                    <p class="text-gray-600 mb-2">Votre téléphone ne tient plus la charge? Nous avons la solution.</p>
                    <div class="text-purple-700 font-bold mb-2">À partir de 15 000 FCFA</div>
                    <a href="#battery-replacement" class="mt-2 text-purple-600 font-semibold hover:text-purple-800 transition inline-block">
                        En savoir plus <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <!-- Service 3 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-plug text-green-600 text-2xl float-animation delay-2"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Réparation de chargeur</h3>
                    <p class="text-gray-600 mb-2">Problème de charge? Nous diagnostiquons et réparons votre port de charge.</p>
                    <div class="text-green-700 font-bold mb-2">À partir de 10 000 FCFA</div>
                    <a href="#charging-port-repair" class="mt-2 text-green-600 font-semibold hover:text-green-800 transition inline-block">
                        En savoir plus <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <!-- Service 4 -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-water text-yellow-600 text-2xl float-animation delay-3"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Dégâts des eaux</h3>
                    <p class="text-gray-600 mb-2">Votre téléphone est tombé dans l'eau? Agissez rapidement pour le sauver.</p>
                    <div class="text-yellow-700 font-bold mb-2">À partir de 18 000 FCFA</div>
                    <a href="#water-damage-repair" class="mt-2 text-yellow-600 font-semibold hover:text-yellow-800 transition inline-block">
                        En savoir plus <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <!-- Services supplémentaires cachés au départ -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center hidden extra-service">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-microchip text-red-600 text-2xl float-animation"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Changement de connecteur SIM</h3>
                    <p class="text-gray-600 mb-2">Problème de détection de carte SIM? Nous remplaçons le connecteur.</p>
                    <div class="text-red-700 font-bold mb-2">À partir de 12 000 FCFA</div>
                    <span class="block text-xs text-gray-400 mb-2">Garantie 6 mois</span>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center hidden extra-service">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-volume-up text-indigo-600 text-2xl float-animation"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Réparation haut-parleur</h3>
                    <p class="text-gray-600 mb-2">Son faible ou grésillant? Nous réparons ou remplaçons le haut-parleur.</p>
                    <div class="text-indigo-700 font-bold mb-2">À partir de 8 000 FCFA</div>
                    <span class="block text-xs text-gray-400 mb-2">Diagnostic gratuit</span>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center hidden extra-service">
                    <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-camera text-pink-600 text-2xl float-animation"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Remplacement caméra</h3>
                    <p class="text-gray-600 mb-2">Caméra floue ou cassée? Nous la remplaçons rapidement.</p>
                    <div class="text-pink-700 font-bold mb-2">À partir de 20 000 FCFA</div>
                    <span class="block text-xs text-gray-400 mb-2">Pièces d'origine</span>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-lg transition text-center hidden extra-service">
                    <div class="bg-gray-200 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-database text-gray-700 text-2xl float-animation"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Récupération de données</h3>
                    <p class="text-gray-600 mb-2">Récupération de vos photos, contacts et fichiers perdus.</p>
                    <div class="text-gray-700 font-bold mb-2">À partir de 15 000 FCFA</div>
                    <span class="block text-xs text-gray-400 mb-2">Confidentialité garantie</span>
                </div>
            </div>

            <div class="text-center mt-12">
                <button id="toggle-services" class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition inline-block">
                    Voir tous nos services
                </button>
            </div>
            <div class="mt-8 text-center text-gray-600 text-sm">
                <span>Tarifs moyens, main d'œuvre incluse. Devis précis sur demande selon modèle.</span>
            </div>
        </div>
    </section>

    
    <script>
        // Affichage/masquage des services supplémentaires
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('toggle-services');
            const extras = document.querySelectorAll('.extra-service');
            let expanded = false;
            btn.addEventListener('click', function () {
                expanded = !expanded;
                extras.forEach(e => e.classList.toggle('hidden', !expanded));
                btn.textContent = expanded ? 'Réduire la liste' : 'Voir tous nos services';
            });
        });
    </script>

    <!-- À propos -->
    <section id="about" class="py-16 bg-gray-100 relative overflow-hidden">
        <!-- Particules bleues animées -->
        <canvas id="blue-particles" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>
        <!-- Formes décoratives transparentes et animées -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-200 bg-opacity-30 rounded-full blur-2xl pointer-events-none animate-float-shape" style="z-index:1; transform: translate(-40%,-40%);"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-200 bg-opacity-20 rounded-full blur-3xl pointer-events-none animate-float-shape2" style="z-index:1; transform: translate(30%,30%);"></div>
        <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-yellow-200 bg-opacity-20 rounded-full blur-xl pointer-events-none animate-float-shape3" style="z-index:1; transform: translate(-50%,-50%);"></div>
        <!-- Nouvelles formes bleues animées -->
        <div class="absolute top-10 left-1/3 w-40 h-40 bg-blue-400 bg-opacity-40 rounded-full blur-2xl pointer-events-none animate-float-shape4" style="z-index:1;"></div>
        <div class="absolute bottom-16 left-16 w-32 h-32 bg-blue-500 bg-opacity-20 rounded-full blur-2xl pointer-events-none animate-float-shape5" style="z-index:1;"></div>
        <div class="absolute top-1/4 right-10 w-28 h-28 bg-blue-300 bg-opacity-30 rounded-full blur-2xl pointer-events-none animate-float-shape6" style="z-index:1;"></div>
        <div class="container mx-auto px-4 relative" style="z-index:2;">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 flex justify-center items-center">
                    <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80"
                         alt="Technicien JD Repair"
                         class="rounded-xl shadow-lg w-full h-[38rem] md:h-[44rem] object-cover max-w-4xl mx-auto">
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <h2 class="text-3xl font-bold mb-6">À propos de <span class="text-blue-600">JD Repair</span></h2>
                    <p class="text-gray-700 mb-4">
                        Fondé en 2015 à Lomé, JD Repair s’est imposé comme le leader de la réparation de téléphones en Afrique de l’Ouest. 
                        Présent au <b>Togo</b>, <b>Ghana</b>, <b>Bénin</b>, <b>Côte d’Ivoire</b> et bientôt dans d’autres pays, notre réseau de centres agréés accompagne particuliers et entreprises pour tous types de réparations mobiles.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Notre équipe est composée de techniciens certifiés, formés aux dernières technologies et outillés pour intervenir sur toutes les grandes marques (Apple, Samsung, Huawei, Xiaomi, Oppo, etc.). 
                        Nous proposons un diagnostic gratuit, des devis transparents et une prise en charge rapide, souvent en moins de 30 minutes.
                    </p>
                    <p class="text-gray-700 mb-4">
                        JD Repair, c’est aussi un engagement qualité : pièces premium garanties, confidentialité de vos données, service client réactif et suivi personnalisé de chaque demande. 
                        Plus de <b>10 000 appareils réparés</b> et un taux de satisfaction de <b>98%</b> témoignent de notre professionnalisme.
                    </p>
                    <ul class="mb-6 text-gray-700 list-disc pl-5">
                        <li>Intervention sur site ou en atelier</li>
                        <li>Garantie jusqu’à 12 mois sur les réparations</li>
                        <li>Solutions pour entreprises et flottes mobiles</li>
                        <li>Service de récupération de données et désoxydation</li>
                        <li>Conseils personnalisés et accompagnement après réparation</li>
                    </ul>
                    <div class="flex flex-wrap gap-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Techniciens formés</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Pièces de qualité premium</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Garantie sur les réparations</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>Service rapide & suivi en ligne</span>
                        </div>
                    </div>
                    <div class="mb-6">
                        <span class="inline-block bg-red-600 text-white px-4 py-2 rounded-full text-sm font-semibold mr-2 mb-2">Togo</span>
                        <span class="inline-block bg-black text-white px-4 py-2 rounded-full text-sm font-semibold mr-2 mb-2">Ghana</span>
                        <span class="inline-block bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-semibold mr-2 mb-2">Bénin</span>
                        <span class="inline-block bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-semibold mr-2 mb-2">Côte d’Ivoire</span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#about" class="bg-blue-600 text-white px-6 py-3 rounded-full font-bold hover:bg-blue-700 transition inline-block">
                            En savoir plus
                        </a>
                        <a href="https://wa.me/22892595661?text=Bonjour%20JD%20Repair%2C%20je%20souhaite%20avoir%20des%20informations%20ou%20un%20devis%20pour%20une%20r%C3%A9paration%20de%20t%C3%A9l%C3%A9phone." target="_blank" rel="noopener noreferrer" class="bg-white border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-full font-bold hover:bg-blue-50 transition inline-block">
                            Contactez-nous
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel logos marques téléphones -->
        <div class="w-full bg-white py-4 border-t border-b border-blue-100 mt-12 overflow-hidden relative">
            <div id="brand-carousel" class="flex items-center gap-10 whitespace-nowrap will-change-transform" style="animation: brand-marquee 30s linear infinite;">
                <!-- Ajoutez ici les logos des marques (images libres de droits ou logos officiels) -->
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple" class="h-12 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg" alt="Samsung" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://www.dafont.com/forum/attach/orig/8/8/884058.png" alt="Huawei" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/29/Xiaomi_logo.svg" alt="Xiaomi" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://logodix.com/logo/23122.png" alt="Oppo" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://1000logos.net/wp-content/uploads/2022/11/OnePlus-Logo-2013.png" alt="OnePlus" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://th.bing.com/th/id/OIP.uu_v0RkHe0r7n_hxKkSVyQHaEK?w=1536&h=864&rs=1&pid=ImgDetMain" alt="Google Pixel" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://w7.pngwing.com/pngs/852/187/png-transparent-sony-xperia-s-sony-ericsson-xperia-neo-sony-ericsson-xperia-ray-sony-xperia-u-sony-xperia-neo-l-sonylogoeps-trademark-logo-business-thumbnail.png" alt="Sony" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://th.bing.com/th/id/R.c455bf919acf15afbe80254a1aa20cef?rik=MN9MRqUHOwRSPA&riu=http%3a%2f%2fall-spares.ua%2fnfs%2fcontent%2f7398%2ffile%2fnokia_logo.png&ehk=xivOMw9B9RMhyNWg7lKd4tTwiXFJLFYNkRSsjtSOCWE%3d&risl=&pid=ImgRaw&r=0" alt="Nokia" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://www.hdwallpapers.in/download/realme_logo_yellow_background_hd_realme-HD.jpg" alt="Realme" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://th.bing.com/th/id/OIP.k80XVI2jn0LKRn2bzJznywHaB1?w=850&h=210&rs=1&pid=ImgDetMain" alt="Asus" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://static.vecteezy.com/system/resources/previews/020/927/745/non_2x/lenovo-logo-brand-phone-symbol-name-red-design-china-mobile-illustration-free-vector.jpg" alt="Lenovo" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://logowik.com/content/uploads/images/motorola-nuevo3473.logowik.com.webp" alt="Motorola" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <!-- Dupliquez pour effet infini -->

                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple" class="h-12 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg" alt="Samsung" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://www.dafont.com/forum/attach/orig/8/8/884058.png" alt="Huawei" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/29/Xiaomi_logo.svg" alt="Xiaomi" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://logodix.com/logo/23122.png" alt="Oppo" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://1000logos.net/wp-content/uploads/2022/11/OnePlus-Logo-2013.png" alt="OnePlus" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://th.bing.com/th/id/OIP.uu_v0RkHe0r7n_hxKkSVyQHaEK?w=1536&h=864&rs=1&pid=ImgDetMain" alt="Google Pixel" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://w7.pngwing.com/pngs/852/187/png-transparent-sony-xperia-s-sony-ericsson-xperia-neo-sony-ericsson-xperia-ray-sony-xperia-u-sony-xperia-neo-l-sonylogoeps-trademark-logo-business-thumbnail.png" alt="Sony" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://th.bing.com/th/id/R.c455bf919acf15afbe80254a1aa20cef?rik=MN9MRqUHOwRSPA&riu=http%3a%2f%2fall-spares.ua%2fnfs%2fcontent%2f7398%2ffile%2fnokia_logo.png&ehk=xivOMw9B9RMhyNWg7lKd4tTwiXFJLFYNkRSsjtSOCWE%3d&risl=&pid=ImgRaw&r=0" alt="Nokia" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://www.hdwallpapers.in/download/realme_logo_yellow_background_hd_realme-HD.jpg" alt="Realme" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://th.bing.com/th/id/OIP.k80XVI2jn0LKRn2bzJznywHaB1?w=850&h=210&rs=1&pid=ImgDetMain" alt="Asus" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://static.vecteezy.com/system/resources/previews/020/927/745/non_2x/lenovo-logo-brand-phone-symbol-name-red-design-china-mobile-illustration-free-vector.jpg" alt="Lenovo" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
                <img src="https://logowik.com/content/uploads/images/motorola-nuevo3473.logowik.com.webp" alt="Motorola" class="h-10 w-auto inline-block mx-4 grayscale hover:grayscale-0 transition" />
               
            </div>
        </div>

        <!-- styles pour les animations de flottement -->
        <style>
            /* Animations pour les formes flottantes */
            @keyframes float-shape {
                0% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-30px) scale(1.05); }
                100% { transform: translateY(0) scale(1); }
            }
            @keyframes float-shape2 {
                0% { transform: translate(30%,30%) scale(1); }
                50% { transform: translate(30%,10%) scale(1.08); }
                100% { transform: translate(30%,30%) scale(1); }
            }
            @keyframes float-shape3 {
                0% { transform: translate(-50%,-50%) scale(1); }
                50% { transform: translate(-50%,-60%) scale(1.1); }
                100% { transform: translate(-50%,-50%) scale(1); }
            }
            @keyframes float-shape4 {
                0% { transform: translateY(0); }
                50% { transform: translateY(25px); }
                100% { transform: translateY(0); }
            }
            @keyframes float-shape5 {
                0% { transform: translateX(0); }
                50% { transform: translateX(30px); }
                100% { transform: translateX(0); }
            }
            @keyframes float-shape6 {
                0% { transform: scale(1) rotate(0deg);}
                50% { transform: scale(1.08) rotate(8deg);}
                100% { transform: scale(1) rotate(0deg);}
            }
            .animate-float-shape {
                animation: float-shape 7s ease-in-out infinite;
            }
            .animate-float-shape2 {
                animation: float-shape2 9s ease-in-out infinite;
            }
            .animate-float-shape3 {
                animation: float-shape3 8s ease-in-out infinite;
            }
            .animate-float-shape4 {
                animation: float-shape4 6s ease-in-out infinite;
            }
            .animate-float-shape5 {
                animation: float-shape5 10s ease-in-out infinite;
            }
            .animate-float-shape6 {
                animation: float-shape6 12s ease-in-out infinite;
            }
            /* Carousel logos marques */
            @keyframes brand-marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            #brand-carousel {
                min-width: 200%;
            }
        </style>


<!-- Particules bleues animées -->
        <script>
            // Particules bleues animées
            (function() {
                const canvas = document.getElementById('blue-particles');
                if (!canvas) return;
                let ctx, particles = [];
                const colors = [
                    'rgba(59,130,246,0.8)', // blue-600
                    'rgba(37,99,235,0.7)',  // blue-700
                    'rgba(96,165,250,0.6)', // blue-400
                    'rgba(147,197,253,0.5)' // blue-200
                ];
                const PARTICLE_COUNT = 32;
                function resize() {
                    canvas.width = canvas.offsetWidth;
                    canvas.height = canvas.offsetHeight;
                }
                function random(min, max) {
                    return Math.random() * (max - min) + min;
                }
                function createParticles() {
                    particles = [];
                    for (let i = 0; i < PARTICLE_COUNT; i++) {
                        particles.push({
                            x: random(0, canvas.width),
                            y: random(0, canvas.height),
                            r: random(6, 18),
                            color: colors[Math.floor(Math.random() * colors.length)],
                            dx: random(-0.3, 0.3),
                            dy: random(-0.2, 0.2),
                            alpha: random(0.5, 1)
                        });
                    }
                }
                function draw() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    for (let p of particles) {
                        ctx.save();
                        ctx.globalAlpha = p.alpha;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, 2 * Math.PI);
                        ctx.fillStyle = p.color;
                        ctx.shadowColor = p.color;
                        ctx.shadowBlur = 16;
                        ctx.fill();
                        ctx.restore();
                    }
                }
                function animate() {
                    for (let p of particles) {
                        p.x += p.dx;
                        p.y += p.dy;
                        // rebondir sur les bords
                        if (p.x < -p.r) p.x = canvas.width + p.r;
                        if (p.x > canvas.width + p.r) p.x = -p.r;
                        if (p.y < -p.r) p.y = canvas.height + p.r;
                        if (p.y > canvas.height + p.r) p.y = -p.r;
                    }
                    draw();
                    requestAnimationFrame(animate);
                }
                function init() {
                    ctx = canvas.getContext('2d');
                    resize();
                    createParticles();
                    animate();
                }
                window.addEventListener('resize', () => {
                    resize();
                    createParticles();
                });
                setTimeout(init, 200); // attendre que le layout soit prêt
            })();
        </script>
    </section>

    <section id="reviews" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Ce que nos clients disent</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="font-semibold">5.0</span>
                    </div>
                    <p class="text-gray-700 mb-4">
                        "Mon écran était complètement cassé après une chute. JD Repair l'a remplacé en moins d'une heure
                        et maintenant il est comme neuf! Service exceptionnel."
                    </p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Marie D.</h4>
                            <p class="text-sm text-gray-500">Client depuis 2022</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="font-semibold">5.0</span>
                    </div>
                    <p class="text-gray-700 mb-4">
                        "Ma batterie ne tenait plus la charge. JD Repair a fait un diagnostic rapide et changé la batterie.
                        Maintenant mon téléphone tient toute la journée. Je recommande!"
                    </p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Jean P.</h4>
                            <p class="text-sm text-gray-500">Client depuis 2021</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="font-semibold">4.5</span>
                    </div>
                    <p class="text-gray-700 mb-4">
                        "Mon téléphone est tombé dans l'eau et ne fonctionnait plus. JD Repair l'a sauvé!
                        Le service était un peu cher mais ça valait le coup pour sauver mes photos."
                    </p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Sophie L.</h4>
                            <p class="text-sm text-gray-500">Client depuis 2023</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="#reviews" class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition inline-block">
                    Lire tous les avis
                </a>
            </div>
        </div>
    </section>

    <!-- Section Contact animée -->
    <section id="contact" class="py-16 bg-blue-600 text-white transition-all duration-700 opacity-0 translate-y-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h2 class="text-3xl font-bold mb-6">Contactez-nous</h2>
                    <p class="mb-6">
                        Besoin d'aide ou d'informations ? Contactez JD Repair à Lomé, Togo.<br>
                        Nous répondons rapidement à toutes vos demandes !
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-4 text-xl"></i>
                            <span>Lomé, Togo</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone-alt mr-4 text-xl"></i>
                            <span>+228 92 59 56 61</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-4 text-xl"></i>
                            <span>contact@jdrepair.tg</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-4 text-xl"></i>
                            <span>Lundi-Samedi: 8h-19h</span>
                        </div>
                    </div>
                    <div class="mt-8 flex space-x-4">
                        <a href="#" class="bg-white text-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-white text-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-white text-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <?php
                    // Affichage du message de succès ou d'erreur
                    $contact_success = false;
                    $contact_error = '';
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
                        $name = trim($_POST['name'] ?? '');
                        $email = trim($_POST['email'] ?? '');
                        $message = trim($_POST['message'] ?? '');
                        if ($name && $email && $message) {
                            // Ici, vous pouvez envoyer un email ou enregistrer en BDD
                            $contact_success = true;
                        } else {
                            $contact_error = "Veuillez remplir tous les champs.";
                        }
                    }
                    ?>
                    <form method="post" action="contact.php" class="bg-white text-gray-800 p-6 rounded-xl shadow-lg">
                        <input type="hidden" name="contact_form" value="1">
                        <h3 class="text-xl font-bold mb-4 text-blue-600">Formulaire de contact</h3>
                        <?php if ($contact_success): ?>
                            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">Votre message a bien été envoyé !</div>
                        <?php elseif ($contact_error): ?>
                            <div class="mb-4 p-3 rounded bg-red-100 text-red-800"><?= htmlspecialchars($contact_error) ?></div>
                        <?php endif; ?>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 mb-2">Nom complet</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                            Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            // Animation d'apparition de la section contact
            window.addEventListener('DOMContentLoaded', function() {
                const contactSection = document.getElementById('contact');
                setTimeout(() => {
                    contactSection.classList.remove('opacity-0', 'translate-y-8');
                    contactSection.classList.add('opacity-100', 'translate-y-0');
                }, 200);
            });
        </script>
    </section>



    <!-- debut du bloc -->
<section id="devis-verif" class="py-16 bg-white transition-all duration-700 opacity-0 translate-y-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="flex justify-center mb-8 gap-4">
            <button id="btn-devis" class="px-6 py-3 rounded-full font-bold bg-blue-600 text-white hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-400">Demande de devis</button>
            <button id="btn-verif" class="px-6 py-3 rounded-full font-bold bg-gray-200 text-blue-700 hover:bg-blue-100 transition focus:outline-none focus:ring-2 focus:ring-blue-400">Vérifier ma demande</button>
        </div>

        <div id="devis-form-block" class="bg-gray-50 p-8 rounded-xl shadow-lg" style="display: block;">
            <p>Service bientôt disponible, veuillez nous laisser un mail via le formulaire de contact.</p>
            </div>

        <div id="verif-form-block" class="bg-gray-50 p-8 rounded-xl shadow-lg" style="display: none;">
            <h2 class="text-3xl font-bold text-center mb-8">Vérifier l'état de ma demande</h2>
            <form id="checkStatusForm" class="space-y-4">
                <input type="text" name="verif_nom" placeholder="Nom complet" class="border rounded px-3 py-2 w-full">
                <div class="text-center font-semibold">OU</div>
                <input type="text" name="verif_numero" placeholder="Numéro de téléphone" class="border rounded px-3 py-2 w-full">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Vérifier l'état</button>
            </form>
        </div>
        <div class="mt-8"></div>
    </div>
</section>

<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3 relative">
        <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>
        <h3 id="modalTitle" class="text-2xl font-bold mb-4"></h3>
        <div id="modalContent" class="text-sm space-y-2"></div>
        <div id="contactOptions" class="mt-6 border-t pt-4 text-center">
            <h4 class="text-lg font-semibold mb-3">Besoin d'aide supplémentaire ?</h4>
            <div class="flex flex-col space-y-3">
                <a href="https://wa.me/22899181626?text=Bonjour,%20je%20souhaite%20en%20savoir%20plus%20sur%20l'état%20de%20ma%20réparation." target="_blank" class="bg-green-500 text-white px-5 py-2 rounded-lg flex items-center justify-center hover:bg-green-600 transition duration-300">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/1200px-WhatsApp.svg.png" alt="WhatsApp" class="w-5 h-5 mr-2"> Contacter par WhatsApp
                </a>
                <a href="tel:22899181626" class="bg-blue-500 text-white px-5 py-2 rounded-lg flex items-center justify-center hover:bg-blue-600 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/483/483947.png" alt="Appel" class="w-5 h-5 mr-2"> Appeler directement
                </a>
                <a href="mailto:votre-email@example.com?subject=Demande%20d'informations%20sur%20ma%20réparation" class="bg-red-500 text-white px-5 py-2 rounded-lg flex items-center justify-center hover:bg-red-600 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/281/281769.png" alt="Email" class="w-5 h-5 mr-2"> Envoyer un Email
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($reparations_pretes)): ?>
    <h2 class="text-2xl font-bold mt-8 mb-4">Réparations Prêtes à Récupérer</h2>
    <div class="space-y-4">
        <?php foreach ($reparations_pretes as $reparation): ?>
            <div class="border rounded p-4">
                <p><b>Nom :</b> <?= htmlspecialchars($reparation['nom_complet']) ?></p>
                <p><b>Téléphone :</b> <?= htmlspecialchars($reparation['numero']) ?></p>
                <p><b>Marque Tél. :</b> <?= htmlspecialchars($reparation['marque_telephone']) ?></p>
                <p><b>Problème :</b> <?= htmlspecialchars($reparation['probleme']) ?></p>
                <p><b>Date de réparation :</b> <?= htmlspecialchars($reparation['date_reparation']) ?></p>
                <p><b>Statut :</b> <?= htmlspecialchars($reparation['statut_reparation']) ?></p>
                <p><b>Montant total :</b> <?= htmlspecialchars($reparation['montant_total']) ?> FCFA</p>
                <p><b>Montant payé :</b> <?= htmlspecialchars($reparation['montant_paye']) ?> FCFA</p>
                <p><b>Reste à payer :</b> <?= htmlspecialchars($reparation['reste_a_payer']) ?> FCFA</p>
                <?php if ($reparation['statut_paiement']): ?>
                    <p><b>Statut Paiement :</b> <?= htmlspecialchars($reparation['statut_paiement']) ?></p>
                    <p><b>Montant Total Facturé :</b> <?= htmlspecialchars($reparation['montant_facture_total']) ?> FCFA</p>
                    <p><b>Montant Réglé :</b> <?= htmlspecialchars($reparation['montant_regle']) ?> FCFA</p>
                    <p><b>Solde Facture :</b> <?= htmlspecialchars($reparation['reste_a_payer_facture']) ?> FCFA</p>
                <?php else: ?>
                    <p><b>Aucune facture associée à cette réparation pour l'instant.</b></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<!-- fin du bloc -->

        <div class="mt-8 text-center">
            <p class="text-gray-600">Vous n'avez pas encore fait de demande?</p>
                    <a href="#contact" class="text-blue-600 font-semibold hover:text-blue-800 transition inline-block mt-2">
                        Faire une demande de réparation <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Script pour la gestion des devis et vérifications -->
        <!-- <script>
            // Animation d'apparition de la section
            window.addEventListener('DOMContentLoaded', function() {
                const section = document.getElementById('devis-verif');
                setTimeout(() => {
                    section.classList.remove('opacity-0', 'translate-y-8');
                    section.classList.add('opacity-100', 'translate-y-0');
                }, 400);

                // Gestion des boutons
                const btnDevis = document.getElementById('btn-devis');
                const btnVerif = document.getElementById('btn-verif');
                const devisBlock = document.getElementById('devis-form-block');
                const verifBlock = document.getElementById('verif-form-block');

                function showDevis() {
                    devisBlock.style.display = 'block';
                    verifBlock.style.display = 'none';
                    btnDevis.classList.add('bg-blue-600', 'text-white');
                    btnDevis.classList.remove('bg-gray-200', 'text-blue-700');
                    btnVerif.classList.remove('bg-blue-600', 'text-white');
                    btnVerif.classList.add('bg-gray-200', 'text-blue-700');
                }
                function showVerif() {
                    devisBlock.style.display = 'none';
                    verifBlock.style.display = 'block';
                    btnVerif.classList.add('bg-blue-600', 'text-white');
                    btnVerif.classList.remove('bg-gray-200', 'text-blue-700');
                    btnDevis.classList.remove('bg-blue-600', 'text-white');
                    btnDevis.classList.add('bg-gray-200', 'text-blue-700');
                }

                btnDevis.addEventListener('click', showDevis);
                btnVerif.addEventListener('click', showVerif);

                // Si l'URL contient #check-request, afficher la vérif directement
                if (window.location.hash === '#check-request') {
                    showVerif();
                    window.location.hash = '#devis-verif';
                }
                // Si POST pour devis, rester sur devis, si POST pour verif, rester sur verif
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verif_nom'])): ?>
                    showVerif();
                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['devis_form'])): ?>
                    showDevis();
                <?php endif; ?>
            });
        </script> -->


    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-mobile-alt text-2xl text-blue-400"></i>
                        <h3 class="text-xl font-bold">JD Repair</h3>
                    </div>
                    <p class="text-gray-400 max-w-xs">
                        Leader de la réparation de téléphones depuis 2015. Service rapide et professionnel avec garantie.
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Liens rapides</h4>
                        <ul class="space-y-2">
                            <li><a href="#hero" class="text-gray-400 hover:text-white transition">Accueil</a></li>
                            <li><a href="#services" class="text-gray-400 hover:text-white transition">Services</a></li>
                            <li><a href="#about" class="text-gray-400 hover:text-white transition">À propos</a></li>
                            <li><a href="#reviews" class="text-gray-400 hover:text-white transition">Avis</a></li>
                            <li><a href="#contact" class="text-gray-400 hover:text-white transition">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Nos services</h4>
                        <ul class="space-y-2">
                            <li><a href="#screen-repair" class="text-gray-400 hover:text-white transition">Réparation d'écran</a></li>
                            <li><a href="#battery-replacement" class="text-gray-400 hover:text-white transition">Remplacement batterie</a></li>
                            <li><a href="#charging-port-repair" class="text-gray-400 hover:text-white transition">Réparation chargeur</a></li>
                            <li><a href="#water-damage-repair" class="text-gray-400 hover:text-white transition">Dégâts des eaux</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Suivez-nous</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter text-xl"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-500">
                <p>&copy; <?= date('Y'); ?> JD Repair. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        const carousel = document.querySelector('.carousel');
        const carouselItems = document.querySelectorAll('.carousel-item');
        const indicators = document.querySelectorAll('.carousel .absolute button');
        let currentIndex = 0;

        function updateCarousel() {
            if (carousel) {
                carousel.scrollLeft = carouselItems[currentIndex].offsetLeft;
            }
            updateIndicators();
        }

        function updateIndicators() {
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('bg-opacity-100', index === currentIndex);
                indicator.classList.toggle('bg-opacity-50', index !== currentIndex);
            });
        }

        function scrollCarousel(index) {
            currentIndex = index;
            updateCarousel();
        }

        // Autoplay (optional)
        setInterval(() => {
            currentIndex = (currentIndex + 1) % carouselItems.length;
            updateCarousel();
        }, 5000);

        updateCarousel(); // Initial setup
    </script>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href.length > 1 && document.querySelector(href)) {
                    e.preventDefault();
                    document.querySelector(href).scrollIntoView({
                        behavior: 'smooth'
                    });
                    // Fermer le menu mobile après navigation
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });

        // Animation d'apparition des sections au scroll
        function revealSectionsOnScroll() {
            const sections = document.querySelectorAll('section, footer');
            const windowHeight = window.innerHeight;
            sections.forEach(section => {
                const sectionTop = section.getBoundingClientRect().top;
                if (sectionTop < windowHeight - 80) {
                    section.classList.add('animate-fadein');
                }
            });
        }

        // Ajout de la classe d'animation initialement
        document.querySelectorAll('section, footer').forEach(section => {
            section.classList.add('opacity-0', 'transition-opacity', 'duration-700');
        });

        // Définir l'animation fadein via Tailwind ou CSS custom
        const style = document.createElement('style');
        style.innerHTML = `
        .animate-fadein {
            opacity: 1 !important;
            transition: opacity 0.7s;
        }
        `;
        document.head.appendChild(style);

        window.addEventListener('scroll', revealSectionsOnScroll);
        window.addEventListener('load', revealSectionsOnScroll);
    </script>


<!-- Script pour la gestion du formulaire avec option reset des champs -->
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Éléments pour la bascule des formulaires ---
        const btnDevis = document.getElementById('btn-devis');
        const btnVerif = document.getElementById('btn-verif');
        const devisFormBlock = document.getElementById('devis-form-block');
        const verifFormBlock = document.getElementById('verif-form-block');

        // --- Éléments pour la modale de statut ---
        const checkStatusForm = document.getElementById('checkStatusForm');
        const statusModal = document.getElementById('statusModal');
        const closeModalButton = document.getElementById('closeModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const contactOptions = document.getElementById('contactOptions');

        // Fonction pour afficher le bloc "Demande de devis"
        function showDevisBlock() {
            if (devisFormBlock) devisFormBlock.style.display = 'block';
            if (verifFormBlock) verifFormBlock.style.display = 'none';

            // Mettre à jour les styles des boutons
            if (btnDevis) {
                btnDevis.classList.add('bg-blue-600', 'text-white');
                btnDevis.classList.remove('bg-gray-200', 'text-blue-700');
            }
            if (btnVerif) {
                btnVerif.classList.remove('bg-blue-600', 'text-white');
                btnVerif.classList.add('bg-gray-200', 'text-blue-700');
            }
        }

        // Fonction pour afficher le bloc "Vérifier ma demande"
        function showVerifBlock() {
            if (devisFormBlock) devisFormBlock.style.display = 'none';
            if (verifFormBlock) verifFormBlock.style.display = 'block';

            // Mettre à jour les styles des boutons
            if (btnVerif) {
                btnVerif.classList.add('bg-blue-600', 'text-white');
                btnVerif.classList.remove('bg-gray-200', 'text-blue-700');
            }
            if (btnDevis) {
                btnDevis.classList.remove('bg-blue-600', 'text-white');
                btnDevis.classList.add('bg-gray-200', 'text-blue-700');
            }
        }

        // --- Gestion des clics sur les boutons de bascule ---
        if (btnDevis) {
            btnDevis.addEventListener('click', showDevisBlock);
        }
        if (btnVerif) {
            btnVerif.addEventListener('click', showVerifBlock);
        }

        // --- Afficher le bloc "Devis" par défaut au chargement de la page ---
        showDevisBlock();


        // --- Gestion de la soumission du formulaire de vérification (via AJAX) ---
        if (checkStatusForm) {
            checkStatusForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Empêche le rechargement de la page

                const formData = new FormData(this);

                fetch('check_status.php', { // Assurez-vous que ce chemin est correct
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // Parse la réponse JSON
                .then(data => {
                    modalTitle.textContent = data.message; // Affiche le message principal
                    modalContent.innerHTML = ''; // Réinitialise le contenu
                    contactOptions.style.display = 'none'; // Cache les options de contact par défaut

                    if (data.success) {
                        const info = data.data;
                        let htmlContent = `
                            <p><b>Nom complet :</b> ${info.nom_complet || 'Non spécifié'}</p>
                            <p><b>Téléphone :</b> ${info.numero || 'Non spécifié'}</p>
                            <p><b>Marque Tél. :</b> ${info.marque_telephone || 'Non spécifié'}</p>
                            <p><b>Problème :</b> ${info.probleme || 'Non spécifié'}</p>
                        `;

                        if (info.date_reparation) { // Si des infos de réparation existent
                            htmlContent += `
                                <p class="mt-3"><b>Détails de la Réparation :</b></p>
                                <p><b>Date de réparation :</b> ${info.date_reparation}</p>
                                <p><b>Statut Réparation :</b> ${info.statut_reparation}</p>
                                <p><b>Coût Total Est. :</b> ${info.montant_total_reparation} FCFA</p>
                                <p><b>Montant Payé (Rép.) :</b> ${info.montant_paye_reparation} FCFA</p>
                                <p><b>Reste à Payer (Rép.) :</b> ${info.reste_a_payer_reparation} FCFA</p>
                            `;
                        } else {
                            htmlContent += `<p class="mt-3 text-gray-600">Aucune réparation enregistrée pour cette demande pour l'instant.</p>`;
                        }

                        if (info.statut_paiement) { // Si des infos de facture existent
                            htmlContent += `
                                <p class="mt-3"><b>Statut de Paiement (Facture) :</b></p>
                                <p><b>Statut Paiement :</b> ${info.statut_paiement}</p>
                                <p><b>Montant Total Facturé :</b> ${info.montant_facture_total} FCFA</p>
                                <p><b>Montant Réglé :</b> ${info.montant_regle_facture} FCFA</p>
                                <p><b>Solde Facture :</b> ${info.reste_a_payer_facture} FCFA</p>
                            `;
                        } else {
                            htmlContent += `<p class="mt-3 text-gray-600">Aucune facture associée à cette réparation pour l'instant.</p>`;
                        }

                        modalContent.innerHTML = htmlContent;
                        contactOptions.style.display = 'block'; // Affiche les options de contact
                    } else {
                        modalContent.innerHTML = `<p>${data.message}</p>`; // Affiche juste le message si non trouvée
                        contactOptions.style.display = 'none'; // Cache les options de contact si aucune demande n'est trouvée
                    }
                    statusModal.classList.remove('hidden'); // Affiche la modale
                    
                    // --- AJOUTEZ CETTE LIGNE ICI pour réinitialiser le formulaire après un SUCCÈS ---
                    this.reset(); 
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    modalTitle.textContent = 'Erreur de connexion';
                    modalContent.innerHTML = '<p>Impossible de vérifier l\'état de la demande. Veuillez réessayer plus tard.</p>';
                    statusModal.classList.remove('hidden');
                    
                    // --- AJOUTEZ CETTE LIGNE ICI pour réinitialiser le formulaire même en cas d'ERREUR ---
                    this.reset(); 
                });
            });
        }


        // --- Gestion de la fermeture de la modale ---
        if (closeModalButton) {
            closeModalButton.addEventListener('click', function() {
                statusModal.classList.add('hidden'); // Cache la modale
            });
        }

        if (statusModal) {
            statusModal.addEventListener('click', function(e) {
                if (e.target === statusModal) { // Ne ferme que si le clic est sur l'arrière-plan de la modale
                    statusModal.classList.add('hidden');
                }
            });
        }
    });
</script>

<!-- Ancien script sans option de reset -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Éléments pour la bascule des formulaires ---
        const btnDevis = document.getElementById('btn-devis');
        const btnVerif = document.getElementById('btn-verif');
        const devisFormBlock = document.getElementById('devis-form-block');
        const verifFormBlock = document.getElementById('verif-form-block');

        // --- Éléments pour la modale de statut ---
        const checkStatusForm = document.getElementById('checkStatusForm');
        const statusModal = document.getElementById('statusModal');
        const closeModalButton = document.getElementById('closeModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const contactOptions = document.getElementById('contactOptions');

        // Fonction pour afficher le bloc "Demande de devis"
        function showDevisBlock() {
            if (devisFormBlock) devisFormBlock.style.display = 'block';
            if (verifFormBlock) verifFormBlock.style.display = 'none';

            // Mettre à jour les styles des boutons
            if (btnDevis) {
                btnDevis.classList.add('bg-blue-600', 'text-white');
                btnDevis.classList.remove('bg-gray-200', 'text-blue-700');
            }
            if (btnVerif) {
                btnVerif.classList.remove('bg-blue-600', 'text-white');
                btnVerif.classList.add('bg-gray-200', 'text-blue-700');
            }
        }

        // Fonction pour afficher le bloc "Vérifier ma demande"
        function showVerifBlock() {
            if (devisFormBlock) devisFormBlock.style.display = 'none';
            if (verifFormBlock) verifFormBlock.style.display = 'block';

            // Mettre à jour les styles des boutons
            if (btnVerif) {
                btnVerif.classList.add('bg-blue-600', 'text-white');
                btnVerif.classList.remove('bg-gray-200', 'text-blue-700');
            }
            if (btnDevis) {
                btnDevis.classList.remove('bg-blue-600', 'text-white');
                btnDevis.classList.add('bg-gray-200', 'text-blue-700');
            }
        }

        // --- Gestion des clics sur les boutons de bascule ---
        if (btnDevis) {
            btnDevis.addEventListener('click', showDevisBlock);
        }
        if (btnVerif) {
            btnVerif.addEventListener('click', showVerifBlock);
        }

        // --- Afficher le bloc "Devis" par défaut au chargement de la page ---
        showDevisBlock();


        // --- Gestion de la soumission du formulaire de vérification (via AJAX) ---
        if (checkStatusForm) {
            checkStatusForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Empêche le rechargement de la page

                const formData = new FormData(this);

                fetch('check_status.php', { // Assurez-vous que ce chemin est correct
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // Parse la réponse JSON
                .then(data => {
                    modalTitle.textContent = data.message; // Affiche le message principal
                    modalContent.innerHTML = ''; // Réinitialise le contenu
                    contactOptions.style.display = 'none'; // Cache les options de contact par défaut

                    if (data.success) {
                        const info = data.data;
                        let htmlContent = `
                            <p><b>Nom complet :</b> ${info.nom_complet}</p>
                            <p><b>Téléphone :</b> ${info.numero}</p>
                            <p><b>Marque Tél. :</b> ${info.marque_telephone}</p>
                            <p><b>Problème :</b> ${info.probleme}</p>
                        `;

                        if (info.date_reparation) { // Si des infos de réparation existent
                            htmlContent += `
                                <p class="mt-3"><b>Détails de la Réparation :</b></p>
                                <p><b>Date de réparation :</b> ${info.date_reparation}</p>
                                <p><b>Statut Réparation :</b> ${info.statut_reparation}</p>
                                <p><b>Coût Total Est. :</b> ${info.montant_total_reparation} FCFA</p>
                                <p><b>Montant Payé (Rép.) :</b> ${info.montant_paye_reparation} FCFA</p>
                                <p><b>Reste à Payer (Rép.) :</b> ${info.reste_a_payer_reparation} FCFA</p>
                            `;
                        } else {
                            htmlContent += `<p class="mt-3 text-gray-600">Aucune réparation enregistrée pour cette demande pour l'instant.</p>`;
                        }

                        if (info.statut_paiement) { // Si des infos de facture existent
                            htmlContent += `
                                <p class="mt-3"><b>Statut de Paiement (Facture) :</b></p>
                                <p><b>Statut Paiement :</b> ${info.statut_paiement}</p>
                                <p><b>Montant Total Facturé :</b> ${info.montant_facture_total} FCFA</p>
                                <p><b>Montant Réglé :</b> ${info.montant_regle_facture} FCFA</p>
                                <p><b>Solde Facture :</b> ${info.reste_a_payer_facture} FCFA</p>
                            `;
                        } else {
                            htmlContent += `<p class="mt-3 text-gray-600">Aucune facture associée à cette réparation pour l'instant.</p>`;
                        }

                        modalContent.innerHTML = htmlContent;
                        contactOptions.style.display = 'block'; // Affiche les options de contact
                    } else {
                        modalContent.innerHTML = `<p>${data.message}</p>`; // Affiche juste le message si non trouvée
                        contactOptions.style.display = 'none'; // Cache les options de contact si aucune demande n'est trouvée
                    }
                    statusModal.classList.remove('hidden'); // Affiche la modale
                })
                .catch(error => {
                    console.error('Erreur :', error);
                    modalTitle.textContent = 'Erreur de connexion';
                    modalContent.innerHTML = '<p>Impossible de vérifier l\'état de la demande. Veuillez réessayer plus tard.</p>';
                    statusModal.classList.remove('hidden');
                });
            });
        }


        // --- Gestion de la fermeture de la modale ---
        if (closeModalButton) {
            closeModalButton.addEventListener('click', function() {
                statusModal.classList.add('hidden'); // Cache la modale
            });
        }

        if (statusModal) {
            statusModal.addEventListener('click', function(e) {
                if (e.target === statusModal) { // Ne ferme que si le clic est sur l'arrière-plan de la modale
                    statusModal.classList.add('hidden');
                }
            });
        }
    });
</script> -->
</body>
</html>