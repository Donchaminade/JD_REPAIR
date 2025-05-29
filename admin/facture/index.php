<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/auth.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
// include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php';
// include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

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
    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php'; ?>
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
                                <button onclick='openDetailsModal(<?= htmlspecialchars(json_encode($facture)) ?>)' class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-eye"></i> Voir
                                </button>
                                <button onclick="confirmDeleteFacture(<?= $facture['id_facture'] ?>)" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i class="fa fa-trash"></i> Supp
                                </button>
                                <button onclick="exportFacturesToPDF()" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-700 text-xs flex items-center gap-1">
                                    <i class="fa fa-file-pdf"></i> PDF
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
        <button onclick="closeDetailsModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i class="fa fa-times w-6 h-6"></i>
        </button>
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Détails de la Facture</h2>
        <div id="detailContent" class="space-y-4 text-base text-gray-700 dark:text-white divide-y divide-gray-300 dark:divide-gray-600">
        </div>
    </div>
</div>
<script>
    function openDetailsModal(data) {
        const detailModal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = `
            <div><strong>ID Facture:</strong> ${data.id_facture}</div>
            <div class="pt-4"><strong>Nom du Demandeur:</strong> ${data.nom_demandeur || 'N/A'}</div>
            <div class="pt-4"><strong>Date Facture:</strong> ${data.date_facture}</div>
            <div class="pt-4"><strong>Montant Total:</strong> ${parseFloat(data.montant_total).toFixed(2)} FCFA</div>
            <div class="pt-4"><strong>Montant Réglé:</strong> ${parseFloat(data.montant_regle).toFixed(2)} FCFA</div>
            <div class="pt-4"><strong>Reste à Payer:</strong> ${parseFloat(data.reste_a_payer).toFixed(2)} FCFA</div>
            <div class="pt-4"><strong>Statut Paiement:</strong> ${data.statut_paiement}</div>
            <div class="pt-4"><strong>Détails:</strong> ${data.details || 'Aucun'}</div>
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

    function confirmDeleteFacture(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette facture ?")) {
            window.location.href = 'delete.php?id_facture=' + encodeURIComponent(id);
        }
    }



    function exportFacturesToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape'
        });
        const pageWidth = doc.internal.pageSize.getWidth();

        // --- Ajouter le Logo (Centré et plus grand) ---
        const imgData = 'jd.png'; // Assurez-vous que le chemin est correct
        const logoWidth = 40;
        const logoHeight = 27;
        const logoX = (pageWidth - logoWidth) / 2; // Centrer horizontalement
        const logoY = 5; // Marge du haut
        doc.addImage(imgData, 'PNG', logoX, logoY, logoWidth, logoHeight);
        const yAfterLogo = logoY + logoHeight + 5; // Espace après le logo

        doc.setFontSize(16);
        const title = "Liste des Factures";
        const titleWidth = doc.getTextWidth(title);
        const titleX = (pageWidth - titleWidth) / 2;
        doc.text(title, titleX, yAfterLogo + 7); // Centrer le titre sous le logo
        const yAfterTitle = yAfterLogo + 12; // Espace après le titre

        const tableColumn = ["ID Facture", "Nom du Demandeur", "Date Facture", "Montant Total", "Montant Réglé", "Reste à Payer", "Statut Paiement"];
        const tableRows = [];

        const rows = document.querySelectorAll("#factureTable tbody tr");
        rows.forEach(row => {
            const cols = row.querySelectorAll("td");
            const rowData = [];
            for (let i = 0; i < Math.min(cols.length, tableColumn.length); i++) {
                rowData.push(cols[i].innerText);
            }
            tableRows.push(rowData);
        });

        // Style du tableau chic avec bordures
        const styles = {
            fontSize: 10,
            borderColor: '#000000',
            lineWidth: 0.5,
            textColor: '#34495e',
            fillColor: '#f9f9f9',
            padding: 2,
        };

        const headerStyles = {
            fillColor: '#34495e',
            textColor: '#fff',
            fontStyle: 'bold',
        };

        doc.autoTable({
            head: [tableColumn],
            body: tableRows,
            startY: yAfterTitle + 10, // Début du tableau après le logo et le titre
            styles: styles,
            headStyles: headerStyles,
        });

        doc.save("factures_paysage.pdf");
    }

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>