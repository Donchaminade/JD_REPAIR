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
                                    <i data-lucide="eye" class="w-4 h-4"></i> Voir
                                </button>
                                <button onclick="generateFacture(<?= json_encode($reparation) ?>)" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 text-xs flex items-center gap-1">
                                    <i data-lucide="file-text" class="w-4 h-4"></i> Facture
                                </button>
                                <button onclick="openEditModal(<?= json_encode($reparation) ?>)" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-xs flex items-center gap-1">
                                    <i data-lucide="edit" class="w-4 h-4"></i> Mod
                                </button>
                                <button onclick="confirmDelete(<?= $reparation['id_reparation'] ?>)" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i data-lucide="trash" class="w-4 h-4"></i> Supp
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
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        <h2 class="text-xl font-bold mb-4 text-blue-600">Détails de la réparation</h2>
        <div id="modalContent" class="space-y-2 text-sm text-gray-800 dark:text-gray-200">
            </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-xl w-full relative">
        <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        <h2 class="text-xl font-bold mb-4 text-yellow-600">Modifier la réparation</h2>
        <form id="editForm" action="update_reparation.php" method="POST" class="space-y-4">
            <input type="hidden" name="id_reparation" id="edit_id_reparation">

            <div>
                <label for="edit_date_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date réparation:</label>
                <input type="date" name="date_reparation" id="edit_date_reparation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <div>
                 <label for="edit_montant_total" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Montant Total:</label>
                <input type="number" name="montant_total" id="edit_montant_total" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <div>
                <label for="edit_montant_paye" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Montant Payé:</label>
                <input type="number" name="montant_paye" id="edit_montant_paye" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <div>
                <label for="edit_statut" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Statut:</label>
                <select name="statut" id="edit_statut" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" required>
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                    <option value="Prêt à récupérer">Prêt à récupérer</option>
                </select>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id_reparation').value = data.id_reparation;
    document.getElementById('edit_date_reparation').value = data.date_reparation;
    document.getElementById('edit_montant_total').value = data.montant_total;
    document.getElementById('edit_montant_paye').value = data.montant_paye;
    document.getElementById('edit_statut').value = data.statut;

    document.getElementById("editModal").classList.remove("hidden");
    document.getElementById("editModal").classList.add("flex");
}

function closeEditModal() {
    document.getElementById("editModal").classList.add("hidden");
    document.getElementById("editModal").classList.remove("flex");
}
</script>

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

        document.querySelectorAll('#reparationTable thead tr th:not(.no-export)').forEach(th => {
            headers.push(th.innerText);
        });

        document.querySelectorAll('#reparationTable tbody tr').forEach(row => {
            const rowData = [];
            row.querySelectorAll('td:not(.no-export)').forEach((td, index) => {
                if (index < headers.length) rowData.push(td.innerText);
            });
            rows.push(rowData);
        });

        doc.autoTable({
            head: [headers],
            body: rows,
            styles: { fontSize: 8 },
            startY: 20
        });

        doc.save("reparations.pdf");
    }

    function exportToExcel() {
        const table = document.getElementById("reparationTable");
        const clone = table.cloneNode(true);
        clone.querySelectorAll('.no-export').forEach(el => el.remove());

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
        document.getElementById("modalContent").innerHTML = content;
        document.getElementById("detailModal").classList.remove("hidden");
        document.getElementById("detailModal").classList.add("flex");
    }

    function closeModal() {
        document.getElementById("detailModal").classList.add("hidden");
        document.getElementById("detailModal").classList.remove("flex");
    }

    function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("reparationTable");
        const tbody = table.getElementsByTagName("tbody")[0];
        const rows = tbody.getElementsByTagName("tr");
        let found = false;
        const searchColumnIndex = 1; // Index de la colonne Technicien

        for (let i = 0; i < rows.length; i++) {
            const cell = rows[i].getElementsByTagName("td")[searchColumnIndex];
            if (cell) {
                const txtValue = cell.textContent || cell.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                    found = true;
                } else {
                    rows[i].style.display = "none";
                }
            } else {
                rows[i].style.display = "none";
            }
        }

        // Gérer l'affichage du message "aucun résultat"
        const noResultsRow = document.getElementById("noResults");
        if (!found) {
            if (!noResultsRow) {
                const newRow = table.insertRow();
                newRow.id = "noResults";
                const cell = newRow.insertCell();
                cell.colSpan = table.rows[0].cells.length;
                cell.textContent = "Aucun résultat trouvé pour ce technicien.";
                cell.classList.add("px-6", "py-4", "text-center", "dark:text-gray-300");
            } else {
                noResultsRow.style.display = "";
            }
        } else {
            if (noResultsRow) {
                noResultsRow.style.display = "none";
            }
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
            window.location.href = 'delete_reparation.php?id_reparation=' + id;
        }
    }

function openEditModal(data) {
    document.getElementById('edit_id_reparation').value = data.id_reparation;
    document.getElementById('edit_date_reparation').value = data.date_reparation;
    document.getElementById('edit_montant_total').value = data.montant_total;
    document.getElementById('edit_montant_paye').value = data.montant_paye;
    document.getElementById('edit_statut').value = data.statut;

    const editModal = document.getElementById('editModal');
    editModal.showModal(); // Utilisez showModal() pour afficher le dialog modal
}

function closeEditModal() {
    const editModal = document.getElementById('editModal');
    editModal.close(); // Utilisez close() pour fermer le dialog
}

    function confirmUpdate() {
        if (confirm("Voulez-vous vraiment valider les modifications ?")) {
            document.getElementById('editForm').submit();
        }
    }

    function generateFacture(data) {
        document.getElementById('facture_id_reparation').value = data.id_reparation;
        document.getElementById('facture_nom_demandeur').value = data.nom_demandeur;
        document.getElementById('facture_technicien').value = data.nom_technicien || 'Non assigné';
        document.getElementById('facture_date_reparation').value = data.date_reparation;
        document.getElementById('facture_montant_total').value = data.montant_total;
        document.getElementById('facture_montant_paye').value = data.montant_paye;
        document.getElementById('facture_reste_a_payer').value = data.reste_a_payer;

        document.getElementById("factureModal").classList.remove("hidden");
        document.getElementById("factureModal").classList.add("flex");
    }

    function closeFactureModal() {
        document.getElementById("factureModal").classList.add("hidden");
        document.getElementById("factureModal").classList.remove("flex");
    }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>