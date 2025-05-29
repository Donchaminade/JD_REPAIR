<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

$error = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_name'] = $user['nom_complet'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur de connexion à la base de données.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Connexion Admin</h2>
        <?php if ($error): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="post" class="space-y-5">
            <div>
                <label for="email" class="block text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div>
                <label for="password" class="block text-gray-700 mb-1">Mot de passe</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Se connecter</button>
        </form>
        <a href="#" onclick="openResetModal()" class="text-sm text-blue-600 hover:underline">Mot de passe oublié ?</a>
    </div>

    <div id="resetModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-md shadow-lg relative">
        <button onclick="closeResetModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center">Réinitialiser le Mot de Passe</h2>
        <form id="resetForm" method="POST" action="" class="space-y-4">
            <div>
                <label for="reset_nom_complet" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom Complet</label>
                <input type="text" name="reset_nom_complet" id="reset_nom_complet" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="reset_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="reset_email" id="reset_email" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="reset_new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nouveau Mot de Passe</label>
                <input type="password" name="reset_new_password" id="reset_new_password" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="reset_confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmer le Nouveau Mot de Passe</label>
                <input type="password" name="reset_confirm_password" id="reset_confirm_password" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div class="text-center pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md">Réinitialiser</button>
            </div>
        </form>
        <div id="reset_message" class="text-sm mt-2 text-center"></div>
    </div>
</div>

<script>
    function openResetModal() {
        document.getElementById('resetModal').classList.remove('hidden');
    }

    function closeResetModal() {
        document.getElementById('resetModal').classList.add('hidden');
    }

        document.querySelector('#resetForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêcher la soumission normale du formulaire

    const formData = new FormData(this);

    fetch('reset_password.php', { // Le même fichier PHP que précédemment
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('reset_message').textContent = data.message;
        if (data.success) {
            // Réinitialiser le formulaire si la réinitialisation réussit
            document.getElementById('resetForm').reset();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('reset_message').textContent = 'Erreur de communication avec le serveur.';
    });
});
</script>
</body>
</html>
