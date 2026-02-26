<?php
session_start();
include '../includes/db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: /admin/index.php');
        exit;
    } else {
        $erreur = "Identifiants incorrects !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — AgriSénégal</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #0d3320, #1a5c38);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #2d9e5f, #4cce85);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 25px rgba(45,158,95,0.4);
        }
        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a2620;
        }
        .login-sub { color: #5a7060; font-size: 0.85rem; margin-top: 4px; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #1a2620;
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1.5px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #2d9e5f;
            box-shadow: 0 0 0 3px rgba(45,158,95,0.1);
        }
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #2d9e5f, #4cce85);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(45,158,95,0.35);
            margin-top: 0.5rem;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(45,158,95,0.45); }
        .erreur {
            background: #fdecea;
            color: #c0392b;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #5a7060;
            font-size: 0.85rem;
            text-decoration: none;
        }
        .back-link:hover { color: #2d9e5f; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div class="login-logo-icon">🌱</div>
            <h1 class="login-title">Espace Admin</h1>
            <p class="login-sub">AgriSénégal — Tableau de bord</p>
        </div>

        <?php if ($erreur): ?>
        <div class="erreur">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label class="form-label">👤 Nom d'utilisateur</label>
                <input type="text" name="username" class="form-input" placeholder="admin" required>
            </div>
            <div class="form-group">
                <label class="form-label">🔒 Mot de passe</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Se connecter →</button>
        </form>
        <a href="../index.php" class="back-link">← Retour au site</a>
    </div>
</body>
</html>