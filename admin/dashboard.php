<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
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
        $stmt = $pdo->query("SELECT COALESCE(SUM(montant_total), 0) FROM facture WHERE statut_paiement = 'Payée'");
        $chiffreAffaires = $stmt->fetchColumn();

        // Nombre de factures payées
        $stmt = $pdo->query("SELECT COUNT(*) FROM facture WHERE statut_paiement = 'Payée'");
        $nombreFacturesPayees = $stmt->fetchColumn();

        // Nombre de factures non payées
        $stmt = $pdo->query("SELECT COUNT(*) FROM facture WHERE statut_paiement = 'Non payée'");
        $nombreFacturesNonPayees = $stmt->fetchColumn();

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
                <i class="fa-solid fa-envelope fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Demandes</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreDemandes) ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-yellow-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-wrench fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Réparations</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreReparations) ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-purple-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-file-invoice fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Factures</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreFactures) ?></p>
            </div>
        </div>

        <div class="bg-green-500 text-white shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="mr-4 text-center md:text-left">
                <i class="fa-solid fa-coins fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-white/70">Chiffre d'affaires</p>
                <p class="text-lg font-semibold"><?= htmlspecialchars(number_format($chiffreAffaires, 2)) ?> FCFA</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-green-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-check-circle fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Payées</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreFacturesPayees) ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-red-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-times-circle fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Impayées</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreFacturesNonPayees) ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-purple-600 mr-4 text-center md:text-left">
                <i class="fa-solid fa-users fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Utilisateurs</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreUtilisateurs) ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-orange-500 mr-4 text-center md:text-left">
                <i class="fa-solid fa-hard-hat fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Techniciens</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreTechniciens) ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex items-center justify-center h-32 w-full md:w-auto">
            <div class="text-blue-600 mr-4 text-center md:text-left">
                <i class="fa-solid fa-tools fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-300">Prêtes à récupérer</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($nombreReparationsPretes) ?></p>
            </div>
        </div>
    </div>

<h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Statistiques et Actions</h3>
    <div class="flex mb-6">
        <div class="w-1/3 pr-4">
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg overflow-x-auto">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Réparations prêtes à récupérer</h4>
                    <?php
                    if ($pdo) {
                        $stmt = $pdo->query("SELECT dr.nom_complet, dr.numero, r.id_reparation
                                               FROM reparation r
                                               JOIN demande_reparation dr ON r.id_demande = dr.id_demande
                                               WHERE r.statut = 'Prêt à récupérer'");
                        $reparationsPretes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($reparationsPretes) > 0) {
                            echo '<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">';
                            echo '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
                            echo '<tr>';
                            echo '<th class="py-3 px-6">Nom du client</th>';
                            echo '<th class="py-3 px-6">Numéro</th>';
                            echo '<th class="py-3 px-6">Actions</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            foreach ($reparationsPretes as $reparation) {
                                echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                                echo '<td class="py-4 px-6">' . htmlspecialchars($reparation['nom_complet']) . '</td>';
                                echo '<td class="py-4 px-6">' . htmlspecialchars($reparation['numero']) . '</td>';
                                echo '<td class="py-4 px-6">';
                                echo '<a href="mailto:?subject=Votre réparation est prête&body=Bonjour ' . htmlspecialchars($reparation['nom_complet']) . ',%0D%0AVotre réparation est prête à être récupérée." class="text-blue-500 hover:underline mr-2"><i class="fa-solid fa-envelope"></i></a>';
                                echo '<a href="https://wa.me/' . htmlspecialchars($reparation['numero']) . '?text=Bonjour,%20votre%20réparation%20est%20prête%20à%20être%20récupérée." target="_blank" class="text-green-500 hover:underline"><i class="fa-brands fa-whatsapp"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p class="text-gray-600 dark:text-gray-300">Aucune réparation n\'est actuellement prête à être récupérée.</p>';
                        }
                    } else {
                        echo '<p class="text-red-500">Erreur de connexion à la base de données.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="flex-1">
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg overflow-x-auto">
                <div class="p-4">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Dernières 20 demandes</h4>
                    <?php
                    if ($pdo) {
                        $stmt = $pdo->query("SELECT id_demande, nom_complet, numero, date_demande FROM demande_reparation ORDER BY date_demande DESC LIMIT 20");
                        $dernieresDemandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($dernieresDemandes) > 0) {
                            echo '<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">';
                            echo '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
                            echo '<tr>';
                            echo '<th class="py-3 px-6">Nom du client</th>';
                            echo '<th class="py-3 px-6">Numéro</th>';
                            echo '<th class="py-3 px-6">Date de la demande</th>';
                            echo '<th class="py-3 px-6">Action</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            foreach ($dernieresDemandes as $demande) {
                                echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                                echo '<td class="py-4 px-6">' . htmlspecialchars($demande['nom_complet']) . '</td>';
                                echo '<td class="py-4 px-6">' . htmlspecialchars($demande['numero']) . '</td>';
                                echo '<td class="py-4 px-6">' . htmlspecialchars($demande['date_demande']) . '</td>';
                                echo '<td class="py-4 px-6">';
                                echo '<a href="/JD_REPAIR/traitement_demande.php?id=' . htmlspecialchars($demande['id_demande']) . '" class="text-indigo-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i> Traiter</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p class="text-gray-600 dark:text-gray-300">Aucune nouvelle demande pour le moment.</p>';
                        }
                    } else {
                        echo '<p class="text-red-500">Erreur de connexion à la base de données.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center mb-6">
        <div class="bg-white dark:bg-gray-700 shadow rounded-lg overflow-x-auto w-full md:w-2/3 lg:w-1/2">
            <div class="p-4">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 text-center">Techniciens et réparations en cours</h4>
                <?php
                if ($pdo) {
                    $stmt = $pdo->query("SELECT u.nom_complet, COUNT(t.id_traitement) AS nombre_reparations
                                           FROM utilisateurs u
                                           LEFT JOIN traitement t ON u.id_utilisateur = t.id_technicien
                                           WHERE u.role = 'technicien'
                                           GROUP BY u.id_utilisateur
                                           ORDER BY nombre_reparations DESC");
                    $techniciensReparations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($techniciensReparations) > 0) {
                        echo '<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">';
                        echo '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
                        echo '<tr>';
                        echo '<th class="py-3 px-6">Technicien</th>';
                        echo '<th class="py-3 px-6">Réparations en cours</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach ($techniciensReparations as $tech) {
                            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
                            echo '<td class="py-4 px-6">' . htmlspecialchars($tech['nom_complet']) . '</td>';
                            echo '<td class="py-4 px-6">' . htmlspecialchars($tech['nombre_reparations']) . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p class="text-gray-600 dark:text-gray-300 text-center">Aucun technicien trouvé.</p>';
                    }
                } else {
                    echo '<p class="text-red-500 text-center">Erreur de connexion à la base de données.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>