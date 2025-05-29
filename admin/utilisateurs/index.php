<?php
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/admin/auth.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/navbar.php';
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/sidebar.php';

$stmt = $pdo->prepare("SELECT * FROM utilisateurs");
$stmt->execute();
$utilisateurs = $stmt->fetchAll();

$stmtColumns = $pdo->prepare("DESCRIBE utilisateurs");
$stmtColumns->execute();
$columnsForForm = $stmtColumns->fetchAll(PDO::FETCH_COLUMN);
$columnsForForm = array_filter($columnsForForm, fn($column) => !in_array($column, ['id_utilisateur']));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_utilisateur'])) {
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
        $sql = "INSERT INTO utilisateurs (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $insertStmt = $pdo->prepare($sql);
        $insertStmt->execute($values);
        echo '<script>window.location.href = "utilisateurs.php";</script>';
        exit();
    }
}
?>

<div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-all duration-300 md:ml-64">
    <div class="container mx-auto py-6 px-4">
        <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
            <a href="#addModal" onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow">
                + Ajouter un utilisateur
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg">
            <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                <tr>
                <th class="px-6 py-3">Nom complet</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Rôle</th>
                <th class="px-6 py-3">Mot de passe</th>
                <th class="px-6 py-3 no-export">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4"><?= htmlspecialchars($utilisateur['nom_complet']) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($utilisateur['role']) ?></td>
                    <td class="px-6 py-4">
                    <button onclick="openAuthModal('<?= $utilisateur['id_utilisateur'] ?>')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-1 px-2 rounded inline-flex items-center">
                        <i class="fa-solid fa-eye"></i>
                        <span>Voir</span>
                    </button>
                    </td>
                    <td class="px-6 py-4 flex gap-2 no-export">
                    <button onclick="openDetailModal(<?= htmlspecialchars(json_encode($utilisateur)) ?>)" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                        <i class="fa-solid fa-eye "></i> Voir
                    </button>
                    <button onclick="openUpdateModal(<?= htmlspecialchars(json_encode($utilisateur)) ?>)" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs flex items-center gap-1">
                        <i class="fa-solid fa-edit "></i> Modifier
                    </button>
                    <a href="delete_utilisateur.php?id_utilisateur=<?= $utilisateur['id_utilisateur'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-800 text-xs flex items-center gap-1">
                        <i class="fa-solid fa-trash "></i> Supprimer
                    </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>



<div id="authModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-md shadow-lg relative">
        <button onclick="closeAuthModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center">Authentification Administrateur</h2>

        <form method="POST" action="verify_admin.php" class="space-y-4">
            <input type="hidden" name="user_id" id="auth_user_id">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Administrateur</label>
                <input type="email" name="admin_email" id="admin_email" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mot de passe Administrateur</label>
                <input type="password" name="admin_password" id="admin_password" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div class="text-center pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md">Vérifier</button>
            </div>
        </form>
        <div id="auth_message" class="text-red-500 text-sm mt-2 text-center"></div>
    </div>
</div>




<div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-md shadow-lg relative">
        <button onclick="closeUpdateModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center">Modifier l'Utilisateur</h2>

        <form method="POST" action="update_utilisateur.php" class="space-y-4">
            <input type="hidden" name="id_utilisateur" id="update_id_utilisateur">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom complet</label>
                <input type="text" name="nom_complet" id="update_nom_complet" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="update_email" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rôle</label>
                <select name="role" id="update_role" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <option value="admin">Admin</option>
                    <option value="technicien">Technicien</option>
                </select>
            </div>

            <div class="text-center pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<div id="addModal" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-full bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800 w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('addModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Ajouter un nouvel utilisateur</h3>
        <form method="POST" action="">
            <?php foreach ($columnsForForm as $column): ?>
                <div class="mb-4">
                    <label for="<?= $column ?>" class="block text-sm font-medium text-gray-700 dark:text-white"><?= ucfirst(str_replace('_', ' ', $column)) ?></label>
                    <?php if ($column === 'role'): ?>
                        <select name="<?= $column ?>" id="<?= $column ?>" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white">
                            <option value="admin">Admin</option>
                            <option value="technicien">Technicien</option>
                        </select>
                    <?php elseif ($column === 'mot_de_passe'): ?>
                        <input type="password" name="<?= $column ?>" id="<?= $column ?>" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white" />
                    <?php else: ?>
                        <input type="text" name="<?= $column ?>" id="<?= $column ?>" required class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 dark:text-white" />
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <div class="flex justify-end">
                <button type="submit" name="ajouter_utilisateur" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 hidden bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-md p-8 relative">
        <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
        </button>
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Détails de l'Utilisateur</h2>

        <div id="detailContent" class="space-y-4 text-base text-gray-700 dark:text-white divide-y divide-gray-300 dark:divide-gray-600">
            </div>
    </div>
</div>

<script>
    function openUpdateModal(utilisateur) {
        document.getElementById('updateModal').classList.remove('hidden');
        document.getElementById('update_id_utilisateur').value = utilisateur.id_utilisateur;
        document.getElementById('update_nom_complet').value = utilisateur.nom_complet;
        document.getElementById('update_email').value = utilisateur.email;
        document.getElementById('update_role').value = utilisateur.role;
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').classList.add('hidden');
    }

    function openDetailModal(utilisateur) {
        const detailModal = document.getElementById('detailModal');
        const detailContent = document.getElementById('detailContent');

        detailContent.innerHTML = `
            <p><strong>Nom complet:</strong> ${utilisateur.nom_complet}</p>
            <p><strong>Email:</strong> ${utilisateur.email}</p>
            <p><strong>Rôle:</strong> ${utilisateur.role}</p>
        `;

        detailModal.classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }



    function openAuthModal(userId) {
    document.getElementById('authModal').classList.remove('hidden');
    document.getElementById('auth_user_id').value = userId;
}

function closeAuthModal() {
    document.getElementById('authModal').classList.add('hidden');
}

document.querySelector('#authModal form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('verify_admin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher le mot de passe (avec prudence !)
            alert('Mot de passe : ' + data.password);
            closeAuthModal();
        } else {
            // Afficher le message d'erreur
            document.getElementById('auth_message').textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('auth_message').textContent = 'Erreur de communication avec le serveur.';
    });
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/includes/footer.php'; ?>