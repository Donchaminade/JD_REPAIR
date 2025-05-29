<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/auth.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
// include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php';
// include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

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
    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php'; ?>
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
                        <?php
                        // Vérifier si une facture existe pour cette réparation
                        $stmt_facture_existe = $pdo->prepare("SELECT COUNT(*) FROM facture WHERE id_reparation = ?");
                        $stmt_facture_existe->execute([$reparation['id_reparation']]);
                        $facture_existe = $stmt_facture_existe->fetchColumn() > 0;
                        ?>
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
                                <button onclick='openEditModal(<?= json_encode($reparation) ?>)' class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-700 text-xs flex items-center gap-1">
                                    <i class="fa fa-pencil"></i> Modifier
                                </button>
                                <?php if ($facture_existe): ?>
                                    <button class="bg-gray-400 text-white px-2 py-1 rounded cursor-not-allowed text-xs flex items-center gap-1">
                                        <i class="fa fa-file-text"></i> Facturé
                                    </button>
                                <?php elseif ($reparation['statut'] === 'Prêt à récupérer'): ?>
                                    <button onclick="openFactureModal(<?= htmlspecialchars(json_encode($reparation)) ?>)" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 text-xs flex items-center gap-1">
                                        <i class="fa fa-file-text"></i> Facturer
                                    </button>
                                <?php else: ?>
                                    <button class="bg-gray-300 text-gray-500 px-2 py-1 rounded cursor-not-allowed text-xs flex items-center gap-1" disabled>
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


<!-- modal modification -->
<div id="editReparationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" aria-labelledby="modal-title-edit" role="dialog" aria-modal="true">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full relative">
        <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
            <i class="fa fa-times w-6 h-6"></i>
        </button>
        <h2 class="text-xl font-bold mb-4 text-indigo-600">Modifier la Réparation</h2>
        <form id="editReparationForm" action="update.php" method="POST" class="space-y-4">
            <input type="hidden" id="edit_id_reparation" name="id_reparation">
            <div>
                <label for="edit_nom_demandeur" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom du Demandeur:</label>
                <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" id="edit_nom_demandeur" name="nom_demandeur" readonly>
                <small class="text-gray-500 dark:text-gray-400">Le nom du demandeur ne peut pas être modifié ici.</small>
            </div>
            <div>
                <label for="edit_technicien" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Technicien:</label>
                <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" id="edit_technicien" name="nom_technicien" readonly>
                <small class="text-gray-500 dark:text-gray-400">Le technicien ne peut pas être modifié ici.</small>
            </div>
            <div>
                <label for="edit_date_reparation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date de réparation:</label>
                <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" id="edit_date_reparation" name="date_reparation">
            </div>
            <div>
                <label for="edit_montant_total" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Montant Total:</label>
                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" id="edit_montant_total" name="montant_total">
            </div>
            <div>
                <label for="edit_montant_paye" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Montant Payé:</label>
                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" id="edit_montant_paye" name="montant_paye">
            </div>
            <div>
                <label for="edit_reste_a_payer" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Reste à Payer:</label>
                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" id="edit_reste_a_payer" name="reste_a_payer">
            </div>
            <div>
                <label for="edit_statut" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Statut:</label>
                <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200" id="edit_statut" name="statut">
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                    <option value="Prêt à récupérer">Prêt à récupérer</option>
                </select>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(data) {
        const editModal = document.getElementById('editReparationModal');
        const editId = document.getElementById('edit_id_reparation');
        const editNom = document.getElementById('edit_nom_demandeur');
        const editTechnicien = document.getElementById('edit_technicien');
        const editDateReparation = document.getElementById('edit_date_reparation');
        const editMontantTotal = document.getElementById('edit_montant_total');
        const editMontantPaye = document.getElementById('edit_montant_paye');
        const editResteAPayer = document.getElementById('edit_reste_a_payer');
        const editStatut = document.getElementById('edit_statut');

        if (editId) editId.value = data.id_reparation || '';
        if (editNom) editNom.value = data.nom_demandeur || '';
        if (editTechnicien) editTechnicien.value = data.nom_technicien || '';
        if (editDateReparation) editDateReparation.value = data.date_reparation || '';
        if (editMontantTotal) editMontantTotal.value = data.montant_total || '';
        if (editMontantPaye) editMontantPaye.value = data.montant_paye || '';
        if (editResteAPayer) editResteAPayer.value = data.reste_a_payer || '';
        if (editStatut) editStatut.value = data.statut || 'En cours';

        if (editModal) {
            editModal.classList.remove("hidden");
            editModal.classList.add("flex");
        }
    }

    function closeEditModal() {
        const editModal = document.getElementById('editReparationModal');
        if (editModal) {
            editModal.classList.add("hidden");
            editModal.classList.remove("flex");
        }
    }
