<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if (isset($_GET['id_traitement'])) {
    $id_traitement = intval($_GET['id_traitement']); // sécurisation

    // Suppression du traitement
    $stmt = $pdo->prepare("DELETE FROM traitement WHERE id_traitement = ?");
    $stmt->execute([$id_traitement]);

    header("Location: index.php"); // Redirection vers la page d'index
    exit();
}
?>
