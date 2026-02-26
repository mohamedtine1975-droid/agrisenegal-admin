<?php
include 'includes/db.php';
include 'includes/header.php';

// Suppression
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Supprimer la photo si elle existe
    $row = $conn->query("SELECT photo_path FROM diagnostics WHERE id = $id")->fetch_assoc();
    if ($row && !empty($row['photo_path']) && file_exists(__DIR__ . '/' . $row['photo_path'])) {
        unlink(__DIR__ . '/' . $row['photo_path']);
    }
    $conn->query("DELETE FROM diagnostics WHERE id = $id");
    header('Location: /historique.php?deleted=1');
    exit;
}

$message = isset($_GET['deleted']) ? '✅ Diagnostic supprimé !' : '';

// Filtres
$filtre_culture = trim($_GET['culture'] ?? '');
$filtre_date    = trim($_GET['date'] ?? '');

$where = [];
if ($filtre_culture) $where[] = "culture = '" . $conn->real_escape_string($filtre_culture) . "'";
if ($filtre_date)    $where[] = "DATE(created_at) = '" . $conn->real_escape_string($filtre_date) . "'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$diagnostics = $conn->query("SELECT * FROM diagnostics $where_sql ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
$cultures_list = $conn->query("SELECT DISTINCT culture FROM diagnostics WHERE culture != '' ORDER BY culture ASC")->fetch_all(MYSQLI_ASSOC);
$total = count($diagnostics);
?>

<!-- PAGE HEADER -->
<section style="background: linear-gradient(135deg, #0d3320, #1a5c38); padding: 8rem 2rem 4rem; text-align: center;">
    <div style="max-width: 700px; margin: 0 auto;">
        <span class="section-tag" style="background: rgba(255,255,255,0.1); color: var(--green-light); border-color: rgba(255,255,255,0.15);">
            📋 Suivi IA
        </span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 900; color: #fff; margin: 1rem 0;">
            Historique des Diagnostics
        </h1>
        <p style="color: rgba(255,255,255,0.65); font-size: 1.1rem; line-height: 1.8;">
            Retrouvez tous vos diagnostics passés et suivez l'évolution de vos cultures.
        </p>
    </div>
</section>

