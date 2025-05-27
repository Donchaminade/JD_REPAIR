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
                                <button onclick='openDetailsModal(<?= json_encode($reparation) ?>)' class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-eye"></i> Voir
                                </button>
                                <?php if ($reparation['statut'] === 'Prêt à récupérer'): ?>
                                    <button onclick="openFactureModal(<?= htmlspecialchars(json_encode($reparation)) ?>)" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 text-xs flex items-center gap-1">
                                        <i class="fa fa-file-text"></i> Facturer
                                    </button>
                                <?php endif; ?>
                                <button onclick="confirmDelete(<?= $reparation['id_reparation'] ?>)" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i class="fa fa-trash"></i> Supp
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- modal detal -->
    <div id="detailModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-3xl p-8 relative">
        <button onclick="closeDetailsModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i class="fa fa-times w-6 h-6"></i>
        </button>
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Détails de la réparation</h2>
        <div id="detailContent" class="space-y-4 text-base text-gray-700 dark:text-white divide-y divide-gray-300 dark:divide-gray-600">
        </div>
    </div>
</div>
 <!-- ---------------------- -->


<!-- modal facture -->
 <!-- Moodal de facture -->
 <div id="factureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full relative">
        <button onclick="closeFactureModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
            <i class="fa fa-times w-6 h-6"></i>
        </button>
        <h2 class="text-xl font-bold mb-4 text-indigo-600">Informations de la facture</h2>
        <form id="factureForm" action="save_facture.php" method="POST" class="space-y-4">
            <input type="hidden" name="id_reparation" id="facture_id_reparation_traitement">
            <input type="hidden" name="from_traitement" value="true">
            <div>
                <label for="facture_nom_demandeur_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom du demandeur:</label>
                <input type="text" id="facture_nom_demandeur_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" readonly>
            </div>

            <div>
                <label for="facture_technicien_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Technicien:</label>
                <input type="text" id="facture_technicien_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" readonly>
            </div>

            <div>
                <label for="facture_date_reception_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date de Réception:</label>
                <input type="text" id="facture_date_reception_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" readonly>
            </div>

            <div>
                <label for="facture_montant_total_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Montant Total (FCFA):</label>
                <input type="text" id="facture_montant_total_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" readonly>
                <input type="hidden" name="facture_montant_total" id="facture_montant_total_hidden_traitement">
            </div>

            <div>
                <label for="facture_montant_regle_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Montant Réglé (FCFA):</label>
                <input type="number" step="0.01" name="montant_regle" id="facture_montant_regle_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <div>
                <label for="facture_date_facture_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date de la Facture:</label>
                <input type="date" name="date_facture" id="facture_date_facture_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <div>
                <label for="facture_details_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Détails supplémentaires:</label>
                <textarea name="details" id="facture_details_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200"></textarea>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeFactureModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Sauvegarder la Facture
                </button>
            </div>
        </form>
    </div>
</div>
 <!-- ---------------------- -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();



    // Ouvrir le modal de détails
        function openDetailsModal(data) {
        const detailModal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = `
            <p><strong>ID Réparation : </strong> ${data.id_reparation}</p>
            <p><strong>Nom du Demandeur : </strong> ${data.nom_demandeur}</p>
            <p><strong>Technicien : </strong> ${data.nom_technicien || 'Non assigné'}</p>
            <p><strong>Date Réparation : </strong> ${data.date_reparation}</p>
            <p><strong>Montant Total : </strong> ${data.montant_total} FCFA</p>
            <p><strong>Montant Payé : </strong> ${data.montant_paye} FCFA</p>
            <p><strong>Reste à Payer : </strong> ${data.reste_a_payer} FCFA</p>
            <p><strong>Statut : </strong> ${data.statut}</p>
            `;
        detailModal.classList.remove('hidden');
        detailModal.classList.add('flex');
    }

    function closeDetailsModal() {
        const detailModal = document.getElementById('detailModal');
        if (detailModal) {
            detailModal.classList.add('hidden');
            detailModal.classList.remove('flex');
        }
    }


        // modal de facture
            function openFactureModal(data) {
        const factureId = document.getElementById('facture_id_reparation_traitement');
        const factureNom = document.getElementById('facture_nom_demandeur_traitement');
        const factureTech = document.getElementById('facture_technicien_traitement');
        const factureDateRec = document.getElementById('facture_date_reception_traitement');
        const factureMontantTotal = document.getElementById('facture_montant_total_traitement');
        const factureMontantTotalHidden = document.getElementById('facture_montant_total_hidden_traitement');
        const factureMontantRegle = document.getElementById('facture_montant_regle_traitement');
        const factureDateFacture = document.getElementById('facture_date_facture_traitement');
        const factureDetails = document.getElementById('facture_details_traitement');

        if (factureId) factureId.value = data.id_traitement || ''; // Utilisez l'ID du traitement ici
        if (factureNom) factureNom.value = data.nom_demandeur || '';
        if (factureTech) factureTech.value = data.nom_technicien || 'Non assigné';
        if (factureDateRec) factureDateRec.value = data.date_reception || '';
        if (factureMontantTotal) factureMontantTotal.value = data.montant_traitement || '';
        if (factureMontantTotalHidden) factureMontantTotalHidden.value = data.montant_traitement || '';
        if (factureMontantRegle) factureMontantRegle.value = '';
        if (factureDateFacture) factureDateFacture.value = new Date().toISOString().split('T')[0];
        if (factureDetails) factureDetails.value = '';

        const factureModal = document.getElementById("factureModal");
        if (factureModal) {
            factureModal.classList.remove("hidden");
            factureModal.classList.add("flex");
        }
    }

    function closeFactureModal() {
        const factureModal = document.getElementById("factureModal");
        if (factureModal) {
            factureModal.classList.add("hidden");
            factureModal.classList.remove("flex");
        }
    }
    // ---------------------------------------

    

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