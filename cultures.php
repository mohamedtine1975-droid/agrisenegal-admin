<?php 
include 'includes/db.php';
include 'includes/header.php'; 

$result = $conn->query("SELECT * FROM cultures ORDER BY nom ASC");
$cultures = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- PAGE HEADER -->
<section style="background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); padding: 8rem 2rem 4rem; text-align: center;">
    <div style="max-width: 700px; margin: 0 auto;">
        <span class="section-tag" style="background: rgba(255,255,255,0.1); color: var(--green-light); border-color: rgba(255,255,255,0.15);">
            🌍 Terroir Sénégalais
        </span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 900; color: #fff; margin: 1rem 0;">
            Nos Cultures
        </h1>
        <p style="color: rgba(255,255,255,0.65); font-size: 1.1rem; line-height: 1.8;">
            Découvrez les cultures adaptées à chaque région du Sénégal avec toutes les informations dont vous avez besoin.
        </p>
    </div>
</section>

<!-- CULTURES GRID -->
<section style="max-width: 1200px; margin: 0 auto; padding: 5rem 2rem;">

    <!-- STATS BAR -->
    <div style="display: flex; gap: 2rem; align-items: center; margin-bottom: 3rem; flex-wrap: wrap;">
        <p style="color: var(--text-light); font-size: 0.95rem;">
            <strong style="color: var(--text);"><?= count($cultures) ?> cultures</strong> disponibles
        </p>
        <div style="flex: 1; height: 1px; background: rgba(0,0,0,0.08);"></div>
        <span style="font-size: 0.85rem; color: var(--text-light);">Cliquez sur une culture pour voir les détails</span>
    </div>

    <!-- GRID -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem;">

        <?php foreach($cultures as $culture): ?>
        <?php
            $couleurs = [
                'green'  => ['bg' => '#e8f5ee', 'accent' => '#2d9e5f', 'light' => '#4cce85'],
                'orange' => ['bg' => '#fef3e8', 'accent' => '#e05c1a', 'light' => '#f5923a'],
                'yellow' => ['bg' => '#fefae8', 'accent' => '#c9960a', 'light' => '#e8c030'],
                'red'    => ['bg' => '#fdecea', 'accent' => '#c0392b', 'light' => '#e74c3c'],
                'blue'   => ['bg' => '#e8f0fe', 'accent' => '#1a6fa8', 'light' => '#3498db'],
                'brown'  => ['bg' => '#f5ede8', 'accent' => '#8B4513', 'light' => '#a0522d'],
            ];
            $c = $couleurs[$culture['couleur']] ?? $couleurs['green'];
        ?>

        <!-- CULTURE CARD -->
        <div class="culture-card" onclick="openModal(<?= $culture['id'] ?>)" style="--accent: <?= $c['accent'] ?>; --accent-bg: <?= $c['bg'] ?>;">
            
            <div class="culture-card-header">
                <div class="culture-emoji-wrap" style="background: <?= $c['bg'] ?>; border-color: <?= $c['accent'] ?>22;">
                    <span style="font-size: 2.5rem;"><?= $culture['emoji'] ?></span>
                </div>
                <div>
                    <h3 class="culture-name"><?= htmlspecialchars($culture['nom']) ?></h3>
                    <span class="culture-price" style="color: <?= $c['accent'] ?>;">
                        <?= number_format($culture['prix_moyen'], 0, ',', ' ') ?> <?= $culture['unite_prix'] ?>
                    </span>
                </div>
                <div class="culture-arrow" style="color: <?= $c['accent'] ?>; background: <?= $c['bg'] ?>;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>

            <p class="culture-desc"><?= htmlspecialchars(substr($culture['description'], 0, 100)) ?>...</p>

            <div class="culture-meta">
                <div class="culture-meta-item" style="background: <?= $c['bg'] ?>;">
                    <span style="font-size: 0.8rem; color: <?= $c['accent'] ?>; font-weight: 600;">🗓 Plantation</span>
                    <span style="font-size: 0.8rem; color: var(--text);"><?= htmlspecialchars($culture['mois_plantation']) ?></span>
                </div>
                <div class="culture-meta-item" style="background: <?= $c['bg'] ?>;">
                    <span style="font-size: 0.8rem; color: <?= $c['accent'] ?>; font-weight: 600;">🌾 Récolte</span>
                    <span style="font-size: 0.8rem; color: var(--text);"><?= htmlspecialchars($culture['mois_recolte']) ?></span>
                </div>
            </div>

            <div class="culture-regions">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                <span><?= htmlspecialchars($culture['regions']) ?></span>
            </div>

        </div>
        <?php endforeach; ?>

    </div>
</section>

<!-- MODALS -->
<?php foreach($cultures as $culture): ?>
<?php
    $couleurs = [
        'green'  => ['bg' => '#e8f5ee', 'accent' => '#2d9e5f', 'light' => '#4cce85'],
        'orange' => ['bg' => '#fef3e8', 'accent' => '#e05c1a', 'light' => '#f5923a'],
        'yellow' => ['bg' => '#fefae8', 'accent' => '#c9960a', 'light' => '#e8c030'],
        'red'    => ['bg' => '#fdecea', 'accent' => '#c0392b', 'light' => '#e74c3c'],
        'blue'   => ['bg' => '#e8f0fe', 'accent' => '#1a6fa8', 'light' => '#3498db'],
        'brown'  => ['bg' => '#f5ede8', 'accent' => '#8B4513', 'light' => '#a0522d'],
    ];
    $c = $couleurs[$culture['couleur']] ?? $couleurs['green'];
