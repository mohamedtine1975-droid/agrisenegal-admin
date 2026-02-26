<?php
include '../includes/db.php';
include 'auth.php';
check_auth();

$message = '';
$edit_culture = null;

// MODIFIER
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_culture = $conn->query("SELECT * FROM cultures WHERE id = $id")->fetch_assoc();
}

// SUPPRIMER
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM cultures WHERE id = $id");
    header('Location: /admin/cultures.php?deleted=1');
    exit;
}

if (isset($_GET['deleted'])) $message = "✅ Culture supprimée !";
if (isset($_GET['updated'])) $message = "✅ Culture mise à jour !";
if (isset($_GET['added']))   $message = "✅ Culture ajoutée !";

// SAUVEGARDER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom            = trim($_POST['nom']);
    $emoji          = trim($_POST['emoji']);
    $description    = trim($_POST['description']);
    $regions        = trim($_POST['regions']);
    $mois_plantation = trim($_POST['mois_plantation']);
    $mois_recolte   = trim($_POST['mois_recolte']);
    $duree_culture  = trim($_POST['duree_culture']);
    $maladies       = trim($_POST['maladies']);
    $prix_moyen     = (float)$_POST['prix_moyen'];
    $unite_prix     = trim($_POST['unite_prix']);
    $couleur        = trim($_POST['couleur']);

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $id = (int)$_POST['edit_id'];
        $stmt = $conn->prepare("UPDATE cultures SET nom=?, emoji=?, description=?, regions=?, mois_plantation=?, mois_recolte=?, duree_culture=?, maladies=?, prix_moyen=?, unite_prix=?, couleur=? WHERE id=?");
        $stmt->bind_param("ssssssssdssi", $nom, $emoji, $description, $regions, $mois_plantation, $mois_recolte, $duree_culture, $maladies, $prix_moyen, $unite_prix, $couleur, $id);
        $stmt->execute();
        header('Location: /admin/cultures.php?updated=1');
        exit;
    } else {
        $stmt = $conn->prepare("INSERT INTO cultures (nom, emoji, description, regions, mois_plantation, mois_recolte, duree_culture, maladies, prix_moyen, unite_prix, couleur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssdss", $nom, $emoji, $description, $regions, $mois_plantation, $mois_recolte, $duree_culture, $maladies, $prix_moyen, $unite_prix, $couleur);
        $stmt->execute();
        header('Location: /admin/cultures.php?added=1');
        exit;
    }
}

