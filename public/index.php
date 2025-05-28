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
                <a href="#check-request" class="bg-white text-blue-600 px-4 py-2 rounded-full font-bold hover:bg-gray-100 transition flex items-center gap-2">
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
                <a href="#check-request" class="bg-white text-blue-600 px-4 py-2 rounded-full font-bold hover:bg-gray-100 transition text-center mt-2">
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
                            <a href="#check-request" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-blue-600 transition inline-block">
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
                            <a href="#contact" class="bg-white text-purple-600 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition inline-block pulse">
                                Demander un devis
                            </a>
                            <a href="#check-request" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-purple-600 transition inline-block">
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
                        <p class="text-xl mb-6">Remplacement de batterie avec garantie d'un an.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="#contact" class="bg-white text-green-600 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition inline-block pulse">
                                Demander un devis
                            </a>
                            <a href="#check-request" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-green-600 transition inline-block">
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
                    <h2 class="text-3xl font-bold mb-6">À propos de JD Repair</h2>
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
                            <span>Techniciens certifiés</span>
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
        </style>
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

    <section id="contact" class="py-16 bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h2 class="text-3xl font-bold mb-6">Contactez-nous</h2>
                    <p class="mb-6">
                        Vous avez besoin de réparer votre téléphone? Remplissez le formulaire ou contactez-nous directement.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-4 text-xl"></i>
                            <span>123 Rue de la Réparation, 75000 Paris</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone-alt mr-4 text-xl"></i>
                            <span>01 23 45 67 89</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-4 text-xl"></i>
                            <span>contact@jdrepair.fr</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-4 text-xl"></i>
                            <span>Lundi-Vendredi: 9h-19h | Samedi: 10h-18h</span>
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
                    <form method="post" action="traitement_formulaire.php" class="bg-white text-gray-800 p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-bold mb-4 text-blue-600">Demande de consultation</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 mb-2">Nom complet</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="device" class="block text-gray-700 mb-2">Appareil à réparer</label>
                            <select id="device" name="device" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionnez votre appareil</option>
                                <option value="iphone">iPhone</option>
                                <option value="samsung">Samsung</option>
                                <option value="huawei">Huawei</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="problem" class="block text-gray-700 mb-2">Problème rencontré</label>
                            <textarea id="problem" name="problem" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                            Envoyer la demande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section id="check-request" class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="bg-gray-50 p-8 rounded-xl shadow-lg">
                <h2 class="text-3xl font-bold text-center mb-8">Vérifier l'état de ma demande</h2>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#check-request" class="space-y-4">
                    <input type="text" name="verif_nom" placeholder="Nom complet" class="border rounded px-3 py-2 w-full">
                    <div class="text-center font-semibold">OU</div>
                    <input type="text" name="verif_numero" placeholder="Numéro de téléphone" class="border rounded px-3 py-2 w-full">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Vérifier l'état</button>
                </form>

                <div class="mt-8" id="request-status">
                    <?php
                    // Simuler une vérification de l'état (à remplacer par une logique réelle)
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['verif_nom']) || isset($_POST['verif_numero']))) {
                        $nom_verif = $_POST['verif_nom'] ?? '';
                        $numero_verif = $_POST['verif_numero'] ?? '';

                        // Exemple de données de réparation (à remplacer par une requête BDD)
                        $reparations = [
                            ['nom_complet' => 'Marie D.', 'numero' => '0123456789', 'date_reparation' => '2025-05-25', 'statut' => 'Réparé', 'montant_total' => 80, 'montant_paye' => 80, 'reste_a_payer' => 0],
                            ['nom_complet' => 'Jean P.', 'numero' => '0698765432', 'date_reparation' => '2025-05-28', 'statut' => 'En cours', 'montant_total' => 50, 'montant_paye' => 20, 'reste_a_payer' => 30],
                            // ... plus de réparations
                        ];

                        $verif_message = '';
                        $verif_infos = null;

                        foreach ($reparations as $reparation) {
                            if (($nom_verif && strtolower($reparation['nom_complet']) === strtolower($nom_verif)) || ($numero_verif && $reparation['numero'] === $numero_verif)) {
                                $verif_infos = $reparation;
                                $verif_message = '✅ Demande trouvée ! Voici les informations :';
                                break;
                            }
                        }

                        if (!$verif_infos && ($nom_verif || $numero_verif)) {
                            $verif_message = '⚠️ Aucune demande trouvée avec ces informations.';
                        }

                        if ($verif_message): ?>
                            <div class="mt-4 p-3 rounded <?php
                                strpos($verif_message,'✅') !== false ? 'bg-green-100 text-green-800' :
                                (strpos($verif_message, '⚠️') !== false ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')
                            ?>">
                                <?= $verif_message ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($verif_infos): ?>
                            <div class="mt-2 text-sm">
                                <div><b>Nom :</b> <?= htmlspecialchars($verif_infos['nom_complet']) ?></div>
                                <div><b>Téléphone :</b> <?= htmlspecialchars($verif_infos['numero']) ?></div>
                                <div><b>Date de réparation :</b> <?= htmlspecialchars($verif_infos['date_reparation']) ?></div>
                                <div><b>Statut :</b> <?= htmlspecialchars($verif_infos['statut']) ?></div>
                                <div><b>Montant total :</b> <?= htmlspecialchars($verif_infos['montant_total']) ?> FCFA</div>
                                <div><b>Montant payé :</b> <?= htmlspecialchars($verif_infos['montant_paye']) ?> FCFA</div>
                                <div><b>Reste à payer :</b> <?= htmlspecialchars($verif_infos['reste_a_payer']) ?> FCFA</div>
                            </div>
                        <?php endif;
                    }
                    ?>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-gray-600">Vous n'avez pas encore fait de demande?</p>
                    <a href="#contact" class="text-blue-600 font-semibold hover:text-blue-800 transition inline-block mt-2">
                        Faire une demande de réparation <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

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
</body>
</html>