</script>
 <!-- ---------------- -->



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

            <div>
                <label for="facture_statut_paiement_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Statut Paiement:</label>
                <select name="statut_paiement" id="facture_statut_paiement_traitement" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none focus:ring-2 dark:bg-gray-700 dark:text-gray-200">
                    <option value="Non payée" selected>Non payée</option>
                    <option value="Partiellement payée">Partiellement payée</option>
                    <option value="Payée">Payée</option>
                </select>
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

<script>
    // Fonction pour fermer le modal de facture
    function closeFactureModal() {
        const factureModal = document.getElementById("factureModal");
        if (factureModal) {
            factureModal.classList.add("hidden");
            factureModal.classList.remove("flex");
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
        const factureStatutPaiement = document.getElementById('facture_statut_paiement_traitement');

        if (factureId) factureId.value = data.id_reparation || '';
        if (factureNom) factureNom.value = data.nom_demandeur || '';
        if (factureTech) factureTech.value = data.nom_technicien || 'Non assigné';
        if (factureDateRec) factureDateRec.value = data.date_reparation || '';
        if (factureMontantTotal) factureMontantTotal.value = data.montant_total || '';
        if (factureMontantTotalHidden) factureMontantTotalHidden.value = data.montant_total || '';
        if (factureMontantRegle) factureMontantRegle.value = ''; // Réinitialiser le montant réglé
        if (factureDateFacture) factureDateFacture.value = new Date().toISOString().split('T')[0]; // Date du jour par défaut
        if (factureDetails) factureDetails.value = ''; // Réinitialiser les détails
        if (factureStatutPaiement) factureStatutPaiement.value = 'Non payée'; // Valeur par défaut

        const factureModal = document.getElementById("factureModal");
        if (factureModal) {
            factureModal.classList.remove("hidden");
            factureModal.classList.add("flex");
        }
    }
</script>
 <!-- ---------------------- -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>





<script>
    lucide.createIcons();

    // function openEditModal(data) {
    //     const editModal = document.getElementById('editReparationModal');
    //     const editId = document.getElementById('edit_id_reparation');
    //     const editNom = document.getElementById('edit_nom_demandeur');
    //     const editAppareil = document.getElementById('edit_appareil');
    //     const editPanne = document.getElementById('edit_panne');
    //     const editMontant = document.getElementById('edit_montant_total');
    //     const editStatut = document.getElementById('edit_statut');

    //     if (editId) editId.value = data.id_reparation || '';
    //     if (editNom) editNom.value = data.nom_demandeur || '';
    //     if (editAppareil) editAppareil.value = data.appareil || '';
    //     if (editPanne) editPanne.value = data.panne || '';
    //     if (editMontant) editMontant.value = data.montant_total || '';
    //     if (editStatut) editStatut.value = data.statut || 'En cours';

    //     if (editModal) {
    //         editModal.classList.remove("hidden");
    //         editModal.classList.add("flex");
    //     }
    // }

    // function closeEditModal() {
    //     const editModal = document.getElementById('editReparationModal');
    //     if (editModal) {
    //         editModal.classList.add("hidden");
    //         editModal.classList.remove("flex");
    //     }
    // }

 

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


    // modal de facture anciennne version
    // function openFactureModal(data) {
    //     const factureId = document.getElementById('facture_id_reparation_traitement');
    //     const factureNom = document.getElementById('facture_nom_demandeur_traitement');
    //     const factureTech = document.getElementById('facture_technicien_traitement');
    //     const factureDateRec = document.getElementById('facture_date_reception_traitement');
    //     const factureMontantTotal = document.getElementById('facture_montant_total_traitement');
    //     const factureMontantTotalHidden = document.getElementById('facture_montant_total_hidden_traitement');
    //     const factureMontantRegle = document.getElementById('facture_montant_regle_traitement');
    //     const factureDateFacture = document.getElementById('facture_date_facture_traitement');
    //     const factureDetails = document.getElementById('facture_details_traitement');

    //     if (factureId) factureId.value = data.id_reparation || '';
    //     if (factureNom) factureNom.value = data.nom_demandeur || '';
    //     if (factureTech) factureTech.value = data.nom_technicien || 'Non assigné';
    //     if (factureDateRec) factureDateRec.value = data.date_reparation || '';
    //     if (factureMontantTotal) factureMontantTotal.value = data.montant_total || '';
    //     if (factureMontantTotalHidden) factureMontantTotalHidden.value = data.montant_total || '';
    //     if (factureMontantRegle) factureMontantRegle.value = ''; // Réinitialiser le montant réglé
    //     if (factureDateFacture) factureDateFacture.value = new Date().toISOString().split('T')[0]; // Date du jour par défaut
    //     if (factureDetails) factureDetails.value = ''; // Réinitialiser les détails

    //     const factureModal = document.getElementById("factureModal");
    //     if (factureModal) {
    //         factureModal.classList.remove("hidden");
    //         factureModal.classList.add("flex");
    //     }
    // }

    function confirmDelete(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette réparation ?")) {
            window.location.href = 'delete.php?id_reparation=' + encodeURIComponent(id);
        }
    }


    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const pageWidth = doc.internal.pageSize.getWidth();

        // --- Ajouter le Logo (Centré et taille ajustée) ---
        const imgData = 'jd.png'; // Assurez-vous que le chemin est correct
        const logoWidth = 40;
        const logoHeight = 27;
        const logoX = (pageWidth - logoWidth) / 2;
        const logoY = 5;
        doc.addImage(imgData, 'PNG', logoX, logoY, logoWidth, logoHeight);
        const yAfterLogo = logoY + logoHeight + 5;

        doc.setFontSize(14);
        const title = "Liste des réparations";
        const titleWidth = doc.getTextWidth(title);
        const titleX = (pageWidth - titleWidth) / 2;
        doc.text(title, titleX, yAfterLogo + 7);
        const yAfterTitle = yAfterLogo + 12;

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
            startY: yAfterTitle + 5,
        });

        doc.save("reparations_avec_logo.pdf");
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
</script>


