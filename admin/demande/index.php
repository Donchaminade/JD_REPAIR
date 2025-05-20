<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

$stmt = $pdo->prepare("SELECT * FROM demande_reparation ORDER BY date_demande DESC");
$stmt->execute();
$demande_reparations = $stmt->fetchAll();

// Récupérer les noms des colonnes pour le formulaire d'ajout
$stmtColumns = $pdo->prepare("DESCRIBE demande_reparation");
$stmtColumns->execute();
$columnsData = $stmtColumns->fetchAll(PDO::FETCH_COLUMN);
$columnsForForm = array_filter($columnsData, function($column) {
    return $column !== 'id_demande'; // Ne pas inclure l'ID pour l'ajout
});

// Gestion de l'ajout de la demande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_demande'])) {
    $fields = [];
    $placeholders = [];
    $values = [];
    foreach ($columnsForForm as $column) {
        if (isset($_POST[$column])) {
            $fields[] = $column;
            $placeholders[] = '?';
            $values[] = $_POST[$column];
        }
    }
    if (!empty($fields)) {
        $sql = "INSERT INTO demande_reparation (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $insertStmt = $pdo->prepare($sql);
        $insertStmt->execute($values);
        header("Location: index.php"); // Redirection après l'ajout
        exit();
    }
}
?>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <div class="container mx-auto py-6 px-4">
        <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
            <a href="#addModal" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow">
                + Ajouter une demande
            </a>

            <form id="searchForm" class="flex flex-col sm:flex-row gap-2 items-center">
                <input type="text" id="searchInput" placeholder="Rechercher par nom..." class="px-4 py-2 bg-gray-200 text-gray-800 border border-gray-300 rounded-md" />
                <select id="filterSelect" class="px-4 py-2 bg-gray-200 text-gray-800 border border-gray-300 rounded-md">
                    <option value="">Filtrer par type</option>
                    <option value="express">Express</option>
                    <option value="standard">Standard</option>
                </select>
                <button type="submit" class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fa fa-search"></i> Rechercher
                </button>
            </form>
        </div>

        <div class="mb-4 flex gap-4">
            <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">📄 Export PDF</button>
            <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">📊 Export Excel</button>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg">
            <table id="demandeTable" class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3">Nom complet</th>
                        <th class="px-6 py-3">Numéro</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Adresse</th>
                        <th class="px-6 py-3">Marque</th>
                        <th class="px-6 py-3">Problème</th>
                        <th class="px-6 py-3">Date de demande</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3 no-export">Actions</th>
                    </tr>
                </thead>
                <tbody id="demandesTable">
                    <?php foreach ($demande_reparations as $demande): ?>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['nom_complet']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['numero']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['email']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['adresse']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['marque_telephone']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['probleme']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['date_demande']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($demande['type_reparation']) ?></td>
                            <td class="px-6 py-4 flex gap-2 no-export">
                                <button onclick='openModal(<?= json_encode($demande) ?>)' class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                    <i class="fa fa-eye"></i> Voir détails
                                </button>
                                <a href="update.php?id_demande=<?= $demande['id_demande'] ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-xs flex items-center gap-1">
                                    <i class="fa fa-pen"></i> Modifier
                                </a>
                                <a href="delete.php?id_demande=<?= $demande['id_demande'] ?>" onclick="return confirm('Supprimer cette demande ?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i class="fa fa-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addModal" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-50 items-center justify-center">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <a href="#" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Fermer la modal</span>
            </a>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Ajouter une nouvelle demande</h3>
                <form class="space-y-6" method="POST" action="">
                    <?php foreach ($columnsForForm as $column): ?>
                        <div>
                            <label for="<?= $column ?>" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars(str_replace('_', ' ', ucfirst($column))) ?></label>
                            <?php if ($column === 'type_reparation'): ?>
                                <select name="<?= $column ?>" id="<?= $column ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="" selected>Sélectionner le type</option>
                                    <option value="express">Express</option>
                                    <option value="standard">Standard</option>
                                </select>
                            <?php elseif ($column === 'date_demande'): ?>
                                <input type="date" name="<?= $column ?>" id="<?= $column ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <?php elseif ($column === 'probleme'): ?>
                                <textarea name="<?= $column ?>" id="<?= $column ?>" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="<?= htmlspecialchars(str_replace('_', ' ', ucfirst($column))) ?>" required></textarea>
                            <?php else: ?>
                                <input type="text" name="<?= $column ?>" id="<?= $column ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="<?= htmlspecialchars(str_replace('_', ' ', ucfirst($column))) ?>" required>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="ajouter_demande" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ajouter</button>
                    <a href="#" class="inline-block w-full text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 text-center dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-xl w-full relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl"><i class="fa fa-times"></i></button>
        <h2 class="text-xl font-bold mb-4 text-blue-600">Détails de la demande</h2>
        <div id="modalContent" class="space-y-2 text-sm text-gray-800 dark:text-gray-200">
            </div>
    </div>
</div>

<style>
#addModal:target {
    display: flex !important;
}

#addModal {
    display: none; /* Initialement caché */
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>