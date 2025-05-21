<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

// Traitement de la mise à jour (avant toute sortie)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_demande = $_POST['id_demande'];
    $nom_complet = $_POST['nom_complet'];
    $numero = $_POST['numero'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $marque_telephone = $_POST['marque_telephone'];
    $probleme = $_POST['probleme'];
    $date_demande = $_POST['date_demande'];
    $type_reparation = $_POST['type_reparation'];

    $stmt = $pdo->prepare("UPDATE demande_reparation SET nom_complet=?, numero=?, email=?, adresse=?, marque_telephone=?, probleme=?, date_demande=?, type_reparation=? WHERE id_demande=?");
    $stmt->execute([$nom_complet, $numero, $email, $adresse, $marque_telephone, $probleme, $date_demande, $type_reparation, $id_demande]);

    header("Location: index.php");
    exit();
}

// Récupération des infos de la demande
$id_demande = $_GET['id_demande'];
$stmt = $pdo->prepare("SELECT * FROM demande_reparation WHERE id_demande = ?");
$stmt->execute([$id_demande]);
$demande = $stmt->fetch();

include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';
?>

<style>
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .animated-form > * {
    animation: fadeInUp 0.5s ease-out;
  }

  <?php for ($i = 1; $i <= 9; $i++): ?>
  .animated-form > *:nth-child(<?= $i ?>) { animation-delay: <?= 0.1 * $i ?>s; }
  <?php endfor; ?>
</style>

        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
<div class="container mx-auto py-10 px-6 max-w-3xl bg-white dark:bg-gray-900 rounded-xl shadow-xl transition-all duration-500">
    
    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center animate__animated animate__fadeInDown">
        ✏️ Modifier la Demande de Réparation
    </h2>

    <form method="POST" class="space-y-4 animated-form">
        <input type="hidden" name="id_demande" value="<?= htmlspecialchars($demande['id_demande']) ?>">

        <div>
            <label for="nom_complet" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom complet</label>
            <input type="text" name="nom_complet" value="<?= htmlspecialchars($demande['nom_complet']) ?>" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="numero" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro</label>
            <input type="text" name="numero" value="<?= htmlspecialchars($demande['numero']) ?>" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($demande['email']) ?>" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="adresse" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse</label>
            <input type="text" name="adresse" value="<?= htmlspecialchars($demande['adresse']) ?>" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="marque_telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marque du téléphone</label>
            <input type="text" name="marque_telephone" value="<?= htmlspecialchars($demande['marque_telephone']) ?>" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="probleme" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Problème</label>
            <textarea name="probleme" required rows="3" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($demande['probleme']) ?></textarea>
        </div>

        <div>
            <label for="date_demande" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de la demande</label>
            <input type="date" name="date_demande" value="<?= htmlspecialchars($demande['date_demande']) ?>" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="type_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type de réparation</label>
            <select name="type_reparation" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="express" <?= $demande['type_reparation'] === 'express' ? 'selected' : '' ?>>Express</option>
                <option value="standard" <?= $demande['type_reparation'] === 'standard' ? 'selected' : '' ?>>Standard</option>
            </select>
        </div>

        <div class="text-center pt-6">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-700 hover:from-yellow-600 hover:to-yellow-800 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition duration-300 ease-in-out">
                💾 Mettre à jour
            </button>
        </div>
    </form>
</div>


<script>
    function closeModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
            modal.classList.add('hidden');
            }
        }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>
