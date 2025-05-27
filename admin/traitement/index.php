<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

// Récupérer les données de la table 'traitement' avec les noms correspondants
$stmt = $pdo->prepare("
    SELECT
        t.id_traitement,
        d.id_demande,
        d.nom_complet AS nom_demandeur,
        t.date_reception,
        t.montant_total AS montant_traitement,
        t.montant_paye AS montant_paye_traitement,
        t.type_reparation,
        u.id_utilisateur AS id_technicien,
        u.nom_complet AS nom_technicien
    FROM traitement t
    JOIN demande_reparation d ON t.id_demande = d.id_demande
    LEFT JOIN utilisateurs u ON t.id_technicien = u.id_utilisateur
    ORDER BY t.date_reception DESC
");
$stmt->execute();
$traitements = $stmt->fetchAll();

// Récupérer les ID des traitements déjà enregistrés dans la table 'reparation'
$stmtReparationIds = $pdo->prepare("SELECT id_traitement FROM reparation");
$stmtReparationIds->execute();
$reparationTraites = $stmtReparationIds->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enregistrer_reparation'])) {
    $id_traitement_reparation = $_POST['id_traitement_reparation'] ?? null;
    $id_demande_reparation = $_POST['id_demande_reparation'] ?? null;
    $date_reparation = $_POST['date_reparation'] ?? date('Y-m-d');
    $montant_total_reparation = $_POST['montant_total_reparation'] ?? null;
    $montant_paye_reparation = $_POST['montant_paye_reparation'] ?? null;
    $reste_a_payer_reparation = $_POST['reste_a_payer_reparation'] ?? null;
    $statut_reparation = $_POST['statut_reparation'] ?? 'En cours';

    if ($id_traitement_reparation && $id_demande_reparation && $montant_total_reparation !== null && $montant_paye_reparation !== null && $reste_a_payer_reparation !== null) {
        try {
            $sql = "INSERT INTO reparation (id_demande, id_traitement, date_reparation, montant_total, montant_paye, reste_a_payer, statut)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtInsertReparation = $pdo->prepare($sql);
            $stmtInsertReparation->execute([$id_demande_reparation, $id_traitement_reparation, $date_reparation, $montant_total_reparation, $montant_paye_reparation, $reste_a_payer_reparation, $statut_reparation]);
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de l'enregistrement de la réparation : " . $e->getMessage();
        }
    } else {
        echo "Erreur : Veuillez remplir tous les champs pour enregistrer la réparation.";
    }
}
?>

<script src="https://unpkg.com/lucide@latest"></script>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">Liste des Traitements</h2>

        <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
            <form id="searchForm" class="flex items-center">
                <input type="text" id="searchInput" placeholder="Rechercher..." class="px-4 py-2 bg-gray-200 text-gray-800 border border-gray-300 rounded-md" onkeyup="searchTable()" />
                <button type="button" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </form>
            <div class="flex gap-2">
                <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">📄 Export PDF</button>
                <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">📊 Export Excel</button>
            </div>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg">
            <table id="traitementTable" class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3">ID Traitement</th>
                        <th class="px-6 py-3">Nom du Demandeur</th>
                        <th class="px-6 py-3">Date de Réception</th>
                        <th class="px-6 py-3">Montant Total</th>
                        <th class="px-6 py-3">Montant Payé</th>
                        <th class="px-6 py-3">Type de Réparation</th>
                        <th class="px-6 py-3">Technicien</th>
                        <th class="px-6 py-3 no-export">Actions</th>
                    </tr>
                </thead>
                <tbody id="traitementsTableBody">
                    <?php foreach ($traitements as $traitement): ?>
                        <?php $isRepare = in_array($traitement['id_traitement'], $reparationTraites); ?>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4"><?= htmlspecialchars($traitement['id_traitement']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($traitement['nom_demandeur']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($traitement['date_reception']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($traitement['montant_traitement']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($traitement['montant_paye_traitement']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($traitement['type_reparation']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($traitement['nom_technicien'] ?: 'Non assigné') ?></td>
                            <td class="px-6 py-4 flex gap-2 no-export">
                                <button
                                    onclick="openReparationModal(<?= htmlspecialchars(json_encode($traitement)) ?>)"
                                    class="<?= $isRepare ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' ?> text-white px-2 py-1 rounded text-xs flex items-center gap-1"
                                    <?= $isRepare ? 'disabled' : '' ?>>
                                    <i class="fa-solid fa-screwdriver-wrench"></i> <?= $isRepare ? 'Réparé' : 'Réparer' ?>
                                </button>
                                <button onclick='openModal(<?= json_encode($demande) ?>)' class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-eye "></i> détails
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


        <div id="detailModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-3xl p-8 relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
            </button>
            <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Détails du traitement</h2>

            <div id="detailContent" class="space-y-4 text-base text- text-gray-700 dark:text-white divide-y divide-gray-300 dark:divide-gray-600">
            </div>
        </div>
        </div>


