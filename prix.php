<?php 
include 'includes/db.php';
include 'includes/header.php';

// Récupérer tous les prix avec le nom de la culture
$sql = "SELECT p.*, c.nom, c.emoji, c.couleur 
        FROM prix_marche p 
        JOIN cultures c ON p.culture_id = c.id 
        ORDER BY c.nom ASC, p.region ASC";
$result = $conn->query($sql);
$prix = $result->fetch_all(MYSQLI_ASSOC);

// Grouper par culture
$par_culture = [];
foreach($prix as $p) {
    $par_culture[$p['nom']][] = $p;
}

// Stats globales
$total = count($prix);
$hausses = count(array_filter($prix, fn($p) => $p['tendance'] === 'hausse'));
$baisses = count(array_filter($prix, fn($p) => $p['tendance'] === 'baisse'));
?>

<!-- PAGE HEADER -->
<section style="background: linear-gradient(135deg, #0d3320, #1a5c38); padding: 8rem 2rem 4rem; text-align: center;">
    <div style="max-width: 700px; margin: 0 auto;">
        <span class="section-tag" style="background: rgba(255,255,255,0.1); color: var(--green-light); border-color: rgba(255,255,255,0.15);">
            📈 Marchés du Sénégal
        </span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 900; color: #fff; margin: 1rem 0;">
            Prix du Marché
        </h1>
        <p style="color: rgba(255,255,255,0.65); font-size: 1.1rem; line-height: 1.8;">
            Suivez les prix de vos récoltes en temps réel dans toutes les régions du Sénégal.
        </p>
    </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stats-bar-inner">
        <div class="stat-box">
            <span class="stat-box-number"><?= $total ?></span>
            <span class="stat-box-label">Prix suivis</span>
        </div>
        <div class="stat-box-divider"></div>
        <div class="stat-box">
            <span class="stat-box-number" style="color: #2d9e5f;"><?= $hausses ?></span>
            <span class="stat-box-label">En hausse ↑</span>
        </div>
        <div class="stat-box-divider"></div>
        <div class="stat-box">
            <span class="stat-box-number" style="color: #e05c1a;"><?= $baisses ?></span>
            <span class="stat-box-label">En baisse ↓</span>
        </div>
        <div class="stat-box-divider"></div>
        <div class="stat-box">
            <span class="stat-box-number"><?= count($par_culture) ?></span>
            <span class="stat-box-label">Cultures</span>
        </div>
        <div style="margin-left: auto; font-size: 0.8rem; color: var(--text-light);">
            🕐 Mis à jour aujourd'hui
        </div>
    </div>
</div>

