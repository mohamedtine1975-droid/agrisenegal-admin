<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'includes/db.php';
include 'includes/auth_user.php';
require_login();

$user = get_user();
$welcome = isset($_GET['welcome']);

// Récupérer les diagnostics de l'utilisateur
$diags = $conn->query("SELECT * FROM diagnostics WHERE user_id = {$user['id']} ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
$total_diags = count($diags);

// Récupérer infos complètes
$info = $conn->query("SELECT * FROM utilisateurs WHERE id = {$user['id']}")->fetch_assoc();

include 'includes/header.php';
?>

<!-- PAGE HEADER -->
<section style="background: linear-gradient(135deg, #0d3320, #1a5c38); padding: 8rem 2rem 4rem;">
    <div style="max-width: 1100px; margin: 0 auto; display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2d9e5f, #4cce85); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; box-shadow: 0 8px 25px rgba(45,158,95,0.4); flex-shrink: 0;">👨‍🌾</div>
        <div>
            <?php if ($welcome): ?>
            <span style="background: rgba(255,255,255,0.15); color: #4cce85; font-size: 0.8rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 20px; letter-spacing: 1px; text-transform: uppercase;">🎉 Bienvenue !</span>
            <?php endif; ?>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: #fff; margin: 0.5rem 0;">
                <?= htmlspecialchars($user['nom']) ?>
            </h1>
            <p style="color: rgba(255,255,255,0.65); font-size: 0.95rem;">
                📍 <?= htmlspecialchars($info['region'] ?: 'Région non renseignée') ?> &nbsp;•&nbsp;
                📧 <?= htmlspecialchars($user['email']) ?> &nbsp;•&nbsp;
                🌱 Membre depuis <?= date('M Y', strtotime($info['created_at'])) ?>
            </p>
        </div>
        <div style="margin-left: auto; display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="/diagnostic.php" class="btn-primary">🔬 Nouveau diagnostic</a>
            <a href="/deconnexion.php" style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.9rem; font-weight: 600; padding: 0.75rem 1.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); transition: all 0.2s;">
                🚪 Déconnexion
            </a>
        </div>
    </div>
</section>

<!-- STATS -->
<section style="max-width: 1100px; margin: 0 auto; padding: 3rem 2rem 0;">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 3rem;">
        <div style="background: #fff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,0.05); text-align: center; border: 1px solid rgba(0,0,0,0.04);">
            <span style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; color: #2d9e5f; display: block;"><?= $total_diags ?></span>
            <span style="color: #5a7060; font-size: 0.85rem;">Diagnostics effectués</span>
        </div>
        <div style="background: #fff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,0.05); text-align: center; border: 1px solid rgba(0,0,0,0.04);">
            <span style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; color: #e8a830; display: block;">
                <?= count(array_unique(array_column($diags, 'culture'))) ?>
            </span>
            <span style="color: #5a7060; font-size: 0.85rem;">Cultures analysées</span>
        </div>
        <div style="background: #fff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,0.05); text-align: center; border: 1px solid rgba(0,0,0,0.04);">
            <span style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; color: #1a6fa8; display: block;">
                <?= $total_diags > 0 ? date('d/m', strtotime($diags[0]['created_at'])) : '—' ?>
            </span>
            <span style="color: #5a7060; font-size: 0.85rem;">Dernier diagnostic</span>
        </div>
    </div>
</section>

<!-- DIAGNOSTICS -->
<section style="max-width: 1100px; margin: 0 auto; padding: 0 2rem 4rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: #1a2620;">Mes diagnostics</h2>
        <a href="/diagnostic.php" class="btn-primary" style="font-size: 0.85rem; padding: 0.6rem 1.2rem;">+ Nouveau</a>
    </div>

    <?php if (empty($diags)): ?>
    <div style="text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 20px; border: 2px dashed rgba(0,0,0,0.08);">
        <span style="font-size: 3.5rem; display: block; margin-bottom: 1rem; opacity: 0.3;">🔬</span>
        <h3 style="font-family: 'Playfair Display', serif; color: #5a7060; margin-bottom: 0.5rem;">Aucun diagnostic encore</h3>
        <p style="color: #5a7060; font-size: 0.9rem; margin-bottom: 1.5rem;">Lancez votre premier diagnostic pour analyser vos plantes.</p>
        <a href="/diagnostic.php" class="btn-primary">🔬 Lancer un diagnostic</a>
    </div>

    <?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(460px, 1fr)); gap: 1.5rem;">
        <?php foreach($diags as $d): ?>
        <div style="background: #fff; border-radius: 20px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #2d9e5f, #4cce85); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">🤖</div>
                <div style="flex: 1;">
                    <strong style="color: #1a2620; display: block; font-size: 0.9rem;"><?= htmlspecialchars($d['culture'] ?: 'Culture non spécifiée') ?></strong>
                    <span style="font-size: 0.75rem; color: #5a7060;"><?= date('d/m/Y à H:i', strtotime($d['created_at'])) ?></span>
                </div>
                <a href="/historique.php?delete=<?= $d['id'] ?>" onclick="return confirm('Supprimer ?')" style="color: #e05c1a; font-size: 0.75rem; background: #fef3e8; padding: 0.3rem 0.6rem; border-radius: 6px; text-decoration: none;">🗑️</a>
            </div>
            <?php if ($d['description']): ?>
            <p style="font-size: 0.82rem; color: #5a7060; line-height: 1.6; font-style: italic; margin-bottom: 0.75rem;">
                "<?= htmlspecialchars(substr($d['description'], 0, 100)) ?>..."
            </p>
            <?php endif; ?>
            <button onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'block' : 'none'; this.textContent = this.textContent.includes('Voir') ? '▲ Masquer' : '📋 Voir le diagnostic';" style="background: #e8f5ee; color: #2d9e5f; border: none; padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; width: 100%;">
                📋 Voir le diagnostic
            </button>
            <div style="display: none; margin-top: 1rem; background: #f8fdf9; border-radius: 12px; padding: 1rem; font-size: 0.85rem; line-height: 1.8; color: #1a2620; white-space: pre-wrap; border: 1px solid rgba(45,158,95,0.1);">
                <?= htmlspecialchars($d['resultat']) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>