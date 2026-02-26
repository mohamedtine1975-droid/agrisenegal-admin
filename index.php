<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-bg-pattern"></div>
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>
    <div class="hero-content">
        <div class="hero-badge">
            <span class="badge-dot"></span>
            Plateforme Agricole Sénégalaise
        </div>
        <h1 class="hero-title">
            Cultivez mieux,<br>
            <span class="hero-title-accent">vivez mieux</span>
        </h1>
        <p class="hero-subtitle">
            L'intelligence au service de la terre sénégalaise — cultures, marchés et diagnostic IA pour chaque agriculteur
        </p>
        <div class="hero-actions">
            <a href="/cultures.php" class="btn-primary">
                Explorer les cultures
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="/diagnostic.php" class="btn-secondary">
                Diagnostic IA
            </a>
        </div>
        <div class="hero-stats">
            <div class="stat">
                <span class="stat-number">12+</span>
                <span class="stat-label">Cultures</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-number">14</span>
                <span class="stat-label">Régions</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <span class="stat-number">IA</span>
                <span class="stat-label">Diagnostic</span>
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <div class="hero-card-float">
            <div class="float-card card-1">
                <span class="float-icon">🌾</span>
                <span>Mil — Thiès</span>
                <span class="float-price">185 FCFA/kg</span>
            </div>
            <div class="float-card card-2">
                <span class="float-icon">🥜</span>
                <span>Arachide — Kaolack</span>
                <span class="float-price">320 FCFA/kg</span>
            </div>
            <div class="float-card card-3">
                <span class="float-icon">🌿</span>
                <span>Diagnostic IA actif</span>
                <span class="float-status">En ligne</span>
            </div>
            <div class="hero-circle">
                <div class="circle-inner">
                    <span class="circle-emoji">🌱</span>
                    <span class="circle-text">AgriSN</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section class="features-section">
    <div class="section-header">
        <span class="section-tag">Nos Services</span>
        <h2 class="section-title">Tout ce dont vous avez besoin</h2>
        <p class="section-sub">Une plateforme complète pensée pour les agriculteurs sénégalais</p>
    </div>
    <div class="features-grid">

        <a href="/cultures.php" class="feature-card feature-green">
            <div class="feature-icon-wrap">
                <span class="feature-icon">🌾</span>
            </div>
            <div class="feature-tag">Guide complet</div>
            <h3 class="feature-title">Nos Cultures</h3>
            <p class="feature-desc">Arachide, mil, sorgho, riz, niébé... Fiches détaillées sur chaque culture avec calendriers de plantation.</p>
            <div class="feature-arrow">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
            <div class="feature-bg-deco">🌾</div>
        </a>

        <a href="/prix.php" class="feature-card feature-orange">
            <div class="feature-icon-wrap">
                <span class="feature-icon">📈</span>
            </div>
            <div class="feature-tag">Temps réel</div>
            <h3 class="feature-title">Prix du Marché</h3>
            <p class="feature-desc">Suivez les prix de vos récoltes dans toutes les régions du Sénégal. Ne vendez plus à perte.</p>
            <div class="feature-arrow">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
            <div class="feature-bg-deco">📈</div>
        </a>

        <a href="/diagnostic.php" class="feature-card feature-blue">
            <div class="feature-icon-wrap">
                <span class="feature-icon">🔬</span>
            </div>
            <div class="feature-tag">Intelligence Artificielle</div>
            <h3 class="feature-title">Diagnostic IA</h3>
            <p class="feature-desc">Décrivez les symptômes de votre plante ou envoyez une photo. Notre IA vous donne un diagnostic instantané.</p>
            <div class="feature-arrow">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
            <div class="feature-bg-deco">🔬</div>
        </a>

    </div>
</section>

<!-- CULTURES PREVIEW -->
<section class="cultures-preview">
    <div class="cultures-left">
        <span class="section-tag">Cultures du Sénégal</span>
        <h2 class="section-title" style="color: #fff;">Les cultures<br>de notre terroir</h2>
        <p style="color: rgba(255,255,255,0.7); margin: 1.5rem 0; line-height: 1.8;">Du nord au sud, découvrez les cultures adaptées à chaque région du Sénégal avec les meilleures pratiques agricoles.</p>
        <a href="/cultures.php" class="btn-primary">Voir toutes les cultures</a>
    </div>
    <div class="cultures-right">
        <div class="culture-pill">
            <span>🥜</span>
            <div>
                <strong>Arachide</strong>
                <small>Kaolack, Fatick, Kaffrine</small>
            </div>
        </div>
        <div class="culture-pill">
            <span>🌾</span>
            <div>
                <strong>Mil</strong>
                <small>Thiès, Diourbel, Louga</small>
            </div>
        </div>
        <div class="culture-pill">
            <span>🌿</span>
            <div>
                <strong>Sorgho</strong>
                <small>Tambacounda, Kédougou</small>
            </div>
        </div>
        <div class="culture-pill">
            <span>🍚</span>
            <div>
                <strong>Riz</strong>
                <small>Ziguinchor, Saint-Louis</small>
            </div>
        </div>
        <div class="culture-pill">
            <span>🌽</span>
            <div>
                <strong>Maïs</strong>
                <small>Kolda, Sédhiou</small>
            </div>
        </div>
        <div class="culture-pill">
            <span>🫘</span>
            <div>
                <strong>Niébé</strong>
                <small>Toutes régions</small>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="cta-content">
        <div class="cta-icon">🤖</div>
        <h2 class="cta-title">Votre plante est malade ?</h2>
        <p class="cta-sub">Notre intelligence artificielle analyse vos symptômes et vous propose un traitement adapté aux ressources disponibles au Sénégal.</p>
        <a href="/diagnostic.php" class="btn-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
            Lancer le diagnostic
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>