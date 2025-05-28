<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

$stmt = $pdo->prepare("
    SELECT
        f.*,
        dr.nom_complet AS nom_demandeur
    FROM facture f
    LEFT JOIN reparation r ON f.id_reparation = r.id_reparation
    LEFT JOIN demande_reparation dr ON r.id_demande = dr.id_demande
    ORDER BY f.date_facture DESC
");
$stmt->execute();
$factures = $stmt->fetchAll();
?>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <div class="container mx-auto py-6 px-4">
        <div class="flex flex-col md:flex-row justify-between mb-4 gap-4 ">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Gestion des Factures</h1>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg">
            <table id="factureTable" class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3">ID Facture</th>
                        <th class="px-6 py-3">Nom du Demandeur</th>
                        <th class="px-6 py-3">Date Facture</th>
                        <th class="px-6 py-3">Montant Total</th>
                        <th class="px-6 py-3">Montant Réglé</th>
                        <th class="px-6 py-3">Reste à Payer</th>
                        <th class="px-6 py-3">Statut Paiement</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($factures as $facture): ?>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4"><?= htmlspecialchars($facture['id_facture']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($facture['nom_demandeur'] ?: 'N/A') ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($facture['date_facture']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($facture['montant_total']) ?> FCFA</td>
                            <td class="px-6 py-4"><?= htmlspecialchars($facture['montant_regle']) ?> FCFA</td>
                            <td class="px-6 py-4"><?= htmlspecialchars($facture['reste_a_payer']) ?> FCFA</td>
                            <td class="px-6 py-4"><?= htmlspecialchars($facture['statut_paiement']) ?></td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="#" onclick="openViewFactureModal(<?= $facture['id_facture'] ?>)" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-eye"></i> Voir
                                </a>
                                <button onclick="confirmDeleteFacture(<?= $facture['id_facture'] ?>)" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i class="fa fa-trash"></i> Supp
                                </button>
                                <a href="generate_pdf_facture.php?id_facture=<?= $facture['id_facture'] ?>" target="_blank" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-700 text-xs flex items-center gap-1">
                                    <i class="fa fa-print"></i> Imprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="viewFactureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full relative">
        <button onclick="closeViewFactureModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
            <i class="fa fa-times w-6 h-6"></i>
        </button>
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4 text-indigo-600">Détails de la Facture</h2>
            <div id="factureDetailsContent" class="space-y-2 text-gray-700 dark:text-gray-200">
                </div>
        </div>
    </div>
</div>

<script>
    function openViewFactureModal(id_facture) {
        fetch('get_facture_details.php?id_facture=' + id_facture)
            .then(response => response.json())
            .then(data => {
                const contentDiv = document.getElementById('factureDetailsContent');
                contentDiv.innerHTML = `
                    <p><strong>ID Facture:</strong> ${data.id_facture}</p>
                    <p><strong>ID Réparation:</strong> ${data.id_reparation || 'N/A'}</p>
                    <p><strong>Nom du Demandeur:</strong> ${data.nom_demandeur || 'N/A'}</p>
                    <p><strong>Date Facture:</strong> ${data.date_facture}</p>
                    <p><strong>Montant Total:</strong> ${parseFloat(data.montant_total).toFixed(2)} FCFA</p>
                    <p><strong>Montant Réglé:</strong> ${parseFloat(data.montant_regle).toFixed(2)} FCFA</p>
                    <p><strong>Reste à Payer:</strong> ${parseFloat(data.reste_a_payer).toFixed(2)} FCFA</p>
                    <p><strong>Détails:</strong> ${data.details || 'Aucun'}</p>
                    <p><strong>Statut Paiement:</strong> ${data.statut_paiement}</p>
                    `;
                const modal = document.getElementById('viewFactureModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des détails de la facture:', error);
                alert('Erreur lors de la récupération des détails de la facture.');
            });
    }

    function closeViewFactureModal() {
        const modal = document.getElementById('viewFactureModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDeleteFacture(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette facture ?")) {
            window.location.href = 'delete_facture.php?id_facture=' + encodeURIComponent(id);
        }
    }
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>