$cultures = $conn->query("SELECT * FROM cultures ORDER BY nom ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cultures — Admin AgriSénégal</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --green: #2d9e5f; --green-dark: #0d3320; --green-light: #4cce85; --text: #1a2620; --text-light: #5a7060; --bg: #f4f7f5; --white: #fff; --sidebar-w: 250px; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-w); background: var(--green-dark); min-height: 100vh; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-logo { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; gap: 10px; }
        .sidebar-logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--green), var(--green-light)); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        .sidebar-logo-text { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: #fff; }
        .sidebar-logo-text span { color: var(--green-light); }
        .sidebar-nav { padding: 1.5rem 0; flex: 1; }
        .sidebar-section { font-size: 0.65rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.3); padding: 0 1.5rem; margin: 1rem 0 0.5rem; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 0.7rem 1.5rem; color: rgba(255,255,255,0.65); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; border-left: 3px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { color: #fff; background: rgba(255,255,255,0.06); border-left-color: var(--green-light); }
        .sidebar-link .icon { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.08); }
        .admin-info { display: flex; align-items: center; gap: 10px; margin-bottom: 1rem; }
        .admin-avatar { width: 36px; height: 36px; background: linear-gradient(135deg, var(--green), var(--green-light)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
        .admin-name { color: #fff; font-size: 0.85rem; font-weight: 600; }
        .admin-role { color: rgba(255,255,255,0.4); font-size: 0.75rem; }
        .btn-logout { display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.5); text-decoration: none; font-size: 0.85rem; transition: color 0.2s; }
        .btn-logout:hover { color: #ff6b6b; }

        /* MAIN */
        .main { margin-left: var(--sidebar-w); flex: 1; padding: 2rem; }
        .page-header { margin-bottom: 2rem; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; color: var(--text); }
        .page-sub { color: var(--text-light); font-size: 0.9rem; margin-top: 4px; }

        /* MOBILE NAV */
        .mobile-nav { display: none; background: var(--green-dark); padding: 0.75rem 1rem; margin: -1rem -1rem 1.5rem; }
        .mobile-nav a { color: rgba(255,255,255,0.7); text-decoration: none; margin-right: 1rem; font-size: 0.85rem; }

        /* LAYOUT */
        .content-grid { display: grid; grid-template-columns: 350px 1fr; gap: 1.5rem; align-items: start; }

        /* FORM */
        .form-card { background: var(--white); border-radius: 16px; padding: 1.75rem; box-shadow: 0 2px 12px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.04); position: sticky; top: 2rem; }
        .form-card-title { font-weight: 700; font-size: 1rem; color: var(--text); margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.82rem; font-weight: 700; color: var(--text); margin-bottom: 0.4rem; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: var(--text); background: #fafafa; outline: none; transition: all 0.2s; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--green); background: #fff; box-shadow: 0 0 0 3px rgba(45,158,95,0.1); }
        .form-textarea { min-height: 80px; resize: vertical; line-height: 1.5; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .btn-save { width: 100%; padding: 0.85rem; background: linear-gradient(135deg, var(--green), var(--green-light)); color: #fff; border: none; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 0.95rem; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(45,158,95,0.3); margin-top: 0.5rem; }
        .btn-save:hover { transform: translateY(-1px); }
        .btn-cancel { display: block; text-align: center; margin-top: 0.75rem; color: var(--text-light); font-size: 0.85rem; text-decoration: none; }
        .btn-cancel:hover { color: #e05c1a; }
        .message { background: #e8f5ee; color: var(--green); border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem; border: 1px solid rgba(45,158,95,0.2); }

        /* CULTURES GRID */
        .cultures-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
        .culture-card { background: var(--white); border-radius: 16px; padding: 1.25rem; box-shadow: 0 2px 12px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.04); transition: all 0.2s; }
        .culture-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .culture-card-header { display: flex; align-items: center; gap: 10px; margin-bottom: 0.75rem; }
        .culture-emoji { font-size: 2rem; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
        .culture-name { font-weight: 700; font-size: 1rem; color: var(--text); }
        .culture-prix { font-size: 0.8rem; color: var(--text-light); }
        .culture-info { font-size: 0.78rem; color: var(--text-light); line-height: 1.6; margin-bottom: 0.75rem; }
        .culture-actions { display: flex; gap: 0.5rem; }
        .btn-edit { color: var(--green); text-decoration: none; font-size: 0.75rem; font-weight: 600; padding: 0.3rem 0.75rem; border-radius: 6px; background: #e8f5ee; transition: all 0.2s; flex: 1; text-align: center; }
        .btn-edit:hover { background: var(--green); color: #fff; }
        .btn-del { color: #e05c1a; text-decoration: none; font-size: 0.75rem; font-weight: 600; padding: 0.3rem 0.75rem; border-radius: 6px; background: #fef3e8; transition: all 0.2s; flex: 1; text-align: center; }
        .btn-del:hover { background: #e05c1a; color: #fff; }

        /* COULEURS */
        .color-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.4rem; }
        .color-option { display: none; }
        .color-label { display: block; height: 30px; border-radius: 8px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s; }
        .color-option:checked + label { border-color: var(--text); transform: scale(0.9); }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; padding: 1rem; }
            .mobile-nav { display: block; }
            .content-grid { grid-template-columns: 1fr; }
            .form-card { position: static; }
            .page-title { font-size: 1.4rem; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">🌱</div>
        <span class="sidebar-logo-text">Agri<span>Sénégal</span></span>
    </div>
    <nav class="sidebar-nav">
        <span class="sidebar-section">Menu</span>
        <a href="/admin/index.php" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
        <a href="/admin/prix.php" class="sidebar-link"><span class="icon">📈</span> Prix du marché</a>
        <a href="/admin/cultures.php" class="sidebar-link active"><span class="icon">🌱</span> Cultures</a>
        <span class="sidebar-section">Site</span>
        <a href="/" class="sidebar-link" target="_blank"><span class="icon">🌍</span> Voir le site</a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar">👤</div>
            <div>
                <div class="admin-name"><?= htmlspecialchars($_SESSION['admin_username']) ?></div>
                <div class="admin-role">Administrateur</div>
            </div>
        </div>
        <a href="/admin/logout.php" class="btn-logout">🚪 Se déconnecter</a>
    </div>
</aside>

<!-- MAIN -->
<main class="main">

    <!-- MENU MOBILE -->
    <div class="mobile-nav">
        <a href="/admin/index.php">📊 Dashboard</a>
        <a href="/admin/prix.php">📈 Prix</a>
        <a href="/admin/cultures.php">🌱 Cultures</a>
        <a href="/admin/logout.php">🚪 Logout</a>
    </div>

    <div class="page-header">
        <h1 class="page-title">Gestion des Cultures</h1>
        <p class="page-sub">Ajoutez, modifiez ou supprimez les cultures</p>
    </div>

    <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="content-grid">

        <!-- FORMULAIRE -->
        <div class="form-card">
            <div class="form-card-title"><?= $edit_culture ? '✏️ Modifier la culture' : '➕ Ajouter une culture' ?></div>
            <form method="POST" action="/admin/cultures.php">
                <?php if ($edit_culture): ?>
                <input type="hidden" name="edit_id" value="<?= $edit_culture['id'] ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">🌱 Nom</label>
                        <input type="text" name="nom" class="form-input" placeholder="Ex: Arachide" required value="<?= htmlspecialchars($edit_culture['nom'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">😀 Emoji</label>
                        <input type="text" name="emoji" class="form-input" placeholder="🥜" required value="<?= htmlspecialchars($edit_culture['emoji'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">📝 Description</label>
                    <textarea name="description" class="form-textarea" placeholder="Description de la culture..."><?= htmlspecialchars($edit_culture['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">📍 Régions</label>
                    <input type="text" name="regions" class="form-input" placeholder="Ex: Kaolack, Fatick, Kaffrine" value="<?= htmlspecialchars($edit_culture['regions'] ?? '') ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">📅 Plantation</label>
                        <input type="text" name="mois_plantation" class="form-input" placeholder="Ex: Juin-Juillet" value="<?= htmlspecialchars($edit_culture['mois_plantation'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">🌾 Récolte</label>
                        <input type="text" name="mois_recolte" class="form-input" placeholder="Ex: Octobre-Novembre" value="<?= htmlspecialchars($edit_culture['mois_recolte'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">⏱️ Durée culture</label>
                        <input type="text" name="duree_culture" class="form-input" placeholder="Ex: 90-120 jours" value="<?= htmlspecialchars($edit_culture['duree_culture'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">💰 Prix moyen</label>
                        <input type="number" name="prix_moyen" class="form-input" step="0.01" placeholder="Ex: 305" value="<?= htmlspecialchars($edit_culture['prix_moyen'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">📏 Unité prix</label>
                    <select name="unite_prix" class="form-select">
                        <option value="FCFA/kg" <?= ($edit_culture['unite_prix'] ?? '') === 'FCFA/kg' ? 'selected' : '' ?>>FCFA/kg</option>
                        <option value="FCFA/sac" <?= ($edit_culture['unite_prix'] ?? '') === 'FCFA/sac' ? 'selected' : '' ?>>FCFA/sac</option>
                        <option value="FCFA/tonne" <?= ($edit_culture['unite_prix'] ?? '') === 'FCFA/tonne' ? 'selected' : '' ?>>FCFA/tonne</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">🦠 Maladies courantes</label>
                    <textarea name="maladies" class="form-textarea" placeholder="Ex: Cercosporiose, Rosette..."><?= htmlspecialchars($edit_culture['maladies'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">🎨 Couleur</label>
                    <div class="color-grid">
                        <?php
                        $couleurs = [
                            'green'  => '#2d9e5f',
                            'orange' => '#e05c1a',
                            'yellow' => '#e8a830',
                            'red'    => '#c0392b',
                            'blue'   => '#1a6fa8',
                            'brown'  => '#7d5a3c',
                            'purple' => '#8e44ad',
                            'teal'   => '#16a085',
                            'pink'   => '#e91e8c',
                            'gray'   => '#5a7060',
                        ];
                        foreach($couleurs as $key => $hex):
                        $checked = ($edit_culture['couleur'] ?? 'green') === $key ? 'checked' : '';
                        ?>
                        <input type="radio" name="couleur" value="<?= $key ?>" id="c-<?= $key ?>" class="color-option" <?= $checked ?>>
                        <label for="c-<?= $key ?>" class="color-label" style="background: <?= $hex ?>;"></label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn-save">
                    <?= $edit_culture ? '💾 Enregistrer' : '➕ Ajouter la culture' ?>
                </button>
                <?php if ($edit_culture): ?>
                <a href="/admin/cultures.php" class="btn-cancel">✕ Annuler</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- LISTE CULTURES -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <span style="font-weight: 700; font-size: 1rem; color: var(--text);">Toutes les cultures</span>
                <span style="font-size: 0.8rem; color: var(--text-light); background: #f3f4f6; padding: 0.25rem 0.75rem; border-radius: 20px;"><?= count($cultures) ?> cultures</span>
            </div>
            <div class="cultures-grid">
                <?php foreach($cultures as $c): ?>
                <div class="culture-card">
                    <div class="culture-card-header">
                        <div class="culture-emoji" style="background: #f0faf5;"><?= $c['emoji'] ?></div>
                        <div>
                            <div class="culture-name"><?= htmlspecialchars($c['nom']) ?></div>
                            <div class="culture-prix"><?= number_format($c['prix_moyen'], 0, ',', ' ') ?> <?= $c['unite_prix'] ?></div>
                        </div>
                    </div>
                    <div class="culture-info">
                        📅 <?= htmlspecialchars($c['mois_plantation']) ?> → <?= htmlspecialchars($c['mois_recolte']) ?><br>
                        📍 <?= htmlspecialchars(substr($c['regions'], 0, 50)) ?>...
                    </div>
                    <div class="culture-actions">
                        <a href="/admin/cultures.php?edit=<?= $c['id'] ?>" class="btn-edit">✏️ Modifier</a>
                        <a href="/admin/cultures.php?delete=<?= $c['id'] ?>" class="btn-del" onclick="return confirm('Supprimer <?= htmlspecialchars($c['nom']) ?> ?')">🗑️ Suppr</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</main>

</body>
</html>