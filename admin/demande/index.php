<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/auth.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
// include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php';
// include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

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
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    }
}
?>

<script src="https://unpkg.com/lucide@latest"></script>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php'; ?>
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
                    <?php
                        // Connexion à la base (si ce n'est pas déjà fait plus haut)
                        $conn = new mysqli("localhost", "root", "", "reparationdb");

                        // Récupérer les ID des demandes déjà traitées
                        $traitementResult = $conn->query("SELECT id_demande FROM traitement");
                        $traites = [];
                        while ($row = $traitementResult->fetch_assoc()) {
                            $traites[] = $row['id_demande'];
                        }
                    ?>

                    <?php foreach ($demande_reparations as $demande): ?>
                        <?php $isTraite = in_array($demande['id_demande'], $traites); ?>
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
                                    <i class="fa-solid fa-eye "></i> détails
                                </button>

                                <button
                                    onclick="<?= $isTraite ? 'return false' : 'openTraitementModal('.htmlspecialchars(json_encode($demande)).')' ?>"
                                    class="<?= $isTraite ? 'bg-gray-400 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-800' ?> text-white px-2 py-1 rounded text-xs flex items-center gap-1"
                                    <?= $isTraite ? 'disabled' : '' ?>>
                                    
                                    <i class="fa-solid fa-wrench "></i> <?= $isTraite ? 'Déjà traité' : 'Traiter' ?>
                                </button>

                                <button onclick="openUpdateModal(<?= htmlspecialchars(json_encode($demande)) ?>)" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-edit "></i> Modif
                                </button>

                                <a href="delete.php?id_demande=<?= $demande['id_demande'] ?>" onclick="return confirm('Supprimer cette demande ?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-trash "></i> Supp
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


        <div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-3xl shadow-lg relative">
                <button onclick="closeUpdateModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
                    <i data-lucide="x" class="w-6 h-6">❌</i>
                </button>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center">Modifier la Demande</h2>

                <form method="POST" action="update.php" class="space-y-4">
                    <input type="hidden" name="id_demande" id="update_id_demande">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom complet</label>
                        <input type="text" name="nom_complet" id="update_nom_complet" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro</label>
                        <input type="text" name="numero" id="update_numero" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="update_email" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse</label>
                        <input type="text" name="adresse" id="update_adresse" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marque du téléphone</label>
                        <input type="text" name="marque_telephone" id="update_marque_telephone" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Problème</label>
                        <textarea name="probleme" id="update_probleme" rows="3" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de la demande</label>
                        <input type="date" name="date_demande" id="update_date_demande" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type de réparation</label>
                        <select name="type_reparation" id="update_type_reparation" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                            <option value="express">Express</option>
                            <option value="standard">Standard</option>
                        </select>
                    </div>

                    <div class="text-center pt-4">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="detailModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-3xl p-8 relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
            </button>
            <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Détails de la demande</h2>

            <div id="detailContent" class="space-y-4 text-base text- text-gray-700 dark:text-white divide-y divide-gray-300 dark:divide-gray-600">
            </div>
        </div>
        </div>

        <script>
        function closeModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
            modal.classList.add('hidden');
            }
        }

        function openModal() {
            const modal = document.getElementById('detailModal');
            if (modal) {
            modal.classList.remove('hidden');
            }

            if (window.lucide) {
            window.lucide.replace(); // Recharge les icônes Lucide si utilisées
            }
        }
        </script>

