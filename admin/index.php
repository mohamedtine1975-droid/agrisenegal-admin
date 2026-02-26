<?php
include '../includes/db.php';
include 'auth.php';
check_auth();

// Stats
$total_cultures = $conn->query("SELECT COUNT(*) FROM cultures")->fetch_row()[0];
$total_prix = $conn->query("SELECT COUNT(*) FROM prix_marche")->fetch_row()[0];
$hausses = $conn->query("SELECT COUNT(*) FROM prix_marche WHERE tendance='hausse'")->fetch_row()[0];
$baisses = $conn->query("SELECT COUNT(*) FROM prix_marche WHERE tendance='baisse'")->fetch_row()[0];

// Derniers prix ajoutés
$derniers = $conn->query("SELECT p.*, c.nom, c.emoji FROM prix_marche p JOIN cultures c ON p.culture_id = c.id ORDER BY p.date_maj DESC LIMIT 8")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — AgriSénégal Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR (Desktop) */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--green-dark);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--green), var(--green-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .sidebar-logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
        }

        .sidebar-logo-text span {
            color: var(--green-light);
        }

        .sidebar-nav {
            padding: 1.5rem 0;
            flex: 1;
        }

        .sidebar-section {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.3);
            padding: 0 1.5rem;
            margin: 1rem 0 0.5rem;
            display: block;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.7rem 1.5rem;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
            border-left-color: var(--green-light);
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .admin-avatar {
            width: 32px;
            height: 32px;
            background: var(--green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-name {
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-logout {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* MAIN CONTENT */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 2rem;
            width: 100%;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 900;
        }

        .page-sub {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: 4px;
        }

        /* MOBILE NAV */
        .mobile-nav {
            display: none;
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 900;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 500;
        }

        /* TABLE */
        .table-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-weight: 700;
            color: var(--text);
        }

        .btn-add {
            background: var(--green);
            color: #fff;
            text-decoration: none;
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-light);
            padding: 1rem 1.5rem;
            background: #fafafa;
        }

        td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        .badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-hausse {
            background: #e8f5ee;
            color: #2d9e5f;
        }

        .badge-baisse {
            background: #fef3e8;
            color: #e05c1a;
        }

        .badge-stable {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-edit {
            color: var(--green);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.3rem 0.7rem;
            background: #e8f5ee;
            border-radius: 8px;
        }

        /* ===== RESPONSIVE UNIFIÉ ===== */
        @media (max-width: 768px) {
            .sidebar {
                display: none !important;
            }

            .main {
                margin-left: 0 !important;
                padding: 1rem !important;
                width: 100% !important;
            }

            .mobile-nav {
                display: flex !important;
                position: sticky;
                top: 0;
                z-index: 1000;
                background: var(--green-dark);
                margin: -1rem -1rem 1.5rem -1rem !important;
                padding: 0.8rem 0.5rem !important;
                justify-content: space-around;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border-bottom: 2px solid var(--green);
            }

            .mobile-nav a {
                color: #fff;
                text-decoration: none;
                font-size: 0.75rem;
                font-weight: 600;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                flex: 1;
                opacity: 0.8;
            }

            .mobile-nav a.active {
                opacity: 1;
                color: var(--green-light);
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr !important;
                gap: 1rem !important;
            }

            .page-title {
                font-size: 1.4rem !important;
            }

            /* Cacher les colonnes moins vitales sur mobile pour éviter le scroll horizontal */
            th:nth-child(2),
            td:nth-child(2),
            /* Région */
            th:nth-child(5),
            td:nth-child(5)

            /* Date */
                {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
            }
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
            <a href="/admin/index.php" class="sidebar-link active">📊 Dashboard</a>
            <a href="/admin/prix.php" class="sidebar-link">📈 Prix du marché</a>
            <a href="/admin/cultures.php" class="sidebar-link">
                <span class="icon">🌱</span> Cultures
            </a>
            <span class="sidebar-section">Site</span>
            <a href="/" class="sidebar-link" target="_blank">🌍 Voir le site</a>
        </nav>
        <div class="sidebar-footer">
            <div class="admin-info">
                <div class="admin-avatar">👤</div>
                <div class="admin-name"><?= htmlspecialchars($_SESSION['admin_username']) ?></div>
            </div>
            <a href="/admin/logout.php" class="btn-logout">🚪 Déconnexion</a>
        </div>
    </aside>

    <main class="main">
        <nav class="mobile-nav">
            <a href="/admin/index.php" class="active"><span>📊</span><span>Dash</span></a>
            <a href="/admin/prix.php"><span>📈</span><span>Prix</span></a>
            <a href="/" target="_blank"><span>🌍</span><span>Site</span></a>
            <a href="/admin/logout.php"><span>🚪</span><span>Quitter</span></a>
        </nav>

        <div class="page-header">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-sub">Content de vous revoir, <?= htmlspecialchars($_SESSION['admin_username']) ?></p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5ee;">🌾</div>
                <div>
                    <div class="stat-number"><?= $total_cultures ?></div>
                    <div class="stat-label">Cultures</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f0fe;">📈</div>
                <div>
                    <div class="stat-number"><?= $total_prix ?></div>
                    <div class="stat-label">Prix suivis</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5ee;">↑</div>
                <div>
                    <div class="stat-number" style="color: #2d9e5f;"><?= $hausses ?></div>
                    <div class="stat-label">Hausses</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3e8;">↓</div>
                <div>
                    <div class="stat-number" style="color: #e05c1a;"><?= $baisses ?></div>
                    <div class="stat-label">Baisses</div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <span class="table-title">Activités récentes</span>
                <a href="/admin/prix.php" class="btn-add">+ Gérer tout</a>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Culture</th>
                            <th>Région</th>
                            <th>Prix</th>
                            <th>Tendance</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($derniers as $p):
                            $icon = ($p['tendance'] === 'hausse') ? '↑' : (($p['tendance'] === 'baisse') ? '↓' : '→');
                        ?>
                            <tr>
                                <td><?= $p['emoji'] ?> <strong><?= htmlspecialchars($p['nom']) ?></strong></td>
                                <td><?= htmlspecialchars($p['region']) ?></td>
                                <td><strong><?= number_format($p['prix'], 0, ',', ' ') ?></strong> <small><?= htmlspecialchars($p['unite']) ?></small></td>
                                <td>
                                    <span class="badge badge-<?= $p['tendance'] ?>">
                                        <?= $icon ?> <?= ucfirst($p['tendance']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/y', strtotime($p['date_maj'])) ?></td>
                                <td><a href="/admin/prix.php?edit=<?= $p['id'] ?>" class="btn-edit">Modifier</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>

</html>