<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

$stmt = $pdo->prepare("SELECT * FROM demande_reparation ORDER BY date_demande DESC");
$stmt->execute();
$demande_reparations = $stmt->fetchAll();

$stmtColumns = $pdo->prepare("DESCRIBE demande_reparation");
$stmtColumns->execute();
$columnsData = $stmtColumns->fetchAll(PDO::FETCH_COLUMN);
$columnsForForm = array_filter($columnsData, fn($column) => $column !== 'id_demande');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_demande'])) {
    $fields = [];
    $placeholders = [];
    $values = [];
    foreach ($columnsForForm as $column) {
        if (isset($_POST[$column])) {
            $fields[] = $column;
            $placeholders[] = '?';
            $values[] = htmlspecialchars($_POST[$column]);
        }
    }
    if (!empty($fields)) {
        $sql = "INSERT INTO demande_reparation (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $insertStmt = $pdo->prepare($sql);
        $insertStmt->execute($values);
        header("Location: index.php");
        exit();
    }
}
?>

<!-- Inclure Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <div class="container mx-auto py-6 px-4">
        <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
            <a href="#addModal" onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow">
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
                    <i data-lucide="search" class="w-4 h-4"></i> Rechercher
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
                                    <i data-lucide="eye" class="w-4 h-4"></i> Voir détails
                                </button>
                                <a href="update.php?id_demande=<?= $demande['id_demande'] ?>" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-xs flex items-center gap-1">
                                    <i data-lucide="edit" class="w-4 h-4"></i> Modifier
                                </a>
                                <a href="delete.php?id_demande=<?= $demande['id_demande'] ?>" onclick="return confirm('Supprimer cette demande ?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i data-lucide="trash" class="w-4 h-4"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODALE VOIR DETAILS -->
<!-- MODALE VOIR DETAILS -->
<div id="detailModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
  <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg p-6 relative">
    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 dark:text-white hover:text-red-500">
      <i data-lucide="x" class="w-6 h-6"></i>
    </button>
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 text-center">Détails de la demande</h2>
    <div id="detailContent" class="text-center mx-auto max-w-md text-sm text-gray-700 dark:text-white"></div>
  </div>
</div>
    <script>
    function openModal() {
        const paragraphs = [
        "Premier paragraphe de détails.",
        "Deuxième paragraphe de détails.",
        "Troisième paragraphe de détails."
        ];

        const detailContent = document.getElementById('detailContent');
        detailContent.innerHTML = paragraphs.map(text =>
        `<p class="border-b border-gray-300 last:border-b-0 py-2">${text}</p>`
        ).join('');

        document.getElementById('detailModal').classList.remove('hidden');

        // Recharge les icônes Lucide si présentes
        if (window.lucide) {
        window.lucide.replace();
        }
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
    </script>


<!-- MODALE AJOUT -->
<div id="addModal" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-full bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800 w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('addModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Ajouter une nouvelle demande</h3>
        <form method="POST" action="">
            <?php foreach ($columnsForForm as $column): ?>
                <div class="mb-4">
                    <label for="<?= $column ?>" class="block text-sm font-medium text-gray-700 dark:text-white"><?= ucfirst(str_replace('_', ' ', $column)) ?></label>
                    <?php if ($column === 'type_reparation'): ?>
                        <select name="<?= $column ?>" id="<?= $column ?>" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white">
                            <option value="">Sélectionner</option>
                            <option value="express">Express</option>
                            <option value="standard">Standard</option>
                        </select>
                    <?php elseif ($column === 'date_demande'): ?>
                        <input type="date" name="<?= $column ?>" id="<?= $column ?>" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white" />
                    <?php elseif ($column === 'probleme'): ?>
                        <textarea name="<?= $column ?>" id="<?= $column ?>" rows="3" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white"></textarea>
                    <?php else: ?>
                        <input type="text" name="<?= $column ?>" id="<?= $column ?>" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white" />
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <div class="flex justify-end">
                <button type="submit" name="ajouter_demande" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();

    function openModal(data) {
        const detailModal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = `
            <p><strong>Nom :</strong> ${data.nom_complet}</p>
            <p><strong>Numéro :</strong> ${data.numero}</p>
            <p><strong>Email :</strong> ${data.email}</p>
            <p><strong>Adresse :</strong> ${data.adresse}</p>
            <p><strong>Marque :</strong> ${data.marque_telephone}</p>
            <p><strong>Problème :</strong> ${data.probleme}</p>
            <p><strong>Date :</strong> ${data.date_demande}</p>
            <p><strong>Type :</strong> ${data.type_reparation}</p>
        `;
        detailModal.classList.remove('hidden');
    }

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


<script>
    // Export PDF
    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        doc.text("Liste des demandes de réparation", 14, 15);

        const headers = [["Nom complet", "Numéro", "Email", "Adresse", "Marque", "Problème", "Date", "Type"]];
        const rows = [];

        document.querySelectorAll("#demandesTable tr").forEach(row => {
            const rowData = [];
            row.querySelectorAll("td:not(.no-export)").forEach(cell => {
                rowData.push(cell.textContent.trim());
            });
            if (rowData.length > 0) {
                rows.push(rowData);
            }
        });

        doc.autoTable({
            head: headers,
            body: rows,
            startY: 25
        });

        doc.save("demandes_reparation.pdf");
    }

    // Export Excel
    function exportToExcel() {
        const wb = XLSX.utils.book_new();
        const ws_data = [["Nom complet", "Numéro", "Email", "Adresse", "Marque", "Problème", "Date", "Type"]];

        document.querySelectorAll("#demandesTable tr").forEach(row => {
            const rowData = [];
            row.querySelectorAll("td:not(.no-export)").forEach(cell => {
                rowData.push(cell.textContent.trim());
            });
            if (rowData.length > 0) {
                ws_data.push(rowData);
            }
        });

        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Demandes");
        XLSX.writeFile(wb, "demandes_reparation.xlsx");
    }
</script>


