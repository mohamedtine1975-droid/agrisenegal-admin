<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /diagnostic.php');
    exit;
}
include 'includes/header.php';
?>

<!-- HERO -->
<section style="background: linear-gradient(135deg, #0d3320, #1a5c38); padding: 8rem 2rem 5rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; inset: 0; background-image: radial-gradient(circle at 20% 50%, rgba(45,158,95,0.15) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(232,168,48,0.08) 0%, transparent 40%);"></div>
    <div style="max-width: 750px; margin: 0 auto; position: relative; z-index: 1;">
        <span class="section-tag" style="background: rgba(255,255,255,0.1); color: var(--green-light); border-color: rgba(255,255,255,0.15);">
            🔬 Diagnostic IA
        </span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 900; color: #fff; margin: 1rem 0; line-height: 1.1;">
            Rejoignez AgriSénégal<br>
            <span style="background: linear-gradient(135deg, #e8a830, #f5c842, #4cce85); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">c'est gratuit !</span>
        </h1>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2.5rem;">
            Créez votre compte gratuit pour accéder au diagnostic IA, suivre vos cultures et consulter votre historique personnel.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="/inscription.php" class="btn-primary" style="font-size: 1.05rem; padding: 1rem 2.5rem;">
                🌱 Créer mon compte gratuit
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="/connexion.php" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; color: rgba(255,255,255,0.85); text-decoration: none; font-weight: 500; font-size: 1rem; padding: 1rem 1.8rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.25); transition: all 0.3s;">
                Déjà un compte ? Se connecter
            </a>
        </div>
    </div>
</section>

