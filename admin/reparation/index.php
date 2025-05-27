<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

$stmt = $pdo->prepare("
        SELECT
            r.*,
            dr.nom_complet AS nom_demandeur,
            u.nom_complet AS nom_technicien
        FROM reparation r
        INNER JOIN demande_reparation dr ON r.id_demande = dr.id_demande
        INNER JOIN traitement t ON r.id_traitement = t.id_traitement
        LEFT JOIN utilisateurs u ON t.id_technicien = u.id_utilisateur
        ORDER BY r.date_reparation DESC
");
$stmt->execute();
$reparations = $stmt->fetchAll();
?>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <div class="container mx-auto py-6 px-4">
        <div class="flex flex-col md:flex-row justify-between mb-4 gap-4 ">
            <a href="create.php" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow">
                + Ajouter une réparation
            </a>

            <form id="searchForm" class="flex flex-col sm:flex-row gap-2 items-center">
                <input type="text" id="searchInput" placeholder="Rechercher par technicien..." class="px-4 py-2 bg-gray-200 text-gray-800 border border-gray-300 rounded-md" onkeyup="searchTable()" />
                <button type="button" class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i data-lucide="search" class="w-4 h-4"></i> Rechercher
                </button>
            </form>
        </div>

        <div class="mb-4 flex gap-4">
            <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">📄 Export PDF</button>
            <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">📊 Export Excel</button>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg">
            <table id="reparationTable" class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3">Nom du Demandeur</th>
                        <th class="px-6 py-3">Technicien</th>
                        <th class="px-6 py-3">Date réparation</th>
                        <th class="px-6 py-3">Montant Total</th>
                        <th class="px-6 py-3">Montant Payé</th>
                        <th class="px-6 py-3">Reste à Payer</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3 no-export">Actions</th>
                    </tr>
                </thead>
                <tbody id="reparationsTable">
                    <?php foreach ($reparations as $reparation): ?>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4"><?= htmlspecialchars($reparation['nom_demandeur']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($reparation['nom_technicien'] ?: 'Non assigné') ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($reparation['date_reparation']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($reparation['montant_total']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($reparation['montant_paye']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($reparation['reste_a_payer']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($reparation['statut']) ?></td>
                            <td class="px-6 py-4 flex gap-2 no-export">
                                <button onclick='openModal(<?= json_encode($reparation) ?>)' class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-eye"></i> Voir
                                </button>
                                <button onclick="confirmDelete(<?= $reparation['id_reparation'] ?>)" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i  class="fa-solid fa-trash"></i> Supp
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-xl w-full relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
            <i data-lucide="x" class="w-6 h-6">❌</i>
        </button>
        <h2 class="text-xl font-bold mb-4 text-blue-600">Détails de la réparation</h2>
        <div id="modalContent" class="space-y-2 text-sm text-gray-800 dark:text-gray-200">
            </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text("Liste des réparations", 14, 10);

        const headers = [];
        const rows = [];

        // Récupérer les en-têtes sans les colonnes marquées no-export
        document.querySelectorAll('#reparationTable thead tr th:not(.no-export)').forEach(th => {
            headers.push(th.innerText.trim());
        });

        // Récupérer les données des lignes sans les colonnes no-export
        document.querySelectorAll('#reparationTable tbody tr').forEach(row => {
            const rowData = [];
            row.querySelectorAll('td:not(.no-export)').forEach((td, index) => {
                if (index < headers.length) {
                    rowData.push(td.innerText.trim());
                }
            });
            rows.push(rowData);
        });

        // Générer la table PDF avec autoTable
        doc.autoTable({
            head: [headers],
            body: rows,
            styles: { fontSize: 8 },
            startY: 20,
        });

        doc.save("reparations.pdf");
    }

    function exportToExcel() {
        const table = document.getElementById("reparationTable");
        if (!table) return;

        const clone = table.cloneNode(true);

        // Supprimer les colonnes à ne pas exporter
        clone.querySelectorAll('.no-export').forEach(el => el.remove());

        // Générer le fichier Excel
        const wb = XLSX.utils.table_to_book(clone, { sheet: "Reparations" });
        XLSX.writeFile(wb, "reparations.xlsx");
    }

    function openModal(data) {
        const content = `
            <p><strong>Nom du Demandeur :</strong> ${data.nom_demandeur}</p>
            <p><strong>Technicien :</strong> ${data.nom_technicien || 'Non assigné'}</p>
            <p><strong>Date réparation :</strong> ${data.date_reparation}</p>
            <p><strong>Montant Total :</strong> ${data.montant_total}</p>
            <p><strong>Montant Payé :</strong> ${data.montant_paye}</p>
            <p><strong>Reste à Payer :</strong> ${data.reste_a_payer}</p>
            <p><strong>Statut :</strong> ${data.statut}</p>
        `;
        const modalContent = document.getElementById("modalContent");
        if (modalContent) {
            modalContent.innerHTML = content;
        }

        const detailModal = document.getElementById("detailModal");
        if (detailModal) {
            detailModal.classList.remove("hidden");
            detailModal.classList.add("flex");
        }
    }

    function closeModal() {
        const detailModal = document.getElementById("detailModal");
        if (detailModal) {
            detailModal.classList.add("hidden");
            detailModal.classList.remove("flex");
        }
    }

    function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("reparationTable");
        if (!table) return;

        const tbody = table.tBodies[0];
        const rows = tbody ? tbody.rows : [];
        const searchColumnIndex = 1; // colonne Technicien
        let foundAny = false;

        // Supprimer l'ancienne ligne "Aucun résultat"
        const existingNoResult = document.getElementById("noResults");
        if (existingNoResult) {
            existingNoResult.remove();
        }

        for (let i = 0; i < rows.length; i++) {
            const cell = rows[i].cells[searchColumnIndex];
            if (cell) {
                const txtValue = cell.textContent || cell.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                    foundAny = true;
                } else {
                    rows[i].style.display = "none";
                }
            } else {
                rows[i].style.display = "none";
            }
        }

        if (!foundAny) {
            const newRow = tbody.insertRow();
            newRow.id = "noResults";
            const cell = newRow.insertCell();
            cell.colSpan = table.tHead.rows[0].cells.length;
            cell.textContent = "Aucun résultat trouvé pour ce technicien.";
            cell.classList.add("px-6", "py-4", "text-center", "dark:text-gray-300");
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener('keyup', searchTable);
        }
    });

    function confirmDelete(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette réparation ?")) {
            window.location.href = 'delete_reparation.php?id_reparation=' + encodeURIComponent(id);
        }
    }
</script>

    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>