<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_demande = intval($_POST['id_demande']);
    $date_reception = $_POST['date_reception'];
    $montant_total = floatval($_POST['montant_total']);
    $montant_paye = floatval($_POST['montant_paye']);
    $type_reparation = $_POST['type_reparation'];
    $id_technicien = intval($_POST['id_technicien']);

    $reste_a_payer = $montant_total - $montant_paye;

    $stmt = $pdo->prepare("INSERT INTO traitement (id_demande, date_reception, montant_total, montant_paye, reste_a_payer, type_reparation, id_technicien) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_demande, $date_reception, $montant_total, $montant_paye, $reste_a_payer, $type_reparation, $id_technicien]);

    header("Location: create.php?success=1");
    exit();
}

$selected_demande = isset($_GET['id_demande']) ? intval($_GET['id_demande']) : null;

$stmt = $pdo->prepare("SELECT id_utilisateur, nom_complet FROM utilisateurs WHERE role = 'technicien'");
$stmt->execute();
$techniciens = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT id_demande, nom_complet FROM demande_reparation WHERE id_demande NOT IN (SELECT id_demande FROM traitement)");
$stmt->execute();
$demandes_non_traitees = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT dr.nom_complet FROM demande_reparation dr JOIN traitement t ON dr.id_demande = t.id_demande ORDER BY t.date_reception DESC LIMIT 1");
$stmt->execute();
$client_recent = $stmt->fetch();
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php'; ?>

<!-- Conteneur principal centré -->
<div class="ml-64 min-h-screen bg-gray-100 dark:bg-gray-900 transition-all duration-500 flex justify-center items-start py-10 px-4">
    <div class="w-full max-w-xl p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg animate-fade-in">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white">Ajouter un traitement</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="mb-4 text-green-600 text-center font-semibold">Traitement ajouté avec succès !</div>
        <?php endif; ?>

        <div class="mb-4">
            <strong class="text-sm text-gray-700 dark:text-gray-300">Dernière demande traitée par :</strong>
            <span class="text-gray-800 dark:text-white">
                <?= $client_recent ? htmlspecialchars($client_recent['nom_complet']) : 'Aucun client traité' ?>
            </span>
        </div>

        <form method="POST" action="create.php" class="space-y-4">
            <!-- Sélection d'une demande -->
            <div>
                <label for="id_demande" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Demande à traiter</label>
                <select name="id_demande" id="id_demande" required class="w-full px-4 py-2 border border-gray-600 rounded-md bg-gray-700 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    <option disabled <?= $selected_demande === null ? 'selected' : '' ?>>Choisir une demande</option>
                    <?php foreach ($demandes_non_traitees as $demande): ?>
                        <option value="<?= $demande['id_demande'] ?>" <?= $selected_demande == $demande['id_demande'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($demande['id_demande'] . " - " . $demande['nom_complet']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date de réception -->
            <div>
                <label for="date_reception" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Date de réception</label>
                <input type="date" name="date_reception" id="date_reception" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-600 rounded-md bg-gray-700 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <!-- Montant total -->
            <div>
                <label for="montant_total" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Montant total (FCFA)</label>
                <input type="number" step="0.01" name="montant_total" id="montant_total" required class="w-full px-4 py-2 border border-gray-600 rounded-md bg-gray-700 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <!-- Montant payé -->
            <div>
                <label for="montant_paye" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Montant payé (FCFA)</label>
                <input type="number" step="0.01" name="montant_paye" id="montant_paye" required class="w-full px-4 py-2 border border-gray-600 rounded-md bg-gray-700 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <!-- Type de réparation -->
            <div>
                <label for="type_reparation" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Type de réparation</label>
                <select name="type_reparation" id="type_reparation" class="w-full px-4 py-2 border border-gray-600 rounded-md bg-gray-700 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    <option value="express">Express</option>
                    <option value="standard">Standard</option>
                </select>
            </div>

            <!-- Sélection du technicien -->
            <div>
                <label for="id_technicien" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Confier à un technicien</label>
                <select id="id_technicien" name="id_technicien" required class="w-full px-4 py-2 border border-gray-600 rounded-md bg-gray-700 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    <option disabled selected>Choisir un technicien</option>
                    <?php foreach ($techniciens as $technicien): ?>
                        <option value="<?= $technicien['id_utilisateur'] ?>">
                            <?= htmlspecialchars($technicien['nom_complet']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Boutons -->
            <div class="text-center pt-4 flex justify-center space-x-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md transition duration-300">
                    Ajouter
                </button>
                <a href="../demande/index.php" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md shadow-md transition duration-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Select2 et Animations -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#id_technicien').select2({ placeholder: "Choisir un technicien", allowClear: true });
        $('#id_demande').select2({ placeholder: "Choisir une demande", allowClear: true });
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out forwards;
    }
    
    /* Animation clique sur boutons */
    button:active, a:active {
        transform: scale(0.95) rotate(-1deg);
        transition: transform 0.2s ease-in-out;
    }
</style>
