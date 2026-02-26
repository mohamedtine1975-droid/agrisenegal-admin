<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriSénégal 🌱</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --green-deep: #0d3320;
            --green-mid: #1a5c38;
            --green-bright: #2d9e5f;
            --green-light: #4cce85;
            --gold: #e8a830;
            --gold-light: #f5c842;
            --orange: #e05c1a;
            --cream: #fdf6e3;
            --dark: #0a0f0d;
            --text: #1a2620;
            --text-light: #5a7060;
            --white: #ffffff;
            --card-bg: #ffffff;
            --blue-accent: #1a6fa8;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(13, 51, 32, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(77, 206, 133, 0.15);
            padding: 0 2rem;
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--green-bright), var(--green-light));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(45, 158, 95, 0.4);
        }

        .nav-logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -0.5px;
        }

        .nav-logo-text span {
            color: var(--green-light);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.25s ease;
            letter-spacing: 0.2px;
        }

        .nav-links a:hover {
            color: var(--white);
            background: rgba(77, 206, 133, 0.15);
        }

        .nav-links .nav-cta {
            background: linear-gradient(135deg, var(--green-bright), var(--green-light));
            color: var(--white) !important;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(45, 158, 95, 0.35);
        }

        .nav-links .nav-cta:hover {
            background: linear-gradient(135deg, var(--green-light), var(--gold-light)) !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45, 158, 95, 0.5);
        }

        /* ===== HERO ===== */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--green-deep) 0%, #0f2e1a 40%, #162b1e 70%, #0d2016 100%);
            display: flex;
            align-items: center;
            padding: 100px 2rem 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero-bg-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(45, 158, 95, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(232, 168, 48, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(45, 158, 95, 0.06) 0%, transparent 40%);
        }

        .floating-shapes {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            animation: floatShape 8s ease-in-out infinite;
        }

        .shape-1 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(45,158,95,0.08), transparent);
            top: 10%; right: 15%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(232,168,48,0.07), transparent);
            bottom: 20%; left: 5%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 150px; height: 150px;
            background: radial-gradient(circle, rgba(76,206,133,0.1), transparent);
            top: 60%; right: 30%;
            animation-delay: 4s;
        }

        .shape-4 {
            width: 80px; height: 80px;
            background: radial-gradient(circle, rgba(232,168,48,0.15), transparent);
            top: 30%; left: 40%;
            animation-delay: 1s;
        }

        @keyframes floatShape {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(45, 158, 95, 0.15);
            border: 1px solid rgba(77, 206, 133, 0.3);
            color: var(--green-light);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.6s ease forwards;
        }

        .badge-dot {
            width: 6px; height: 6px;
            background: var(--green-light);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.5); }
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 5vw, 4.5rem);
            font-weight: 900;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease 0.1s forwards;
            opacity: 0;
        }

        .hero-title-accent {
            background: linear-gradient(135deg, var(--gold), var(--gold-light), var(--green-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            color: rgba(255,255,255,0.65);
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2.5rem;
            max-width: 480px;
            animation: fadeInUp 0.8s ease 0.2s forwards;
            opacity: 0;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease 0.3s forwards;
            opacity: 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--green-bright), var(--green-light));
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.85rem 1.8rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(45, 158, 95, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(45, 158, 95, 0.55);
            background: linear-gradient(135deg, var(--green-light), var(--gold-light));
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.85rem 1.8rem;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.4);
            color: var(--white);
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            align-items: center;
            animation: fadeInUp 0.8s ease 0.4s forwards;
            opacity: 0;
        }

        .stat { text-align: center; }

        .stat-number {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--gold-light);
            line-height: 1;
        }

        .stat-label {
            display: block;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        .stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255,255,255,0.15);
        }

        /* ===== HERO VISUAL ===== */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .hero-card-float {
            position: relative;
            width: 320px;
            height: 320px;
        }

        .hero-circle {
            width: 220px; height: 220px;
            background: linear-gradient(135deg, rgba(45,158,95,0.2), rgba(232,168,48,0.1));
            border: 2px solid rgba(77,206,133,0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: rotateSlow 20s linear infinite;
        }

        .circle-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .circle-emoji { font-size: 3rem; }

        .circle-text {
            font-family: 'Playfair Display', serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--green-light);
            letter-spacing: 2px;
        }

        @keyframes rotateSlow {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .float-card {
            position: absolute;
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 14px;
            padding: 0.75rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 4px;
            color: white;
            font-size: 0.8rem;
            min-width: 150px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .float-card .float-icon { font-size: 1.4rem; }
        .float-price { color: var(--gold-light); font-weight: 700; font-size: 0.85rem; }
        .float-status { color: var(--green-light); font-weight: 600; font-size: 0.8rem; }

        .card-1 { top: 0; right: -20px; animation: floatCard 4s ease-in-out infinite; }
        .card-2 { bottom: 30px; left: -30px; animation: floatCard 4s ease-in-out infinite 1.5s; }
        .card-3 { top: 40%; right: -40px; animation: floatCard 4s ease-in-out infinite 0.8s; }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== FEATURES ===== */
        .features-section {
            padding: 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-tag {
            display: inline-block;
            background: rgba(45, 158, 95, 0.12);
            color: var(--green-bright);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            margin-bottom: 1rem;
            border: 1px solid rgba(45,158,95,0.2);
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            color: var(--text);
            line-height: 1.15;
            margin-bottom: 1rem;
        }

        .section-sub {
            color: var(--text-light);
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .feature-card {
            position: relative;
            background: var(--white);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-decoration: none;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }

        .feature-green:hover { border-color: var(--green-bright); }
        .feature-orange:hover { border-color: var(--orange); }
        .feature-blue:hover { border-color: var(--blue-accent); }

        .feature-icon-wrap {
            width: 60px; height: 60px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .feature-green .feature-icon-wrap { background: rgba(45,158,95,0.1); }
        .feature-orange .feature-icon-wrap { background: rgba(224,92,26,0.1); }
        .feature-blue .feature-icon-wrap { background: rgba(26,111,168,0.1); }

        .feature-tag {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }

        .feature-green .feature-tag { color: var(--green-bright); }
        .feature-orange .feature-tag { color: var(--orange); }
        .feature-blue .feature-tag { color: var(--blue-accent); }

        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.75rem;
        }

        .feature-desc {
            color: var(--text-light);
            font-size: 0.9rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .feature-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px; height: 40px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .feature-green .feature-arrow { background: rgba(45,158,95,0.1); color: var(--green-bright); }
        .feature-orange .feature-arrow { background: rgba(224,92,26,0.1); color: var(--orange); }
        .feature-blue .feature-arrow { background: rgba(26,111,168,0.1); color: var(--blue-accent); }

        .feature-card:hover .feature-arrow { transform: translateX(4px); }

        .feature-bg-deco {
            position: absolute;
            bottom: -10px;
            right: 15px;
            font-size: 6rem;
            opacity: 0.05;
            pointer-events: none;
            transform: rotate(-10deg);
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-bg-deco { opacity: 0.09; transform: rotate(-5deg) scale(1.1); }

        /* ===== CULTURES PREVIEW ===== */
        .cultures-preview {
            background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
            padding: 6rem 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            max-width: 100%;
        }

        .cultures-left {
            max-width: 500px;
            margin: 0 auto;
            padding-left: 10%;
        }

        .cultures-left .section-tag {
            background: rgba(255,255,255,0.1);
            color: var(--green-light);
            border-color: rgba(255,255,255,0.15);
        }

        .cultures-right {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            padding-right: 10%;
        }

        .culture-pill {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .culture-pill:hover {
            background: rgba(255,255,255,0.13);
            transform: translateX(4px);
        }

        .culture-pill span:first-child { font-size: 1.8rem; }

        .culture-pill strong {
            display: block;
            color: var(--white);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .culture-pill small {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
        }

        /* ===== CTA ===== */
        .cta-section {
            padding: 6rem 2rem;
            text-align: center;
            background: var(--cream);
        }

        .cta-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: block;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .cta-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--text);
            margin-bottom: 1rem;
        }

        .cta-sub {
            color: var(--text-light);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 2.5rem;
        }

        /* ===== FOOTER ===== */
        footer {
            background: var(--green-deep);
            color: rgba(255,255,255,0.7);
            padding: 3rem 2rem;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--white);
        }

        .footer-logo span { color: var(--green-light); }

        .footer-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .footer-links a {
            text-decoration: none;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--green-light); }

        .footer-copy {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.3);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-content { grid-template-columns: 1fr; gap: 2rem; }
            .hero-visual { display: none; }
            .features-grid { grid-template-columns: 1fr; }
            .cultures-preview { grid-template-columns: 1fr; padding: 4rem 1.5rem; }
            .cultures-left { padding-left: 0; }
            .cultures-right { padding-right: 0; grid-template-columns: 1fr; }
            .footer-inner { flex-direction: column; text-align: center; }
            .nav-links { display: none; }
        }
        /* ===== RESPONSIVE MOBILE ===== */
.nav-links { display: flex; }
.nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 5px;
}
.nav-toggle span {
    width: 25px; height: 2px;
    background: #fff;
    border-radius: 2px;
    transition: all 0.3s;
}

@media (max-width: 768px) {
    .nav-toggle { display: flex; }
    .nav-links {
        display: none;
        position: fixed;
        top: 70px; left: 0; right: 0;
        background: var(--green-deep);
        flex-direction: column;
        padding: 1rem;
        gap: 0.25rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        z-index: 999;
    }
    .nav-links.open { display: flex; }
    .nav-links a {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-size: 1rem;
    }
    .hero-content {
        grid-template-columns: 1fr !important;
        text-align: center;
    }
    .hero-visual { display: none !important; }
    .hero-actions { justify-content: center; }
    .hero-stats { justify-content: center; }
    .features-grid { grid-template-columns: 1fr !important; }
    .cultures-preview {
        grid-template-columns: 1fr !important;
        padding: 4rem 1.5rem !important;
    }
    .cultures-left { padding-left: 0 !important; }
    .cultures-right { padding-right: 0 !important; grid-template-columns: 1fr 1fr !important; }
}
    </style>
</head>
<body>

<nav>
    <div class="nav-inner">
        <a href="../index.php" class="nav-logo">
            <div class="nav-logo-icon">🌱</div>
            <span class="nav-logo-text">Agri<span>Sénégal</span></span>
        </a>
        <div class="nav-toggle" onclick="document.querySelector('.nav-links').classList.toggle('open')">
    <span></span>
    <span></span>
    <span></span>
</div>

<ul class="nav-links">
    <li><a href="/">Accueil</a></li>
    <li><a href="/cultures.php">Cultures</a></li>
    <li><a href="/prix.php">Prix du marché</a></li>
    <li><a href="/diagnostic.php" class="nav-cta">🔬 Diagnostic IA</a></li>
</ul>
    </div>
</nav>