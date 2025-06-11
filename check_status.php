<?php
// --- Configuration de la base de données ---
$db_host = 'localhost'; // Votre hôte de base de données
$db_name = 'reparationdb'; // Le nom de votre base de données
$db_user = 'root'; // <<< À REMPLACER PAR VOTRE NOM D'UTILISATEUR DE BASE DE DONNÉES
$db_pass = ''; // <<< À REMPLACER PAR VOTRE MOT DE PASSE DE BASE DE DONNÉES

$conn = null; // Initialisation de la variable de connexion
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Récupérer les résultats sous forme de tableau associatif par défaut
} catch (PDOException $e) {
    $response['message'] = "Erreur de connexion à la base de données : " . $e->getMessage();
    echo json_encode($response);
    exit();
}

// --- Traitement du formulaire de vérification (si l'utilisateur soumet un nom ou un numéro) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['verif_nom']) || isset($_POST['verif_numero']))) {
    $nom_verif = trim($_POST['verif_nom'] ?? '');
    $numero_verif = trim($_POST['verif_numero'] ?? '');

    if (empty($nom_verif) && empty($numero_verif)) {
        $response['message'] = '⚠️ Veuillez entrer un nom complet ou un numéro de téléphone.';
        echo json_encode($response);
        exit();
    }

    $sql = "SELECT
                dr.nom_complet,
                dr.numero,
                dr.marque_telephone,
                dr.probleme,
                r.date_reparation,
                r.montant_total AS montant_total_reparation,
                r.montant_paye AS montant_paye_reparation,
                r.reste_a_payer AS reste_a_payer_reparation,
                r.statut AS statut_reparation,
                f.statut_paiement,
                f.montant_total AS montant_facture_total,
                f.montant_regle AS montant_regle_facture,
                f.reste_a_payer AS reste_a_payer_facture
            FROM
                demande_reparation dr
            LEFT JOIN
                reparation r ON dr.id_demande = r.id_demande
            LEFT JOIN
                facture f ON r.id_reparation = f.id_reparation
            WHERE
                (dr.nom_complet = :nom_verif OR dr.numero = :numero_verif)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom_verif', $nom_verif, PDO::PARAM_STR);
        $stmt->bindParam(':numero_verif', $numero_verif, PDO::PARAM_STR);
        $stmt->execute();
        $demande_trouvee = $stmt->fetch();

        if ($demande_trouvee) {
            $response['success'] = true;
            $response['message'] = '✅ Demande trouvée !';
            $response['data'] = $demande_trouvee;
        } else {
            $response['message'] = '⚠️ Aucune demande trouvée avec ces informations.';
        }

    } catch (PDOException $e) {
        $response['message'] = '❌ Erreur lors de la vérification de la demande : ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Requête invalide.';
}

echo json_encode($response);

// Fermez la connexion à la base de données
$conn = null;
?>