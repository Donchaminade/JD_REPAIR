<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_complet = trim($_POST['reset_nom_complet'] ?? '');
    $email = trim($_POST['reset_email'] ?? '');
    $new_password = $_POST['reset_new_password'] ?? '';
    $confirm_password = $_POST['reset_confirm_password'] ?? '';

    if ($nom_complet && $email && $new_password && $confirm_password) {
        if ($new_password === $confirm_password) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom_complet = ? AND email = ?");
                $stmt->execute([$nom_complet, $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id_utilisateur = ?");
                    $stmt->execute([$hashed_password, $user['id_utilisateur']]);
                    echo json_encode(['success' => true, 'message' => 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.']);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Nom complet et email incorrects.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>