<section style="max-width: 1100px; margin: 0 auto; padding: 4rem 2rem;">

    <?php if ($message): ?>
    <div style="background: #e8f5ee; color: #2d9e5f; border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem; border: 1px solid rgba(45,158,95,0.2);">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- FILTRES -->
    <div class="filtres-bar">
        <form method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <select name="culture" class="filtre-input" onchange="this.form.submit()">
                <option value="">🌱 Toutes les cultures</option>
                <?php foreach($cultures_list as $cl): ?>
                <option value="<?= htmlspecialchars($cl['culture']) ?>" <?= $filtre_culture === $cl['culture'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cl['culture']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="date" class="filtre-input" value="<?= htmlspecialchars($filtre_date) ?>" onchange="this.form.submit()">
            <?php if ($filtre_culture || $filtre_date): ?>
            <a href="/historique.php" style="color: #e05c1a; font-size: 0.85rem; font-weight: 600; text-decoration: none;">✕ Effacer les filtres</a>
            <?php endif; ?>
            <span style="margin-left: auto; font-size: 0.85rem; color: var(--text-light);">
                <strong style="color: var(--text);"><?= $total ?></strong> diagnostic<?= $total > 1 ? 's' : '' ?> trouvé<?= $total > 1 ? 's' : '' ?>
            </span>
        </form>
    </div>

    <?php if (empty($diagnostics)): ?>
    <!-- VIDE -->
    <div style="text-align: center; padding: 5rem 2rem; background: #fff; border-radius: 20px; border: 2px dashed rgba(0,0,0,0.08);">
        <span style="font-size: 4rem; display: block; margin-bottom: 1rem; opacity: 0.3;">🔬</span>
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--text-light); margin-bottom: 0.5rem;">Aucun diagnostic trouvé</h3>
        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1.5rem;">Lancez votre premier diagnostic pour voir l'historique ici.</p>
        <a href="/diagnostic.php" class="btn-primary">🔬 Lancer un diagnostic</a>
    </div>

    <?php else: ?>

    <!-- LISTE DIAGNOSTICS -->
    <div class="diag-grid">
        <?php foreach($diagnostics as $d): ?>
        <div class="diag-item">

            <div class="diag-item-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #2d9e5f, #4cce85); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">🤖</div>
                    <div>
                        <strong style="color: var(--text); display: block; font-size: 0.95rem;">
                            <?= htmlspecialchars($d['culture'] ?: 'Culture non spécifiée') ?>
                        </strong>
                        <span style="font-size: 0.75rem; color: var(--text-light);">
                            🕐 <?= date('d/m/Y à H:i', strtotime($d['created_at'])) ?>
                        </span>
                    </div>
                </div>
                <button class="btn-toggle" onclick="toggleDiag(<?= $d['id'] ?>)">
                    <span id="icon-<?= $d['id'] ?>">▼</span>
                </button>
            </div>

            <!-- PHOTO + DESCRIPTION -->
            <div style="display: grid; grid-template-columns: <?= $d['photo_path'] ? '120px 1fr' : '1fr' ?>; gap: 1rem; margin: 1rem 0;">
                <?php if ($d['photo_path'] && file_exists(__DIR__ . '/' . $d['photo_path'])): ?>
                <img src="/<?= htmlspecialchars($d['photo_path']) ?>" alt="Photo" style="width: 120px; height: 90px; object-fit: cover; border-radius: 10px; border: 1px solid rgba(0,0,0,0.08);">
                <?php endif; ?>
                <div>
                    <?php if ($d['description']): ?>
                    <p style="font-size: 0.85rem; color: var(--text-light); line-height: 1.6; font-style: italic;">
                        "<?= htmlspecialchars(substr($d['description'], 0, 150)) ?><?= strlen($d['description']) > 150 ? '...' : '' ?>"
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RÉSULTAT (expandable) -->
            <div id="diag-<?= $d['id'] ?>" class="diag-resultat" style="display: none;">
                <div style="background: #f8fdf9; border-radius: 12px; padding: 1.25rem; border: 1px solid rgba(45,158,95,0.1); font-size: 0.88rem; line-height: 1.8; color: var(--text); white-space: pre-wrap;">
                    <?= htmlspecialchars($d['resultat']) ?>
                </div>
            </div>

            <!-- ACTIONS -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.06);">
                <button onclick="toggleDiag(<?= $d['id'] ?>)" style="background: #e8f5ee; color: #2d9e5f; border: none; padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif;">
                    📋 Voir le diagnostic complet
                </button>
                <a href="/historique.php?delete=<?= $d['id'] ?>" onclick="return confirm('Supprimer ce diagnostic ?')" style="color: #e05c1a; font-size: 0.8rem; font-weight: 600; text-decoration: none; background: #fef3e8; padding: 0.4rem 0.75rem; border-radius: 8px;">
                    🗑️ Supprimer
                </a>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <!-- BOUTON NOUVEAU DIAGNOSTIC -->
    <div style="text-align: center; margin-top: 3rem;">
        <a href="/diagnostic.php" class="btn-primary" style="font-size: 1rem; padding: 1rem 2.5rem;">
            🔬 Nouveau diagnostic
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

</section>

<style>
.filtres-bar {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.05);
}
.filtre-input {
    padding: 0.6rem 1rem;
    border: 1.5px solid rgba(0,0,0,0.1);
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem;
    color: var(--text);
    background: #fafafa;
    outline: none;
    transition: all 0.2s;
    cursor: pointer;
}
.filtre-input:focus { border-color: #2d9e5f; background: #fff; }

.diag-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(480px, 1fr));
    gap: 1.5rem;
}
.diag-item {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s;
}
.diag-item:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.1); transform: translateY(-2px); }
.diag-item-header { display: flex; justify-content: space-between; align-items: flex-start; }
.btn-toggle {
    background: #f3f4f6;
    border: none;
    width: 32px; height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 0.75rem;
    color: var(--text-light);
    flex-shrink: 0;
    transition: all 0.2s;
}
.btn-toggle:hover { background: #e8f5ee; color: #2d9e5f; }
.diag-resultat { margin-top: 1rem; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

@media (max-width: 768px) {
    .diag-grid { grid-template-columns: 1fr; }
    .filtres-bar form { flex-direction: column; align-items: stretch; }
    .filtre-input { width: 100%; }
}
</style>

<script>
function toggleDiag(id) {
    const el = document.getElementById('diag-' + id);
    const icon = document.getElementById('icon-' + id);
    if (el.style.display === 'none') {
        el.style.display = 'block';
        icon.textContent = '▲';
    } else {
        el.style.display = 'none';
        icon.textContent = '▼';
    }
}
</script>

<?php include 'includes/footer.php'; ?>