<div id="reparationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-md shadow-lg relative">
        <button onclick="closeReparationModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center">Enregistrer la Réparation</h2>

        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="id_traitement_reparation" id="reparation_id_traitement">
            <input type="hidden" name="id_demande_reparation" id="reparation_id_demande">

            <div>
                <label for="date_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Réparation</label>
                <input type="date" name="date_reparation" id="date_reparation" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label for="montant_total_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant Total (FCFA)</label>
                <input type="number" name="montant_total_reparation" id="montant_total_reparation" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label for="montant_paye_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant Payé (FCFA)</label>
                <input type="number" name="montant_paye_reparation" id="montant_paye_reparation" value="0" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label for="reste_a_payer_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reste à Payer (FCFA)</label>
                <input type="number" name="reste_a_payer_reparation" id="reste_a_payer_reparation" class="w-full px-4 py-2 border rounded-md bg-gray-200 dark:bg-gray-700 dark:text-white" readonly>
            </div>

            <div>
                <label for="statut_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                <select name="statut_reparation" id="statut_reparation" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                    <option value="Prêt à récupérer">Prêt à récupérer</option>
                </select>
            </div>

            <div class="text-center pt-4">
                <button type="submit" name="enregistrer_reparation" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md">Enregistrer la Réparation</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>

    
    lucide.createIcons();


            function openModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
            modal.classList.remove('hidden');
            }

            if (window.lucide) {
            window.lucide.replace(); // Recharge les icônes Lucide si utilisées
            }
        }

            function closeModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
            modal.classList.add('hidden');
            }
        }


            function openModal(data) {
        const detailModal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = `
            <p><strong>Nom : </strong> ${data.nom_complet}</p>
            <p><strong>Numéro : </strong> ${data.numero}</p>
            <p><strong>Email : </strong> ${data.email}</p>
            <p><strong>Adresse : </strong> ${data.adresse}</p>
            <p><strong>Marque : </strong> ${data.marque_telephone}</p>
            <p><strong>Problème : </strong> ${data.probleme}</p>
            <p><strong>Date : </strong> ${data.date_demande}</p>
            <p><strong>Type : </strong> ${data.type_reparation}</p>
        `;
        detailModal.classList.remove('hidden');
        if (window.lucide) {
            window.lucide.replace();
        }
    }


    function openReparationModal(traitement) {
        document.getElementById('reparationModal').classList.remove('hidden');
        document.getElementById('reparation_id_traitement').value = traitement.id_traitement;
        document.getElementById('reparation_id_demande').value = traitement.id_demande;
        document.getElementById('montant_total_reparation').value = traitement.montant_traitement || '';
        document.getElementById('montant_paye_reparation').value = traitement.montant_paye_traitement || '0';
        calculerResteAPayerReparation();
    }

    function closeReparationModal() {
        document.getElementById('reparationModal').classList.add('hidden');
    }

    function calculerResteAPayerReparation() {
        const montantTotal = parseFloat(document.getElementById('montant_total_reparation').value) || 0;
        const montantPaye = parseFloat(document.getElementById('montant_paye_reparation').value) || 0;
        document.getElementById('reste_a_payer_reparation').value = (montantTotal - montantPaye).toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const montantTotalReparationInput = document.getElementById('montant_total_reparation');
        const montantPayeReparationInput = document.getElementById('montant_paye_reparation');

        if (montantTotalReparationInput && montantPayeReparationInput) {
            montantTotalReparationInput.addEventListener('input', calculerResteAPayerReparation);
            montantPayeReparationInput.addEventListener('input', calculerResteAPayerReparation);
        }

        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener('keyup', searchTable);
        }
    });

    function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("traitementTable");
        const tbody = table.getElementsByTagName("tbody")[0];
        const rows = tbody.getElementsByTagName("tr");
        const noResultsRow = document.getElementById("noResults");
        let resultsFound = false;

        for (let i = 0; i < rows.length; i++) {
            let shouldDisplay = false;
            const cells = rows[i].getElementsByTagName("td");
            for (let j = 0; j < cells.length - 1; j++) {
                const txtValue = cells[j].textContent || cells[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    shouldDisplay = true;
                    break;
                }
            }
            rows[i].style.display = shouldDisplay ? "" : "none";
            if (shouldDisplay) {
                resultsFound = true;
            }
        }

        // Afficher ou masquer le message "aucun résultat"
        if (!resultsFound) {
            if (!noResultsRow) {
                let newRow = table.insertRow();
                newRow.id = "noResults";
                let cell = newRow.insertCell();
                cell.colSpan = table.rows[0].cells.length;
                cell.textContent = "Aucun résultat trouvé.";
                cell.classList.add("px-6", "py-4", "text-center", "dark:text-gray-300");
            } else {
                document.getElementById("noResults").style.display = "";
            }
        } else {
            const noResultsElement = document.getElementById("noResults");
            if (noResultsElement) {
                noResultsElement.style.display = "none";
            }
        }
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        doc.text("Liste des Traitements", 14, 15);

        const headers = [["ID Traitement", "Nom Demandeur", "Date Réception", "Montant Total", "Montant Payé", "Type Réparation", "Technicien"]];
        const rows = [];
        const table = document.getElementById("traitementTable");
        const tbody = table.getElementsByTagName("tbody")[0];
        const allRows = tbody.getElementsByTagName("tr");

        for (let i = 0; i < allRows.length; i++) {
            if (allRows[i].style.display !== "none" && allRows[i].id !== "noResults") {
                const rowData = [];
                const cells = allRows[i].getElementsByTagName("td");
                for (let j = 0; j < cells.length - 1; j++) {
                    rowData.push(cells[j].textContent.trim());
                }
                rows.push(rowData);
            }
        }

        doc.autoTable({
            head: headers,
            body: rows,
            startY: 25
        });

        doc.save("traitements.pdf");
    }

    function exportToExcel() {
        const wb = XLSX.utils.book_new();
        const ws_data = [["ID Traitement", "Nom Demandeur", "Date Réception", "Montant Total", "Montant Payé", "Type Réparation", "Technicien"]];
        const table = document.getElementById("traitementTable");
        const tbody = table.getElementsByTagName("tbody")[0];
        const allRows = tbody.getElementsByTagName("tr");

        for (let i = 0; i < allRows.length; i++) {
            if (allRows[i].style.display !== "none" && allRows[i].id !== "noResults") {
                const rowData = [];
                const cells = allRows[i].getElementsByTagName("td");
                for (let j = 0; j < cells.length - 1; j++) {
                    rowData.push(cells[j].textContent.trim());
                }
                ws_data.push(rowData);
            }
        }

        const ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Traitements");
        XLSX.writeFile(wb, "traitements.xlsx");
    }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>