?>
<div class="modal-overlay" id="modal-<?= $culture['id'] ?>" onclick="closeModal(<?= $culture['id'] ?>)">
    <div class="modal-box" onclick="event.stopPropagation()">
        
        <button class="modal-close" onclick="closeModal(<?= $culture['id'] ?>)">✕</button>

        <div class="modal-header" style="background: linear-gradient(135deg, <?= $c['accent'] ?>, <?= $c['light'] ?>);">
            <span style="font-size: 4rem;"><?= $culture['emoji'] ?></span>
            <div>
                <h2 style="font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: #fff;"><?= htmlspecialchars($culture['nom']) ?></h2>
                <span style="color: rgba(255,255,255,0.8); font-size: 0.9rem;"><?= htmlspecialchars($culture['duree_culture']) ?></span>
            </div>
        </div>

        <div class="modal-body">

            <p style="color: var(--text-light); line-height: 1.8; margin-bottom: 2rem;">
                <?= htmlspecialchars($culture['description']) ?>
            </p>

            <div class="modal-grid">

                <div class="modal-info-card" style="border-left: 4px solid <?= $c['accent'] ?>;">
                    <span class="modal-info-label">🗓 Période de plantation</span>
                    <span class="modal-info-value"><?= htmlspecialchars($culture['mois_plantation']) ?></span>
                </div>

                <div class="modal-info-card" style="border-left: 4px solid <?= $c['accent'] ?>;">
                    <span class="modal-info-label">🌾 Période de récolte</span>
                    <span class="modal-info-value"><?= htmlspecialchars($culture['mois_recolte']) ?></span>
                </div>

                <div class="modal-info-card" style="border-left: 4px solid <?= $c['accent'] ?>;">
                    <span class="modal-info-label">📍 Régions recommandées</span>
                    <span class="modal-info-value"><?= htmlspecialchars($culture['regions']) ?></span>
                </div>

                <div class="modal-info-card" style="border-left: 4px solid <?= $c['accent'] ?>;">
                    <span class="modal-info-label">💰 Prix moyen du marché</span>
                    <span class="modal-info-value" style="color: <?= $c['accent'] ?>; font-size: 1.3rem;">
                        <?= number_format($culture['prix_moyen'], 0, ',', ' ') ?> <?= $culture['unite_prix'] ?>
                    </span>
                </div>

            </div>

            <div class="modal-maladies">
                <h4 style="font-weight: 700; color: var(--text); margin-bottom: 1rem;">⚠️ Maladies courantes</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <?php foreach(explode(',', $culture['maladies']) as $maladie): ?>
                    <span style="background: #fff0f0; color: #c0392b; border: 1px solid #fcc; font-size: 0.8rem; padding: 0.3rem 0.75rem; border-radius: 20px; font-weight: 500;">
                        <?= trim(htmlspecialchars($maladie)) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <a href="/agrisenegal/diagnostic.php" class="btn-primary" style="display: inline-flex; margin-top: 1.5rem;">
                🔬 Diagnostiquer une maladie
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>

        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- STYLES CULTURES -->
<style>
.culture-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.75rem;
    cursor: pointer;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: all 0.35s ease;
    position: relative;
    overflow: hidden;
}
.culture-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent);
    transform: scaleX(0);
    transition: transform 0.35s ease;
}
.culture-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,0.12); }
.culture-card:hover::before { transform: scaleX(1); }

.culture-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}
.culture-emoji-wrap {
    width: 60px; height: 60px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid transparent;
    flex-shrink: 0;
}
.culture-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 2px;
}
.culture-price {
    font-size: 0.85rem;
    font-weight: 700;
}
.culture-arrow {
    margin-left: auto;
    width: 34px; height: 34px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.culture-card:hover .culture-arrow { transform: translateX(4px); }

.culture-desc {
    color: var(--text-light);
    font-size: 0.875rem;
    line-height: 1.7;
    margin-bottom: 1.25rem;
}
.culture-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.6rem;
    margin-bottom: 1rem;
}
.culture-meta-item {
    border-radius: 10px;
    padding: 0.6rem 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.culture-regions {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    color: var(--text-light);
    font-size: 0.78rem;
    line-height: 1.5;
    padding-top: 1rem;
    border-top: 1px solid rgba(0,0,0,0.06);
}

/* MODAL */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}
.modal-overlay.active { display: flex; }

.modal-box {
    background: #fff;
    border-radius: 24px;
    max-width: 650px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: modalIn 0.35s ease;
    box-shadow: 0 30px 80px rgba(0,0,0,0.3);
}
@keyframes modalIn {
    from { opacity: 0; transform: translateY(30px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.modal-close {
    position: absolute;
    top: 1rem; right: 1rem;
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    width: 36px; height: 36px;
    border-radius: 50%;
    font-size: 1rem;
    cursor: pointer;
    z-index: 10;
    transition: background 0.2s;
}
.modal-close:hover { background: rgba(255,255,255,0.35); }

.modal-header {
    padding: 2.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    border-radius: 24px 24px 0 0;
}
.modal-body { padding: 2rem; }

.modal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.modal-info-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem 1.2rem;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.modal-info-label { font-size: 0.75rem; color: var(--text-light); font-weight: 600; }
.modal-info-value { font-size: 0.9rem; color: var(--text); font-weight: 600; }

.modal-maladies {
    background: #fff8f8;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #fde;
}
</style>

<script>
function openModal(id) {
    document.getElementById('modal-' + id).classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById('modal-' + id).classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => {
            m.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>