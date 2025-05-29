<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/admin/auth.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

// Connexion à la base de données
$pdo = null;
try {
    $pdo = new PDO('mysql:host=localhost:3306;dbname=reparationdb;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<div class='p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800' role='alert'>
              <span class='font-medium'>Erreur de connexion à la base de données :</span> " . $e->getMessage() . "
            </div>";
    $nombreDemandes = 0;
    $nombreReparations = 0;
    $nombreFactures = 0;
    $chiffreAffaires = 0;
    $nombreFacturesPayees = 0;
    $nombreFacturesNonPayees = 0;
    $nombreUtilisateurs = 0;
    $nombreTechniciens = 0;
    $nombreReparationsPretes = 0;
}

if ($pdo) {
    try {
        // Nombre de demandes de réparation
        $stmt = $pdo->query("SELECT COUNT(*) FROM demande_reparation");
        $nombreDemandes = $stmt->fetchColumn();

        // Nombre de réparations
        $stmt = $pdo->query("SELECT COUNT(*) FROM reparation");
        $nombreReparations = $stmt->fetchColumn();

        // Nombre de factures
        $stmt = $pdo->query("SELECT COUNT(*) FROM facture");
        $nombreFactures = $stmt->fetchColumn();

        // Chiffre d'affaires (somme des montants totaux des factures payées)

        $stmt = $pdo->query("SELECT COALESCE(SUM(montant_paye), 0) FROM traitement ");
        $chiffreAffaires = $stmt->fetchColumn();
        // Chiffre d'affaires montant paye(somme des montants totaux des factures payées)
        // $stmt = $pdo->query("SELECT COALESCE(SUM(montant_paye), 0) FROM traitement ");
        // $chiffreAffairesmp = $stmt->fetchColumn();

        // Nombre de factures payées
        $stmt = $pdo->query("SELECT COUNT(*) FROM facture WHERE statut_paiement = 'Payée'");
        $nombreFacturesPayees = $stmt->fetchColumn();

        // Nombre de factures non payées
        $stmt = $pdo->query("SELECT COUNT(*) FROM reparation WHERE statut = 'Endommagé'");
        $nombrerepfail = $stmt->fetchColumn();

        // Nombre d'utilisateurs (tous)
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs");
        $nombreUtilisateurs = $stmt->fetchColumn();

        // Nombre de techniciens
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'technicien'");
        $nombreTechniciens = $stmt->fetchColumn();

        // Nombre de réparations prêtes à récupérer
        $stmt = $pdo->query("SELECT COUNT(*) FROM reparation WHERE statut = 'Prêt à récupérer'");
        $nombreReparationsPretes = $stmt->fetchColumn();

    } catch (PDOException $e) {
        echo "<div class='p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800' role='alert'>
                  <span class='font-medium'>Erreur lors de la récupération des données :</span> " . $e->getMessage() . "
                </div>";
        $nombreDemandes = 0;
        $nombreReparations = 0;
        $nombreFactures = 0;
        $chiffreAffaires = 0;
        $nombreFacturesPayees = 0;
        $nombreFacturesNonPayees = 0;
        $nombreUtilisateurs = 0;
        $nombreTechniciens = 0;
        $nombreReparationsPretes = 0;
    }
} else {
    $nombreDemandes = 0;
    $nombreReparations = 0;
    $nombreFactures = 0;
    $chiffreAffaires = 0;
    $nombreFacturesPayees = 0;
    $nombreFacturesNonPayees = 0;
    $nombreUtilisateurs = 0;
    $nombreTechniciens = 0;
    $nombreReparationsPretes = 0;
}
?>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Bienvenue sur le tableau de bord !</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-blue-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-book fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Demandes</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><strong><?= htmlspecialchars($nombreDemandes) ?></strong></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-yellow-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-wrench fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Réparations</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><strong><?= htmlspecialchars($nombreReparations) ?></strong></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-purple-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-file-invoice fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Factures</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><strong><?= htmlspecialchars($nombreFactures) ?></strong></p>
            </div>
        </div>

        <div class="bg-gray-700 text-white shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="mr-4 text-center md:text-left">
            <i class="fa-solid fa-coins fa-2x"></i>
            </div>
            <div>
            <p class="text-sm text-white/70">Chiffre d'affaires</p>
            <p class="text-2xl font-bold text-green-500 transition-transform duration-200 hover:scale-110">
                <?= htmlspecialchars(number_format($chiffreAffaires, 2)) ?> FCFA
            </p>
            </div>
        </div>
        <!-- <div class="bg-blue-900 text-white shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="mr-4 text-center md:text-left">
                <i class="fa-solid fa-coins fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-white/70">Total Avance recu</p>
                
            </div>
        </div> -->

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-green-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-check-circle fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Totalement Payées</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><strong><?= htmlspecialchars($nombreFacturesPayees) ?></strong></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-red-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-times-circle fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Reparations Echouee</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><strong><?= htmlspecialchars($nombrerepfail) ?></strong></p>
            </div>
        </div>

        <div class="bg-yellow-900 dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-purple-600 mr-4 text-center md:text-left">
                <i class="fa-solid fa-users fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Utilisateurs</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><strong><?= htmlspecialchars($nombreUtilisateurs) ?></strong></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-orange-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-hard-hat fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Techniciens</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"></p><strong><?= htmlspecialchars($nombreTechniciens) ?></strong></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-blue-600 mr-4 text-center md:text-left">
                <i class="fa-solid fa-tools fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Prêtes à récupérer</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"></p><strong><?= htmlspecialchars($nombreReparationsPretes) ?></strong></p>
            </div>
        </div>
    </div>
<br>

<h1 class="text-2xl text-center font-bold text-gray-800 dark:text-gray-200 mb-8 tracking-wide">Raccourcis & Statistiques</h1>
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Bloc Réparations prêtes à récupérer -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white dark:bg-gray-700 shadow-lg rounded-2xl overflow-hidden">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center border-b pb-2">Réparations prêtes à récupérer</h4>
                <?php
                if ($pdo) {
                    $stmt = $pdo->query("SELECT dr.nom_complet, dr.numero, r.id_reparation
                                           FROM reparation r
                                           JOIN demande_reparation dr ON r.id_demande = dr.id_demande
                                           WHERE r.statut = 'Prêt à récupérer'");
                    $reparationsPretes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($reparationsPretes) > 0) {
                        echo '<div class="overflow-x-auto">';
                        echo '<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">';
                        echo '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
                        echo '<tr>';
                        echo '<th class="py-3 px-4">Nom du client</th>';
                        echo '<th class="py-3 px-4">Numéro</th>';
                        echo '<th class="py-3 px-4 text-center">Actions</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach ($reparationsPretes as $reparation) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">';
                            echo '<td class="py-3 px-4">' . htmlspecialchars($reparation['nom_complet']) . '</td>';
                            echo '<td class="py-3 px-4">' . htmlspecialchars($reparation['numero']) . '</td>';
                            echo '<td class="py-3 px-4 text-center">';
                            echo '<a href="mailto:?subject=Votre réparation est prête&body=Bonjour ' . htmlspecialchars($reparation['nom_complet']) . ',%0D%0AVotre réparation est prête à être récupérée." class="text-blue-500 hover:text-blue-700 mx-2" title="Envoyer Email"><i class="fa-solid fa-envelope"></i></a>';
                            echo '<a href="https://wa.me/' . htmlspecialchars($reparation['numero']) . '?text=Bonjour,%20votre%20réparation%20est%20prête%20à%20être%20récupérée." target="_blank" class="text-green-500 hover:text-green-700 mx-2" title="Envoyer WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo '<p class="text-gray-600 dark:text-gray-300 text-center">Aucune réparation n\'est actuellement prête à être récupérée.</p>';
                    }
                } else {
                    echo '<p class="text-red-500 text-center">Erreur de connexion à la base de données.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bloc Graphiques -->
    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Statuts de réparation -->
        <div class="bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6 flex flex-col items-center">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Statuts de réparation</h4>
            <canvas id="pieStatutReparation" width="220" height="220"></canvas>
        </div>
        <!-- Statuts de traitement -->
        <div class="bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6 flex flex-col items-center">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Traitements : Endommagé vs Prêt à récupérer</h4>
            <canvas id="pieStatutTraitement" width="220" height="220"></canvas>
        </div>
        <!-- Statuts de factures -->
        <div class="bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6 flex flex-col items-center">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Factures : Payée vs Non payée</h4>
            <canvas id="pieStatutFacture" width="220" height="220"></canvas>
        </div>
        <!-- Utilisateurs -->
        <div class="bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6 flex flex-col items-center">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Utilisateurs</h4>
            <canvas id="pieUtilisateurs" width="220" height="220"></canvas>
        </div>
    </div>
</div>

<!-- Deuxième ligne : Graphiques -->
<div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Techniciens ayant le plus réparé -->
    <div class="bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6">
        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Top Techniciens</h4>
        <canvas id="doughnutTechniciensReparations" height="260"></canvas>
    </div>
    <!-- Evolution des demandes par mois et par année -->
    <div class="bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6">
        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Evolution des demandes (mois/année)</h4>
        <canvas id="lineDemandesMoisAnnee" height="260"></canvas>
    </div>
</div>

<?php
// Statuts de réparation (table reparation)
$statuts = ['En cours', 'Prêt à récupérer', 'Endommagé'];
$statutCounts = [];
if ($pdo) {
    foreach ($statuts as $statut) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reparation WHERE statut = ?");
        $stmt->execute([$statut]);
        $statutCounts[] = (int)$stmt->fetchColumn();
    }
} else {
    $statutCounts = [0, 0, 0];
}

// Statuts de traitement (juste Endommagé et Prêt à récupérer)
$statutsTraitement = ['Endommagé', 'Prêt à récupérer'];
$statutTraitementCounts = [];
if ($pdo) {
    foreach ($statutsTraitement as $statut) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reparation WHERE statut = ?");
        $stmt->execute([$statut]);
        $statutTraitementCounts[] = (int)$stmt->fetchColumn();
    }
} else {
    $statutTraitementCounts = [0, 0];
}