<div id="traitementModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-2xl shadow-lg relative">
        <button onclick="closeTraitementModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center">Traitement de la Demande</h2>

        <div id="demandeInfos" class="mb-4 text-gray-700 dark:text-white">
            <p><strong class="dark:text-gray-300">Nom du demandeur :</strong> <span id="traitement_nom_complet"></span></p>
            <p><strong class="dark:text-gray-300">Marque du téléphone :</strong> <span id="traitement_marque_telephone"></span></p>
            <p><strong class="dark:text-gray-300">Problème :</strong> <span id="traitement_probleme"></span></p>
            <p><strong class="dark:text-gray-300">Date de la demande :</strong> <span id="traitement_date_demande"></span></p>
        </div>

        <form method="POST" action="../traitement/create.php

        " class="space-y-4" id="traitementForm">
            <input type="hidden" name="id_demande" id="traitement_id_demande">
            <input type="hidden" name="date_reception" id="traitement_date_reception">

            <div>
                <label for="montant_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant Total (FCFA)</label>
                <input type="number" name="montant_total" id="montant_total" min="0" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label for="montant_paye" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant Payé (FCFA)</label>
                <input type="number" name="montant_paye" id="montant_paye" min="0" value="0" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label for="reste_a_payer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reste à Payer (FCFA)</label>
                <input type="number" name="reste_a_payer" id="reste_a_payer" class="w-full px-4 py-2 border rounded-md bg-gray-200 dark:bg-gray-700 dark:text-white" readonly>
            </div>

            <div>
                <label for="type_reparation_traitement" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type de Réparation</label>
                <select name="type_reparation" id="type_reparation_traitement" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <option value="express">Express</option>
                    <option value="standard">Standard</option>
                </select>
            </div>

            <div>
                <label for="id_technicien" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du Technicien</label>
                <select name="id_technicien" id="id_technicien" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <option value="">Sélectionner un technicien</option>
                    <?php
                    $stmtTechniciens = $pdo->prepare("SELECT id_utilisateur, nom_complet FROM utilisateurs WHERE role = 'technicien'");
                    $stmtTechniciens->execute();
                    $techniciens = $stmtTechniciens->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($techniciens as $technicien):
                    ?>
                        <option value="<?= $technicien['id_utilisateur'] ?>"><?= htmlspecialchars($technicien['nom_complet']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center pt-4">
                <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-md">Sauvegarder</button>
            </div>
        </form>
    </div>
</div>

<script>
    const traitementModalForm = document.querySelector('#traitementModal form');
    const traitementModalElement = document.getElementById('traitementModal');

    if (traitementModalForm && traitementModalElement) {
        traitementModalForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('../traitement/create.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    const idDemandeTraitee = formData.get('id_demande');
                    const buttonsTraiter = document.querySelectorAll('#demandesTable tr');
                    buttonsTraiter.forEach(row => {
                        const button = row.querySelector(`button[onclick*='openTraitementModal({\"id_demande\":${idDemandeTraitee},']`);
                        if (button) {
                            button.classList.remove('bg-purple-600', 'hover:bg-purple-800');
                            button.classList.add('bg-gray-400', 'cursor-not-allowed');
                            button.disabled = true;
                            button.innerHTML = '<i data-lucide="wrench" class="w-4 h-4"></i> Déjà traité';
                            lucide.createIcons();
                        }
                    });
                    closeTraitementModal();
                } else {
                    alert('Erreur lors de l\'enregistrement du traitement: ' + data);
                }
            })
            .catch(error => {
                console.error('Erreur réseau:', error);
                alert('Erreur réseau lors de l\'enregistrement du traitement.');
            });
        });
    }

    function openTraitementModal(demande) {
        document.getElementById('traitementModal').classList.remove('hidden');
        document.getElementById('traitement_id_demande').value = demande.id_demande;
        document.getElementById('traitement_nom_complet').textContent = demande.nom_complet;
        document.getElementById('traitement_marque_telephone').textContent = demande.marque_telephone;
        document.getElementById('traitement_probleme').textContent = demande.probleme;
        document.getElementById('traitement_date_demande').textContent = demande.date_demande;
        document.getElementById('traitement_date_reception').value = demande.date_demande;
        document.getElementById('type_reparation_traitement').value = demande.type_reparation;
        document.getElementById('id_technicien').value = '';

        document.getElementById('montant_total').value = '';
        document.getElementById('montant_paye').value = '0';
        document.getElementById('reste_a_payer').value = '';

        calculerResteAPayer();
    }

    function closeTraitementModal() {
        document.getElementById('traitementModal').classList.add('hidden');
    }

    function calculerResteAPayer() {
        const montantTotal = parseFloat(document.getElementById('montant_total').value) || 0;
        const montantPaye = parseFloat(document.getElementById('montant_paye').value) || 0;
        const resteAPayerInput = document.getElementById('reste_a_payer');
        resteAPayerInput.value = (montantTotal - montantPaye).toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const montantTotalInput = document.getElementById('montant_total');
        const montantPayeInput = document.getElementById('montant_paye');

        if (montantTotalInput && montantPayeInput) {
            montantTotalInput.addEventListener('input', calculerResteAPayer);
            montantPayeInput.addEventListener('input', calculerResteAPayer);
        }
    });
</script>

<script>
    function openUpdateModal(demande) {
        document.getElementById('updateModal').classList.remove('hidden');
        document.getElementById('update_id_demande').value = demande.id_demande;
        document.getElementById('update_nom_complet').value = demande.nom_complet;
        document.getElementById('update_numero').value = demande.numero;
        document.getElementById('update_email').value = demande.email;
        document.getElementById('update_adresse').value = demande.adresse;
        document.getElementById('update_marque_telephone').value = demande.marque_telephone;
        document.getElementById('update_probleme').value = demande.probleme;
        document.getElementById('update_date_demande').value = demande.date_demande;
        document.getElementById('update_type_reparation').value = demande.type_reparation;
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').classList.add('hidden');
    }
</script>

<script>
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
        if (window.lucide) {
            window.lucide.replace();
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
            function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });
        const pageWidth = doc.internal.pageSize.getWidth();

        // --- Ajouter le Logo (Centré et taille ajustée) ---
        const imgData = 'jd.png'; // Assurez-vous que le chemin est correct
        const logoWidth = 40; // Diminuer la largeur
        const logoHeight = 27; // Conserver le ratio approximativement
        const logoX = (pageWidth - logoWidth) / 2; // Centrer horizontalement
        const logoY = 5; // Réduire la marge du haut
        doc.addImage(imgData, 'PNG', logoX, logoY, logoWidth, logoHeight);
        const yAfterLogo = logoY + logoHeight + 5; // Réduire l'espace après le logo

        doc.setFontSize(14);
        const title = "Liste des demandes de réparation";
        const titleWidth = doc.getTextWidth(title);
        const titleX = (pageWidth - titleWidth) / 2;
        doc.text(title, titleX, yAfterLogo + 7); // Réduire l'espace avant le titre
        const yAfterTitle = yAfterLogo + 12; // Position après le titre

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
            startY: yAfterTitle + 5 // Réduire l'espace avant le tableau
        });

        doc.save("demandes_reparation.pdf");
    }

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

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>