<!-- <script>
    lucide.createIcons();

     function openEditModal(data) {
        const editModal = document.getElementById('editReparationModal');
        const editId = document.getElementById('edit_id_reparation');
        const editNom = document.getElementById('edit_nom_demandeur');
        const editAppareil = document.getElementById('edit_appareil');
        const editPanne = document.getElementById('edit_panne');
        const editMontant = document.getElementById('edit_montant_total');
        const editStatut = document.getElementById('edit_statut');

        if (editId) editId.value = data.id_reparation || '';
        if (editNom) editNom.value = data.nom_demandeur || '';
        if (editAppareil) editAppareil.value = data.appareil || '';
        if (editPanne) editPanne.value = data.panne || '';
        if (editMontant) editMontant.value = data.montant_total || '';
        if (editStatut) editStatut.value = data.statut || 'En cours'; // Sélectionner le statut actuel

        if (editModal) {
            editModal.classList.remove("hidden");
            editModal.classList.add("flex");
        }
    }

    function closeEditModal() {
        const editModal = document.getElementById('editReparationModal');
        if (editModal) {
            editModal.classList.add("hidden");
            editModal.classList.remove("flex");
        }
    }



    // Fonction pour fermer le modal de facture
    function closeFactureModal() {
        const factureModal = document.getElementById("factureModal");
        if (factureModal) {
            factureModal.classList.add("hidden");
            factureModal.classList.remove("flex");
        }
    }

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

            if (factureId) factureId.value = data.id_reparation || '';
            if (factureNom) factureNom.value = data.nom_demandeur || '';
            if (factureTech) factureTech.value = data.nom_technicien || 'Non assigné';
            if (factureDateRec) factureDateRec.value = data.date_reparation || '';
            if (factureMontantTotal) factureMontantTotal.value = data.montant_total || '';
            if (factureMontantTotalHidden) factureMontantTotalHidden.value = data.montant_total || '';
            if (factureMontantRegle) factureMontantRegle.value = ''; // Réinitialiser le montant réglé
            if (factureDateFacture) factureDateFacture.value = new Date().toISOString().split('T')[0]; // Date du jour par défaut
            if (factureDetails) factureDetails.value = ''; // Réinitialiser les détails

            const factureModal = document.getElementById("factureModal");
            if (factureModal) {
                factureModal.classList.remove("hidden");
                factureModal.classList.add("flex");
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
            window.location.href = 'delete.php?id_reparation=' + encodeURIComponent(id);
        }
    }
</script> -->

    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>