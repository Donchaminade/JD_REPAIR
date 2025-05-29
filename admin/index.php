<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/JD_REPAIR/config/db.php';

$error = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_name'] = $user['nom_complet'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur de connexion à la base de données.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        #bg-canvas {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>


<body class="bg-gray-900 flex items-center justify-center min-h-screen relative overflow-hidden">
    <canvas id="bg-canvas"></canvas>
    <div class="w-full max-w-lg bg-white/90 dark:bg-gray-800/80 rounded-2xl shadow-2xl p-12 backdrop-blur z-10 relative">
        <div class="flex justify-center mb-6">
            <img src="../jd.png" alt="JD Logo" class="h-16 w-auto">
        </div>
        <br><br>
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-800 dark:text-white">Connexion Admin</h2>
        <?php if ($error): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="post" class="space-y-6">
            <div>
                <label for="email" class="block text-gray-700 dark:text-gray-200 mb-1">Email</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div>
                <label for="password" class="block text-gray-700 dark:text-gray-200 mb-1">Mot de passe</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition">Se connecter</button>
        </form>
        <a href="#" onclick="openResetModal()" class="text-sm text-blue-600 hover:underline block mt-4 text-center">Mot de passe oublié ?</a>
    </div>

    <div id="resetModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl w-full max-w-md shadow-lg relative">
        <button onclick="closeResetModal()" class="absolute top-4 right-4 text-gray-700 dark:text-white hover:text-red-500">
            <i data-lucide="x" class="w-6 h-6">❌</i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center">Réinitialiser le Mot de Passe</h2>
        <form id="resetForm" method="POST" action="" class="space-y-4">
            <div>
                <label for="reset_nom_complet" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom Complet</label>
                <input type="text" name="reset_nom_complet" id="reset_nom_complet" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="reset_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="reset_email" id="reset_email" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="reset_new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nouveau Mot de Passe</label>
                <input type="password" name="reset_new_password" id="reset_new_password" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="reset_confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmer le Nouveau Mot de Passe</label>
                <input type="password" name="reset_confirm_password" id="reset_confirm_password" required class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-800 dark:text-white">
            </div>
            <div class="text-center pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md">Réinitialiser</button>
            </div>
        </form>
        <div id="reset_message" class="text-sm mt-2 text-center"></div>
    </div>
</div>

<script>
    // Animation molécules
    const canvas = document.getElementById('bg-canvas');
    const ctx = canvas.getContext('2d');
    let width = window.innerWidth;
    let height = window.innerHeight;
    canvas.width = width;
    canvas.height = height;

    function resizeCanvas() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
    }
    window.addEventListener('resize', resizeCanvas);

    // Molécules
    const molecules = [];
    const moleculeCount = 40;
    for (let i = 0; i < moleculeCount; i++) {
        molecules.push({
            x: Math.random() * width,
            y: Math.random() * height,
            r: 8 + Math.random() * 8,
            dx: (Math.random() - 0.5) * 1.2,
            dy: (Math.random() - 0.5) * 1.2,
            color: `rgba(59,130,246,${0.15 + Math.random() * 0.15})`
        });
    }

    function draw() {
        ctx.clearRect(0, 0, width, height);

        // Dessiner les liens
        for (let i = 0; i < molecules.length; i++) {
            for (let j = i + 1; j < molecules.length; j++) {
                const dx = molecules[i].x - molecules[j].x;
                const dy = molecules[i].y - molecules[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 140) {
                    ctx.strokeStyle = 'rgba(59,130,246,0.10)';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(molecules[i].x, molecules[i].y);
                    ctx.lineTo(molecules[j].x, molecules[j].y);
                    ctx.stroke();
                }
            }
        }

        // Dessiner les molécules
        for (const m of molecules) {
            ctx.beginPath();
            ctx.arc(m.x, m.y, m.r, 0, Math.PI * 2);
            ctx.fillStyle = m.color;
            ctx.shadowColor = '#3b82f6';
            ctx.shadowBlur = 10;
            ctx.fill();
            ctx.shadowBlur = 0;
        }
    }

    function update() {
        for (const m of molecules) {
            m.x += m.dx;
            m.y += m.dy;
            if (m.x < 0 || m.x > width) m.dx *= -1;
            if (m.y < 0 || m.y > height) m.dy *= -1;
        }
    }

    function animate() {
        update();
        draw();
        requestAnimationFrame(animate);
    }
    animate();

    // Modal reset
    function openResetModal() {
        document.getElementById('resetModal').classList.remove('hidden');
    }

    function closeResetModal() {
        document.getElementById('resetModal').classList.add('hidden');
    }

    document.querySelector('#resetForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('reset_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('reset_message').textContent = data.message;
            if (data.success) {
                document.getElementById('resetForm').reset();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('reset_message').textContent = 'Erreur de communication avec le serveur.';
        });
    });
</script>
</body>
</html>