<!-- PRIX SECTION -->
<section style="max-width: 1200px; margin: 0 auto; padding: 4rem 2rem;">

    <?php foreach($par_culture as $nom_culture => $lignes): ?>
    <?php
        $couleurs = [
            'green'  => ['accent' => '#2d9e5f', 'bg' => '#e8f5ee', 'light' => '#4cce85'],
            'orange' => ['accent' => '#e05c1a', 'bg' => '#fef3e8', 'light' => '#f5923a'],
            'yellow' => ['accent' => '#c9960a', 'bg' => '#fefae8', 'light' => '#e8c030'],
            'red'    => ['accent' => '#c0392b', 'bg' => '#fdecea', 'light' => '#e74c3c'],
            'blue'   => ['accent' => '#1a6fa8', 'bg' => '#e8f0fe', 'light' => '#3498db'],
            'brown'  => ['accent' => '#8B4513', 'bg' => '#f5ede8', 'light' => '#a0522d'],
        ];
        $c = $couleurs[$lignes[0]['couleur']] ?? $couleurs['green'];
        $prix_vals = array_column($lignes, 'prix');
        $prix_min = min($prix_vals);
        $prix_max = max($prix_vals);
        $prix_moy = round(array_sum($prix_vals) / count($prix_vals));
    ?>

    <div class="culture-prix-block">

        <!-- CULTURE HEADER -->
        <div class="culture-prix-header" style="border-left: 5px solid <?= $c['accent'] ?>;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: <?= $c['bg'] ?>; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                    <?= $lignes[0]['emoji'] ?>
                </div>
                <div>
                    <h2 style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--text);">
                        <?= htmlspecialchars($nom_culture) ?>
                    </h2>
                    <span style="font-size: 0.8rem; color: var(--text-light);"><?= count($lignes) ?> régions suivies</span>
                </div>
            </div>
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <div style="text-align: center;">
                    <span style="display: block; font-size: 0.7rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px;">Min</span>
                    <span style="font-weight: 700; color: #2d9e5f;"><?= number_format($prix_min, 0, ',', ' ') ?></span>
                </div>
                <div style="text-align: center;">
                    <span style="display: block; font-size: 0.7rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px;">Moy</span>
                    <span style="font-weight: 700; color: <?= $c['accent'] ?>;"><?= number_format($prix_moy, 0, ',', ' ') ?></span>
                </div>
                <div style="text-align: center;">
                    <span style="display: block; font-size: 0.7rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px;">Max</span>
                    <span style="font-weight: 700; color: #e05c1a;"><?= number_format($prix_max, 0, ',', ' ') ?></span>
                </div>
                <span style="font-size: 0.75rem; color: var(--text-light);">FCFA/kg</span>
            </div>
        </div>

        <!-- REGIONS GRID -->
        <div class="regions-grid">
            <?php foreach($lignes as $ligne): ?>
            <?php
                $tendance_config = [
                    'hausse' => ['icon' => '↑', 'color' => '#2d9e5f', 'bg' => '#e8f5ee', 'label' => 'Hausse'],
                    'baisse' => ['icon' => '↓', 'color' => '#e05c1a', 'bg' => '#fef3e8', 'label' => 'Baisse'],
                    'stable' => ['icon' => '→', 'color' => '#6b7280', 'bg' => '#f3f4f6', 'label' => 'Stable'],
                ];
                $t = $tendance_config[$ligne['tendance']];
                $pct = $prix_max > $prix_min ? round((($ligne['prix'] - $prix_min) / ($prix_max - $prix_min)) * 100) : 50;
            ?>
            <div class="region-card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                            <svg width="12" height="12" fill="none" stroke="<?= $c['accent'] ?>" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                            <span style="font-weight: 700; color: var(--text); font-size: 0.95rem;"><?= htmlspecialchars($ligne['region']) ?></span>
                        </div>
                        <span style="font-size: 0.75rem; color: var(--text-light);">Mis à jour : <?= date('d/m/Y', strtotime($ligne['date_maj'])) ?></span>
                    </div>
                    <span style="background: <?= $t['bg'] ?>; color: <?= $t['color'] ?>; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 20px;">
                        <?= $t['icon'] ?> <?= $t['label'] ?>
                    </span>
                </div>

                <!-- PRIX -->
                <div style="margin-bottom: 0.75rem;">
                    <span style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; color: <?= $c['accent'] ?>;">
                        <?= number_format($ligne['prix'], 0, ',', ' ') ?>
                    </span>
                    <span style="font-size: 0.8rem; color: var(--text-light); margin-left: 4px;"><?= $ligne['unite'] ?></span>
                </div>

                <!-- BARRE DE PROGRESSION -->
                <div style="background: #f0f0f0; border-radius: 99px; height: 5px; overflow: hidden;">
                    <div style="width: <?= $pct ?>%; height: 100%; background: linear-gradient(90deg, <?= $c['accent'] ?>, <?= $c['light'] ?>); border-radius: 99px; transition: width 1s ease;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                    <span style="font-size: 0.7rem; color: var(--text-light);">Min: <?= number_format($prix_min, 0) ?></span>
                    <span style="font-size: 0.7rem; color: var(--text-light);">Max: <?= number_format($prix_max, 0) ?></span>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
    <?php endforeach; ?>

</section>

<!-- CTA DIAGNOSTIC -->
<section style="background: linear-gradient(135deg, #0d3320, #1a5c38); padding: 4rem 2rem; text-align: center;">
    <div style="max-width: 600px; margin: 0 auto;">
        <p style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; color: #fff; margin-bottom: 1rem;">
            Votre culture a un problème ?
        </p>
        <p style="color: rgba(255,255,255,0.65); margin-bottom: 2rem; line-height: 1.8;">
            Utilisez notre diagnostic IA pour identifier les maladies et trouver les bons traitements.
        </p>
        <a href="/agrisenegal/diagnostic.php" class="btn-primary">
            🔬 Lancer le diagnostic IA
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<!-- STYLES -->
<style>
.stats-bar {
    background: #fff;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    position: sticky;
    top: 70px;
    z-index: 100;
}
.stats-bar-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    flex-wrap: wrap;
}
.stat-box { text-align: center; }
.stat-box-number {
    display: block;
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--text);
    line-height: 1;
}
.stat-box-label {
    display: block;
    font-size: 0.7rem;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 3px;
}
.stat-box-divider {
    width: 1px;
    height: 35px;
    background: rgba(0,0,0,0.08);
}

.culture-prix-block {
    margin-bottom: 3rem;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05);
}
.culture-prix-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: #fafafa;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    flex-wrap: wrap;
    gap: 1rem;
}
.regions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 0;
}
.region-card {
    padding: 1.5rem;
    border-right: 1px solid rgba(0,0,0,0.05);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: background 0.2s ease;
}
.region-card:hover { background: #fafafa; }

@media (max-width: 768px) {
    .culture-prix-header { flex-direction: column; align-items: flex-start; }
    .stats-bar-inner { gap: 1rem; }
}
</style>

<?php include 'includes/footer.php'; ?>