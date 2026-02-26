<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: /profil.php');
    exit;
}

$erreur  = '';
$success = '';
$regions = ['Dakar','Thies','Kaolack','Fatick','Kaffrine','Diourbel','Louga','Saint-Louis','Matam','Tambacounda','Kedougou','Kolda','Sedhiou','Ziguinchor'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');
    $region    = trim($_POST['region'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');

    if (empty($nom) || empty($email) || empty($password)) {
        $erreur = "Veuillez remplir tous les champs obligatoires.";
    } elseif ($password !== $password2) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $erreur = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Vérifier si email existe déjà
        $check = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, password, region, telephone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nom, $email, $hash, $region, $telephone);
            if ($stmt->execute()) {
                // Connexion automatique
                $new_id = $conn->insert_id;
                $_SESSION['user_id']     = $new_id;
                $_SESSION['user_nom']    = $nom;
                $_SESSION['user_email']  = $email;
                $_SESSION['user_region'] = $region;
                header('Location: /profil.php?welcome=1');
                exit;
            } else {
                $erreur = "Erreur lors de l'inscription. Réessayez.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — AgriSénégal</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: linear-gradient(135deg, #0d3320, #1a5c38); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: #fff; border-radius: 24px; padding: 3rem; width: 100%; max-width: 480px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); }
        .logo { text-align: center; margin-bottom: 2rem; }
        .logo-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #2d9e5f, #4cce85); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1rem; box-shadow: 0 8px 25px rgba(45,158,95,0.4); }
        .logo-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: #1a2620; }
        .logo-sub { color: #5a7060; font-size: 0.85rem; margin-top: 4px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group { margin-bottom: 1.1rem; }
        .form-label { display: block; font-size: 0.82rem; font-weight: 700; color: #1a2620; margin-bottom: 0.4rem; }
        .form-label span { color: #e05c1a; }
        .form-input, .form-select { width: 100%; padding: 0.8rem 1rem; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 12px; font-family: 'DM Sans', sans-serif; font-size: 0.875rem; outline: none; transition: all 0.2s; background: #fafafa; color: #1a2620; }
        .form-input:focus, .form-select:focus { border-color: #2d9e5f; background: #fff; box-shadow: 0 0 0 3px rgba(45,158,95,0.1); }
        .btn { width: 100%; padding: 1rem; background: linear-gradient(135deg, #2d9e5f, #4cce85); color: #fff; border: none; border-radius: 12px; font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 6px 20px rgba(45,158,95,0.35); margin-top: 0.5rem; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(45,158,95,0.45); }
        .erreur { background: #fdecea; color: #c0392b; border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.85rem; margin-bottom: 1rem; text-align: center; }
        .links { text-align: center; margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; }
        .links a { color: #5a7060; font-size: 0.85rem; text-decoration: none; }
        .links a:hover { color: #2d9e5f; }
        .links .login-link { color: #2d9e5f; font-weight: 600; }
        .optional { color: #5a7060; font-weight: 400; font-size: 0.75rem; }
        @media (max-width: 480px) { .grid-2 { grid-template-columns: 1fr; } .card { padding: 2rem 1.5rem; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div class="logo-icon">🌱</div>
            <h1 class="logo-title">Créer un compte</h1>
            <p class="logo-sub">AgriSénégal — Rejoignez la communauté</p>
        </div>

        <?php if ($erreur): ?>
        <div class="erreur">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">👤 Nom complet <span>*</span></label>
                <input type="text" name="nom" class="form-input" placeholder="Ex: Mamadou Diallo" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">📧 Email <span>*</span></label>
                <input type="email" name="email" class="form-input" placeholder="votre@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">🔒 Mot de passe <span>*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Min. 6 caractères" required>
                </div>
                <div class="form-group">
                    <label class="form-label">🔒 Confirmer <span>*</span></label>
                    <input type="password" name="password2" class="form-input" placeholder="Répéter" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">📍 Région <span class="optional">(optionnel)</span></label>
                    <select name="region" class="form-select">
                        <option value="">-- Choisir --</option>
                        <?php foreach($regions as $r): ?>
                        <option value="<?= $r ?>" <?= ($_POST['region'] ?? '') === $r ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">📱 Téléphone <span class="optional">(optionnel)</span></label>
                    <input type="tel" name="telephone" class="form-input" placeholder="77 000 00 00" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn">Créer mon compte →</button>
        </form>

        <div class="links">
            <a href="/connexion.php" class="login-link">Déjà un compte ? Se connecter</a>
            <a href="/">← Retour au site</a>
        </div>
    </div>
</body>
</html>