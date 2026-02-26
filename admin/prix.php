<?php
include '../includes/db.php';
include 'auth.php';
check_auth();

$message = '';
$edit_prix = null;

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_prix = $conn->query("SELECT p.*, c.nom FROM prix_marche p JOIN cultures c ON p.culture_id = c.id WHERE p.id = $id")->fetch_assoc();
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM prix_marche WHERE id = $id");
    header('Location: /admin/prix.php?deleted=1');
    exit;
}

if (isset($_GET['deleted'])) $message = "✅ Prix supprimé avec succès !";
if (isset($_GET['updated'])) $message = "✅ Prix mis à jour avec succès !";
if (isset($_GET['added']))   $message = "✅ Prix ajouté avec succès !";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $culture_id = (int)$_POST['culture_id'];
    $region     = trim($_POST['region']);
    $prix       = (float)$_POST['prix'];
    $unite      = trim($_POST['unite']);
    $tendance   = $_POST['tendance'];
    $date_maj   = date('Y-m-d');

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $id = (int)$_POST['edit_id'];
        $stmt = $conn->prepare("UPDATE prix_marche SET culture_id=?, region=?, prix=?, unite=?, tendance=?, date_maj=? WHERE id=?");
        $stmt->bind_param("isdsssi", $culture_id, $region, $prix, $unite, $tendance, $date_maj, $id);
        $stmt->execute();
        header('Location: /admin/prix.php?updated=1');
        exit;
    } else {
        $stmt = $conn->prepare("INSERT INTO prix_marche (culture_id, region, prix, unite, tendance, date_maj) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsss", $culture_id, $region, $prix, $unite, $tendance, $date_maj);
        $stmt->execute();
        header('Location: /admin/prix.php?added=1');
        exit;
    }
}

