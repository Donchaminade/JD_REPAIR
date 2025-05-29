<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
session_start(); // Assurez-vous d'avoir une session active

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_email = htmlspecialchars($_POST['admin_email']);
    $admin_password = $_POST['admin_password']; // NE PAS utiliser htmlspecialchars() sur les mots de passe avant de les vérifier
    $user_id = $_POST['user_id'];

    // Vérifier si l'utilisateur est un administrateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND role = 'admin'");
    $stmt->execute([$admin_email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($admin_password, $admin['mot_de_passe'])) {
        // Authentification réussie de l'administrateur

        // Récupérer le mot de passe de l'utilisateur demandé
        $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user) {
            // Renvoyer le mot de passe à la page appelante
            echo json_encode(['success' => true, 'password' => $user['mot_de_passe']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
        }
    } else {
        // Authentification échouée de l'administrateur
        echo json_encode(['success' => false, 'message' => 'Email ou mot de passe administrateur incorrect.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>