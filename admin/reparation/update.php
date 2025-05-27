<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";        // Modifier selon ta config
$password = "";            // Modifier selon ta config
$dbname = "reparationdb"; // Modifier selon ta config

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Vérification de la connexion
if (!$conn) {
    die("Connexion échouée: " . mysqli_connect_error());
}

// Vérifier que les données POST sont reçues
if (
    isset($_POST['id_reparation']) &&
    isset($_POST['date_reparation']) &&
    isset($_POST['montant_total']) &&
    isset($_POST['montant_paye']) &&
    isset($_POST['statut'])
) {
    // Nettoyer les données reçues
    $id = intval($_POST['id_reparation']);
    $date_reparation = mysqli_real_escape_string($conn, $_POST['date_reparation']);
    $montant_total = floatval($_POST['montant_total']);
    $montant_paye = floatval($_POST['montant_paye']);
    $statut = mysqli_real_escape_string($conn, $_POST['statut']);

    // Requête de mise à jour
    $sql = "UPDATE reparation SET 
                date_reparation = '$date_reparation', 
                montant_total = $montant_total, 
                montant_paye = $montant_paye, 
                statut = '$statut' 
            WHERE id_reparation = $id";

    if (mysqli_query($conn, $sql)) {
        // Redirection après succès (par exemple vers la liste)
        header("Location: liste_reparations.php?update=success");
        exit();
    } else {
        echo "Erreur lors de la mise à jour : " . mysqli_error($conn);
    }
} else {
    echo "Données manquantes.";
}

mysqli_close($conn);
?>