$tous_prix    = $conn->query("SELECT p.*, c.nom, c.emoji FROM prix_marche p JOIN cultures c ON p.culture_id = c.id ORDER BY c.nom ASC, p.region ASC")->fetch_all(MYSQLI_ASSOC);
$cultures_list = $conn->query("SELECT id, nom, emoji FROM cultures ORDER BY nom ASC")->fetch_all(MYSQLI_ASSOC);
$regions_senegal = ['Dakar','Thies','Kaolack','Fatick','Kaffrine','Diourbel','Louga','Saint-Louis','Matam','Tambacounda','Kedougou','Kolda','Sedhiou','Ziguinchor'];
?>
<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prix du Marché — Admin AgriSénégal</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    :root { 
        --green: #2d9e5f; 
        --green-dark: #0d3320; 
        --green-light: #4cce85; 
        --text: #1a2620; 
        --text-light: #5a7060; 
        --bg: #f4f7f5; 
        --white: #fff; 
        --sidebar-w: 250px; 
    }
    body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

    /* SIDEBAR (Desktop) */
    .sidebar { width: var(--sidebar-w); background: var(--green-dark); min-height: 100vh; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; z-index: 100; }
    .sidebar-logo { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; gap: 10px; }
    .sidebar-logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--green), var(--green-light)); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .sidebar-logo-text { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: #fff; }
    .sidebar-logo-text span { color: var(--green-light); }
    .sidebar-nav { padding: 1.5rem 0; flex: 1; }
    .sidebar-section { font-size: 0.65rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.3); padding: 0 1.5rem; margin: 1rem 0 0.5rem; display: block; }
    .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 0.7rem 1.5rem; color: rgba(255,255,255,0.65); text-decoration: none; font-size: 0.9rem; transition: all 0.2s; border-left: 3px solid transparent; }
    .sidebar-link:hover, .sidebar-link.active { color: #fff; background: rgba(255,255,255,0.06); border-left-color: var(--green-light); }
    .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.08); }
    .admin-info { display: flex; align-items: center; gap: 10px; margin-bottom: 1rem; }
    .admin-avatar { width: 32px; height: 32px; background: var(--green); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .admin-name { color: #fff; font-size: 0.85rem; font-weight: 600; }
    .btn-logout { color: rgba(255,255,255,0.5); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; }

    /* MAIN CONTENT */
    .main { margin-left: var(--sidebar-w); flex: 1; padding: 2rem; width: 100%; position: relative; }
    .page-header { margin-bottom: 2rem; }
    .page-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; }
    .page-sub { color: var(--text-light); font-size: 0.9rem; margin-top: 4px; }

    /* MOBILE NAV (Fixed bottom) */
    .mobile-nav { display: none; }

    /* FORM & TABLE GRID */
    .content-grid { display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; align-items: start; }
    .form-card { background: var(--white); border-radius: 16px; padding: 1.75rem; box-shadow: 0 2px 12px rgba(0,0,0,0.05); position: sticky; top: 2rem; }
    .form-group { margin-bottom: 1.1rem; }
    .form-label { display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.4rem; }
    .form-input, .form-select { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px; outline: none; font-family: inherit; }
    
    .tendance-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }
    .tendance-option { display: none; }
    .tendance-label { display: block; text-align: center; padding: 0.6rem; border-radius: 10px; border: 1.5px solid rgba(0,0,0,0.1); cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: 0.2s; }
    #t-hausse:checked + label { background: #e8f5ee; border-color: var(--green); color: var(--green); }
    #t-stable:checked + label { background: #f3f4f6; border-color: #6b7280; color: #6b7280; }
    #t-baisse:checked + label { background: #fef3e8; border-color: #e05c1a; color: #e05c1a; }
    
    .btn-save { width: 100%; padding: 0.85rem; background: var(--green); color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; margin-top: 0.5rem; }
    .btn-cancel { display: block; text-align: center; margin-top: 1rem; color: var(--text-light); text-decoration: none; font-size: 0.85rem; }

    /* TABLE */
    .table-card { background: var(--white); border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden; }
    .table-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.06); display: flex; justify-content: space-between; align-items: center; }
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 600px; }
    th { text-align: left; font-size: 0.75rem; text-transform: uppercase; color: var(--text-light); padding: 1rem 1.5rem; background: #fafafa; }
    td { padding: 1rem 1.5rem; font-size: 0.875rem; border-bottom: 1px solid rgba(0,0,0,0.04); }
    .badge { font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 20px; }
    .badge-hausse { background: #e8f5ee; color: #2d9e5f; }
    .badge-stable { background: #f3f4f6; color: #6b7280; }
    .badge-baisse { background: #fef3e8; color: #e05c1a; }
    .btn-edit { color: var(--green); text-decoration: none; font-weight: 600; font-size: 0.8rem; padding: 0.35rem 0.7rem; background: #e8f5ee; border-radius: 8px; margin-right: 5px; }
    .btn-del { color: #e05c1a; text-decoration: none; font-weight: 600; font-size: 0.8rem; padding: 0.35rem 0.7rem; background: #fef3e8; border-radius: 8px; }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .content-grid { grid-template-columns: 1fr; }
        .form-card { position: static; margin-bottom: 1.5rem; }
    }

    @media (max-width: 768px) {
        .sidebar { display: none !important; }
        .main { margin-left: 0 !important; padding: 1rem !important; padding-bottom: 5rem !important; }
        .mobile-nav {
            display: flex !important;
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000;
            background: var(--green-dark); padding: 0.8rem;
            justify-content: space-around; box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
        }
        .mobile-nav a { color: #fff; text-decoration: none; font-size: 0.75rem; display: flex; flex-direction: column; align-items: center; gap: 4px; opacity: 0.7; }
        .mobile-nav a.active { opacity: 1; color: var(--green-light); }
    }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">🌱</div>
        <span class="sidebar-logo-text">Agri<span>Sénégal</span></span>
    </div>
    <nav class="sidebar-nav">
        <span class="sidebar-section">Menu</span>
        <a href="/admin/index.php" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
        <a href="/admin/prix.php" class="sidebar-link active"><span class="icon">📈</span> Prix du marché</a>
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
        <a href="/admin/logout.php" class="btn-logout">🚪 Déconnexion</a>
    </div>
</aside>

<nav class="mobile-nav">
    <a href="/admin/index.php">📊 <span>Dash</span></a>
    <a href="/admin/prix.php" class="active">📈 <span>Prix</span></a>
    <a href="/">🌍 <span>Site</span></a>
    <a href="/admin/logout.php">🚪 <span>Logout</span></a>
</nav>

<main class="main">
    <div class="page-header">
        <h1 class="page-title">Prix du Marché</h1>
        <p class="page-sub">Gérez les cotations journalières par région au Sénégal</p>
    </div>

    <?php if ($message): ?>
        <div style="background: #e8f5ee; color: var(--green); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 600; border: 1px solid rgba(45,158,95,0.2);">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="content-grid">
        <div class="form-card">
            <h3 style="margin-bottom: 1.5rem;"><?= $edit_prix ? '✏️ Modifier le prix' : '➕ Nouveau prix' ?></h3>
            <form method="POST" action="/admin/prix.php">
                <?php if ($edit_prix): ?>
                    <input type="hidden" name="edit_id" value="<?= $edit_prix['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label">🌱 Culture</label>
                    <select name="culture_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach($cultures_list as $cl): ?>
                            <option value="<?= $cl['id'] ?>" <?= ($edit_prix && $edit_prix['culture_id'] == $cl['id']) ? 'selected' : '' ?>>
                                <?= $cl['emoji'] ?> <?= htmlspecialchars($cl['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">📍 Région</label>
                    <select name="region" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach($regions_senegal as $r): ?>
                            <option value="<?= $r ?>" <?= ($edit_prix && $edit_prix['region'] == $r) ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">💰 Prix (FCFA)</label>
                    <input type="number" name="prix" step="0.01" class="form-input" required value="<?= $edit_prix ? $edit_prix['prix'] : '' ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">📏 Unité</label>
                    <select name="unite" class="form-select">
                        <option value="FCFA/kg" <?= ($edit_prix && $edit_prix['unite'] == 'FCFA/kg') ? 'selected' : '' ?>>FCFA/kg</option>
                        <option value="FCFA/sac" <?= ($edit_prix && $edit_prix['unite'] == 'FCFA/sac') ? 'selected' : '' ?>>FCFA/sac</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">📊 Tendance</label>
                    <div class="tendance-grid">
                        <input type="radio" name="tendance" value="hausse" id="t-hausse" class="tendance-option" <?= (!$edit_prix || $edit_prix['tendance'] == 'hausse') ? 'checked' : '' ?>>
                        <label for="t-hausse" class="tendance-label">↑ Hausse</label>
                        
                        <input type="radio" name="tendance" value="stable" id="t-stable" class="tendance-option" <?= ($edit_prix && $edit_prix['tendance'] == 'stable') ? 'checked' : '' ?>>
                        <label for="t-stable" class="tendance-label">→ Stable</label>
                        
                        <input type="radio" name="tendance" value="baisse" id="t-baisse" class="tendance-option" <?= ($edit_prix && $edit_prix['tendance'] == 'baisse') ? 'checked' : '' ?>>
                        <label for="t-baisse" class="tendance-label">↓ Baisse</label>
                    </div>
                </div>

                <button type="submit" class="btn-save"><?= $edit_prix ? '💾 Mettre à jour' : '➕ Ajouter' ?></button>
                <?php if ($edit_prix): ?>
                    <a href="/admin/prix.php" class="btn-cancel">✕ Annuler</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-card">
            <div class="table-header">
                <span style="font-weight: 700;">Tous les prix</span>
                <span style="font-size: 0.8rem; background: #eee; padding: 2px 10px; border-radius: 10px;"><?= count($tous_prix) ?> entrées</span>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Culture</th><th>Région</th><th>Prix</th><th>Tendance</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tous_prix as $p): ?>
                        <tr>
                            <td><?= $p['emoji'] ?> <strong><?= htmlspecialchars($p['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($p['region']) ?></td>
                            <td><strong><?= number_format($p['prix'], 0, ',', ' ') ?></strong> <small><?= $p['unite'] ?></small></td>
                            <td><span class="badge badge-<?= $p['tendance'] ?>"><?= ucfirst($p['tendance']) ?></span></td>
                            <td>
                                <a href="/admin/prix.php?edit=<?= $p['id'] ?>" class="btn-edit">✏️</a>
                                <a href="/admin/prix.php?delete=<?= $p['id'] ?>" class="btn-del" onclick="return confirm('Supprimer ?')">🗑️</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>