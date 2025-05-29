<?php
 // Assurez-vous que la session est démarrée (si ce n'est pas déjà fait)
 if (session_status() == PHP_SESSION_NONE) {
  session_start();
 }
 ?>
 
 <nav class="bg-gray-800 py-4">
  <div class="container mx-auto flex items-center justify-between">
  
  <div class="text-white font-bold text-xl">
  <a href="dashboard.php"> Mon Application
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
  <a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
  Déconnexion
  </a>
  <?php else: ?>
  <a href="index.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
  Connexion
  </a>
  <?php endif; ?>
  </div>
  </div>
 </nav>
 
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