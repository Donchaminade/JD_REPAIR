<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="bg-black py-2 fixed top-0 left-0 w-full z-50 shadow-lg">
    <div class="container mx-auto flex items-center justify-between">
        <div class="flex items-center space-x-6">
            <a href="dashboard.php" class="text-white font-bold text-xl"></a>
            <a href="dashboard.php" class="text-gray-300 hover:text-white text-lg">
                <!-- <i class="fas fa-home"></i> Accueil -->
            </a>
        </div>
        <div class="text-white text-center">
            <span id="datetime" class="text-lg"></span>
        </div>
        <div class="flex items-center space-x-6">
            <?php if (isset($_SESSION['user_name'])): ?>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user-circle text-white text-2xl"></i>
                    <div>
                        <div class="text-white font-semibold"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                        <div class="text-gray-400 text-sm"><?= htmlspecialchars($_SESSION['user_role']) ?></div>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Connexion
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="pt-20"></div> <!-- Pour éviter que le contenu soit caché sous la navbar -->

<script>
function updateDateTime() {
    var now = new Date();
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    var dateTimeString = now.toLocaleDateString('fr-FR', options);
    document.getElementById('datetime').textContent = dateTimeString;
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