// Statuts de factures (Payée vs Non payée)
$facturePayee = 0;
$factureNonPayee = 0;
if ($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM facture WHERE statut_paiement = 'Payée'");
    $facturePayee = (int)$stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM facture WHERE statut_paiement != 'Payée'");
    $factureNonPayee = (int)$stmt->fetchColumn();
}

// Utilisateurs : techniciens vs autres
$nbTechniciens = 0;
$nbAutres = 0;
if ($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'technicien'");
    $nbTechniciens = (int)$stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role != 'technicien' OR role IS NULL OR role = ''");
    $nbAutres = (int)$stmt->fetchColumn();
}

// Evolution des demandes par mois et par année (diagramme en lignes)
$barLabels = [];
$barDatasets = [];
if ($pdo) {
    // Récupérer les années distinctes
    $stmt = $pdo->query("SELECT DISTINCT YEAR(date_demande) as annee FROM demande_reparation ORDER BY annee ASC");
    $annees = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Générer les labels de mois (01 à 12)
    $moisLabels = [];
    for ($m = 1; $m <= 12; $m++) {
        $moisLabels[] = sprintf('%02d', $m);
    }
    $barLabels = $moisLabels;

    // Pour chaque année, récupérer les demandes par mois
    foreach ($annees as $annee) {
        $data = array_fill(0, 12, 0);
        $stmt = $pdo->prepare("
            SELECT MONTH(date_demande) as mois, COUNT(*) as total
            FROM demande_reparation
            WHERE YEAR(date_demande) = ?
            GROUP BY mois
        ");
        $stmt->execute([$annee]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $data[(int)$row['mois'] - 1] = (int)$row['total'];
        }
        $barDatasets[] = [
            'label' => $annee,
            'data' => $data,
        ];
    }
}
// Couleurs pour les datasets (max 5 années)
$barColors = ['#6366f1', '#10b981', '#f59e42', '#ef4444', '#a78bfa'];
foreach ($barDatasets as $i => &$ds) {
    $ds['borderColor'] = $barColors[$i % count($barColors)];
    $ds['backgroundColor'] = $barColors[$i % count($barColors)];
    $ds['fill'] = false;
    $ds['tension'] = 0.3;
}
unset($ds);

// Récupérer les techniciens et le nombre de réparations effectuées
$techLabels = [];
$techData = [];
if ($pdo) {
    $stmt = $pdo->query("
        SELECT u.nom_complet, COUNT(r.id_reparation) AS nb_reparations
        FROM utilisateurs u
        LEFT JOIN traitement t ON u.id_utilisateur = t.id_technicien
        LEFT JOIN reparation r ON r.id_traitement = t.id_traitement
        WHERE u.role = 'technicien'
        GROUP BY u.id_utilisateur
        ORDER BY nb_reparations DESC
        LIMIT 10
    ");
    $techs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($techs as $tech) {
        $techLabels[] = $tech['nom_complet'];
        $techData[] = (int)$tech['nb_reparations'];
    }
}
// Couleurs pour le doughnut techniciens
$doughnutColors = ['#6366f1', '#10b981', '#f59e42', '#ef4444', '#a78bfa', '#f472b6', '#34d399', '#fbbf24', '#60a5fa', '#f87171'];
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Diagramme circulaire : Statuts de réparation
    new Chart(document.getElementById('pieStatutReparation').getContext('2d'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($statuts) ?>,
            datasets: [{
                data: <?= json_encode($statutCounts) ?>,
                backgroundColor: [
                    '#3b82f6', // En cours
                    '#10b981', // Prêt à récupérer
                    '#ef4444'  // Endommagé
                ],
            }]
        },
        options: {
            responsive: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Diagramme circulaire : Statuts de traitement
    new Chart(document.getElementById('pieStatutTraitement').getContext('2d'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($statutsTraitement) ?>,
            datasets: [{
                data: <?= json_encode($statutTraitementCounts) ?>,
                backgroundColor: [
                    '#ef4444', // Endommagé
                    '#10b981'  // Prêt à récupérer
                ],
            }]
        },
        options: {
            responsive: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Diagramme circulaire : Statuts de factures
    new Chart(document.getElementById('pieStatutFacture').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Payée', 'Non payée'],
            datasets: [{
                data: [<?= $facturePayee ?>, <?= $factureNonPayee ?>],
                backgroundColor: [
                    '#10b981', // Payée
                    '#ef4444'  // Non payée
                ],
            }]
        },
        options: {
            responsive: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Diagramme circulaire : Utilisateurs
    new Chart(document.getElementById('pieUtilisateurs').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Techniciens', 'Autres'],
            datasets: [{
                data: [<?= $nbTechniciens ?>, <?= $nbAutres ?>],
                backgroundColor: [
                    '#f59e42', // Techniciens
                    '#6366f1'  // Autres
                ],
            }]
        },
        options: {
            responsive: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Diagramme doughnut : Techniciens ayant le plus réparé
    new Chart(document.getElementById('doughnutTechniciensReparations').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($techLabels) ?>,
            datasets: [{
                label: 'Nombre de réparations',
                data: <?= json_encode($techData) ?>,
                backgroundColor: <?= json_encode(array_slice($doughnutColors, 0, count($techLabels))) ?>
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Diagramme en lignes : Evolution des demandes par mois et par année
    new Chart(document.getElementById('lineDemandesMoisAnnee').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode($barLabels) ?>,
            datasets: <?= json_encode($barDatasets) ?>
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true },
                x: { title: { display: true, text: 'Mois' } }
            }
        }
    });
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>