<!-- AVANTAGES -->
<section style="max-width: 1100px; margin: 0 auto; padding: 5rem 2rem;">

    <div style="text-align: center; margin-bottom: 3.5rem;">
        <span class="section-tag">Pourquoi s'inscrire ?</span>
        <h2 class="section-title" style="margin-top: 0.75rem;">Tout ce que vous débloquez</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 4rem;">

        <!-- Avantage 1 -->
        <div style="background: #fff; border-radius: 20px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #2d9e5f, #4cce85); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem; box-shadow: 0 8px 20px rgba(45,158,95,0.3);">🔬</div>
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--text); margin-bottom: 0.75rem;">Diagnostic IA illimité</h3>
            <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.7;">Analysez vos plantes malades avec notre IA — texte ou photo. Obtenez un diagnostic complet et des traitements adaptés au Sénégal.</p>
            <div style="margin-top: 1rem; background: #e8f5ee; color: #2d9e5f; font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 20px; display: inline-block;">✓ Gratuit</div>
        </div>

        <!-- Avantage 2 -->
        <div style="background: #fff; border-radius: 20px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #e8a830, #f5c842); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem; box-shadow: 0 8px 20px rgba(232,168,48,0.3);">📋</div>
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--text); margin-bottom: 0.75rem;">Historique personnel</h3>
            <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.7;">Retrouvez tous vos diagnostics passés. Suivez l'évolution de la santé de vos cultures saison après saison.</p>
            <div style="margin-top: 1rem; background: #fefae8; color: #c9960a; font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 20px; display: inline-block;">✓ Gratuit</div>
        </div>

        <!-- Avantage 3 -->
        <div style="background: #fff; border-radius: 20px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #1a6fa8, #3498db); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem; box-shadow: 0 8px 20px rgba(26,111,168,0.3);">👤</div>
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--text); margin-bottom: 0.75rem;">Profil agriculteur</h3>
            <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.7;">Votre espace personnel avec vos statistiques, vos cultures préférées et vos informations par région.</p>
            <div style="margin-top: 1rem; background: #e8f0fe; color: #1a6fa8; font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 20px; display: inline-block;">✓ Gratuit</div>
        </div>

    </div>

    <!-- COMPARAISON LIBRE VS CONNECTÉ -->
    <div style="background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 4rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr;">

            <!-- VISITEUR -->
            <div style="padding: 2rem;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem;">
                    <span style="font-size: 1.5rem;">👁️</span>
                    <div>
                        <strong style="color: var(--text); display: block;">Visiteur</strong>
                        <span style="font-size: 0.78rem; color: var(--text-light);">Sans compte</span>
                    </div>
                </div>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: var(--text-light);"><span style="color: #2d9e5f;">✅</span> Voir les cultures</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: var(--text-light);"><span style="color: #2d9e5f;">✅</span> Consulter les prix du marché</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: #ccc;"><span>❌</span> Diagnostic IA</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: #ccc;"><span>❌</span> Historique des diagnostics</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: #ccc;"><span>❌</span> Profil personnel</li>
                </ul>
            </div>

            <!-- MEMBRE -->
            <div style="padding: 2rem; background: linear-gradient(135deg, #0d3320, #1a5c38); border-radius: 0 24px 24px 0;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem;">
                    <span style="font-size: 1.5rem;">👨‍🌾</span>
                    <div>
                        <strong style="color: #fff; display: block;">Membre AgriSénégal</strong>
                        <span style="font-size: 0.78rem; color: rgba(255,255,255,0.6);">Compte gratuit</span>
                    </div>
                    <span style="margin-left: auto; background: var(--green-light); color: #0d3320; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 20px;">GRATUIT</span>
                </div>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: rgba(255,255,255,0.85);"><span style="color: #4cce85;">✅</span> Voir les cultures</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: rgba(255,255,255,0.85);"><span style="color: #4cce85;">✅</span> Consulter les prix du marché</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: rgba(255,255,255,0.85);"><span style="color: #4cce85;">✅</span> Diagnostic IA illimité</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: rgba(255,255,255,0.85);"><span style="color: #4cce85;">✅</span> Historique des diagnostics</li>
                    <li style="display: flex; gap: 10px; font-size: 0.9rem; color: rgba(255,255,255,0.85);"><span style="color: #4cce85;">✅</span> Profil personnel</li>
                </ul>
                <a href="/inscription.php" class="btn-primary" style="display: flex; justify-content: center; margin-top: 1.5rem;">
                    Créer mon compte →
                </a>
            </div>

        </div>
    </div>

    <!-- TÉMOIGNAGE FICTIF -->
    <div style="background: linear-gradient(135deg, #f0faf5, #e8f5ee); border-radius: 20px; padding: 2.5rem; text-align: center; border: 1px solid rgba(45,158,95,0.15);">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🌾</div>
        <p style="font-family: 'Playfair Display', serif; font-size: 1.2rem; font-style: italic; color: var(--text); line-height: 1.7; max-width: 600px; margin: 0 auto 1.5rem;">
            "Grâce au diagnostic IA d'AgriSénégal, j'ai pu identifier la rosette de l'arachide rapidement et sauver ma récolte cette saison."
        </p>
        <strong style="color: #2d9e5f; font-size: 0.9rem;">Mamadou D. — Agriculteur à Kaolack</strong>
    </div>

</section>

<!-- CTA FINAL -->
<section style="background: linear-gradient(135deg, #0d3320, #1a5c38); padding: 5rem 2rem; text-align: center;">
    <div style="max-width: 600px; margin: 0 auto;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 1rem;">
            Prêt à commencer ?
        </h2>
        <p style="color: rgba(255,255,255,0.65); font-size: 1rem; line-height: 1.8; margin-bottom: 2.5rem;">
            Inscription gratuite en moins d'une minute. Pas de carte bancaire requise.
        </p>
        <a href="/inscription.php" class="btn-primary" style="font-size: 1.1rem; padding: 1rem 3rem;">
            🌱 Rejoindre AgriSénégal gratuitement
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <p style="color: rgba(255,255,255,0.4); font-size: 0.8rem; margin-top: 1rem;">
            Déjà un compte ? <a href="/connexion.php" style="color: var(--green-light); text-decoration: none;">Se connecter →</a>
        </p>
    </div>
</section>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: repeat(3, 1fr)"] {
        grid-template-columns: 1fr !important;
    }
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
    div[style*="border-radius: 0 24px 24px 0"] {
        border-radius: 0 0 24px 24px !important;
    }
}
</style>

<?php include 'includes/footer.php'; ?>