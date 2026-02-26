<?php
include 'includes/db.php';
include 'includes/header.php';

$diagnostic = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $culture = trim($_POST['culture'] ?? '');
    $image_base64 = '';
    $image_media_type = '';

    // Traitement image
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $mime = mime_content_type($_FILES['photo']['tmp_name']);
        if (in_array($mime, $allowed)) {
            $image_base64 = base64_encode(file_get_contents($_FILES['photo']['tmp_name']));
            $image_media_type = $mime;
        }
    }

    if (!empty($description) || !empty($image_base64)) {
        // Construction du message pour Claude
        $content = [];

        if (!empty($image_base64)) {
            $content[] = [
                "type" => "image",
                "source" => [
                    "type" => "base64",
                    "media_type" => $image_media_type,
                    "data" => $image_base64
                ]
            ];
        }

        $prompt = "Tu es un expert agronome spécialisé dans l'agriculture sénégalaise. ";
        if (!empty($culture)) $prompt .= "La culture concernée est : $culture. ";
        if (!empty($description)) $prompt .= "Symptômes décrits : $description. ";
        $prompt .= "
Analyse ce problème et réponds en français avec ce format exact :

🔍 DIAGNOSTIC
[Nom de la maladie ou problème identifié]

📋 DESCRIPTION
[Explication simple de la maladie en 2-3 phrases]

⚠️ CAUSES
[Les causes principales]

💊 TRAITEMENT
[Traitements disponibles au Sénégal, produits locaux et accessibles]

🛡️ PRÉVENTION
[Comment éviter ce problème la prochaine fois]

⏰ URGENCE
[Faible / Moyenne / Élevée — et pourquoi]";

        $content[] = ["type" => "text", "text" => $prompt];

        // Appel API Anthropic
        $data = json_encode([
            "model" => "claude-opus-4-6",
            "max_tokens" => 1024,
            "messages" => [["role" => "user", "content" => $content]]
        ]);

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . ANTHROPIC_API_KEY,
            'anthropic-version: 2023-06-01'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        if (isset($json['content'][0]['text'])) {
            $diagnostic = $json['content'][0]['text'];
        } else {
            $erreur = "Erreur lors de l'analyse. Vérifiez votre clé API.";
        }
    }
}

// Récupérer cultures pour le select
$cultures_list = $conn->query("SELECT nom, emoji FROM cultures ORDER BY nom ASC")->fetch_all(MYSQLI_ASSOC);
?>

<!-- PAGE HEADER -->
<section style="background: linear-gradient(135deg, #0d3320, #1a5c38); padding: 8rem 2rem 4rem; text-align: center;">
    <div style="max-width: 700px; margin: 0 auto;">
        <span class="section-tag" style="background: rgba(255,255,255,0.1); color: var(--green-light); border-color: rgba(255,255,255,0.15);">
            🤖 Intelligence Artificielle
        </span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 900; color: #fff; margin: 1rem 0;">
            Diagnostic de Plante
        </h1>
        <p style="color: rgba(255,255,255,0.65); font-size: 1.1rem; line-height: 1.8;">
            Décrivez les symptômes ou envoyez une photo — notre IA analyse et vous donne un diagnostic complet.
        </p>
    </div>
</section>

<!-- MAIN -->
<section style="max-width: 1100px; margin: 0 auto; padding: 4rem 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">

    <!-- FORMULAIRE -->
    <div>
        <div class="diag-card">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem;">
                Décrivez le problème
            </h2>
            <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 2rem;">Remplissez un ou plusieurs champs ci-dessous</p>

            <form method="POST" enctype="multipart/form-data">

                <!-- SELECT CULTURE -->
                <div class="form-group">
                    <label class="form-label">🌱 Quelle culture ?</label>
                    <select name="culture" class="form-input">
                        <option value="">-- Sélectionner une culture --</option>
                        <?php foreach($cultures_list as $cl): ?>
                        <option value="<?= htmlspecialchars($cl['nom']) ?>" <?= ($_POST['culture'] ?? '') === $cl['nom'] ? 'selected' : '' ?>>
                            <?= $cl['emoji'] ?> <?= htmlspecialchars($cl['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">
                    <label class="form-label">📝 Décrivez les symptômes</label>
                    <textarea name="description" class="form-input form-textarea" placeholder="Ex: Les feuilles jaunissent par le bas, des taches noires apparaissent, la tige devient molle..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <!-- PHOTO UPLOAD -->
                <div class="form-group">
                    <label class="form-label">📸 Photo de la plante (optionnel)</label>
                    <div class="upload-zone" id="upload-zone" onclick="document.getElementById('photo-input').click()">
                        <input type="file" name="photo" id="photo-input" accept="image/*" style="display:none" onchange="previewPhoto(this)">
                        <div id="upload-placeholder">
                            <span style="font-size: 2.5rem;">📷</span>
                            <p style="color: var(--text-light); margin-top: 0.5rem; font-size: 0.9rem;">Cliquez pour ajouter une photo</p>
                            <p style="color: var(--text-light); font-size: 0.75rem;">JPG, PNG — max 5MB</p>
                        </div>
                        <img id="photo-preview" src="" alt="" style="display:none; max-width: 100%; border-radius: 12px; max-height: 200px; object-fit: cover;">
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    <span id="btn-text">🔬 Lancer le diagnostic IA</span>
                    <span id="btn-loading" style="display:none;">⏳ Analyse en cours...</span>
                </button>

            </form>
        </div>

        <!-- CONSEILS -->
        <div class="tips-card">
            <h3 style="font-weight: 700; color: var(--text); margin-bottom: 1rem;">💡 Pour un meilleur diagnostic</h3>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.6rem;">
                <li style="display: flex; gap: 8px; font-size: 0.85rem; color: var(--text-light);">
                    <span>✅</span> Décrivez les symptômes en détail
                </li>
                <li style="display: flex; gap: 8px; font-size: 0.85rem; color: var(--text-light);">
                    <span>✅</span> Photo en pleine lumière, nette
                </li>
                <li style="display: flex; gap: 8px; font-size: 0.85rem; color: var(--text-light);">
                    <span>✅</span> Mentionnez depuis combien de temps
                </li>
                <li style="display: flex; gap: 8px; font-size: 0.85rem; color: var(--text-light);">
                    <span>✅</span> Sélectionnez la bonne culture
                </li>
            </ul>
        </div>
    </div>

    <!-- RÉSULTAT -->
    <div>
        <?php if (!empty($erreur)): ?>
        <div class="result-card result-error">
            <span style="font-size: 2rem;">❌</span>
            <p style="color: #c0392b; font-weight: 600; margin-top: 0.5rem;"><?= htmlspecialchars($erreur) ?></p>
        </div>

        <?php elseif (!empty($diagnostic)): ?>
        <div class="result-card result-success">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.08);">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #2d9e5f, #4cce85); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">🤖</div>
                <div>
                    <strong style="color: var(--text); display: block;">Diagnostic AgriSénégal IA</strong>
                    <span style="font-size: 0.75rem; color: var(--text-light);">Analysé par Claude AI</span>
                </div>
                <span style="margin-left: auto; background: #e8f5ee; color: #2d9e5f; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 20px;">✓ Complété</span>
            </div>
            <div class="diagnostic-text">
                <?= nl2br(htmlspecialchars($diagnostic)) ?>
            </div>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0,0,0,0.08);">
                <p style="font-size: 0.78rem; color: var(--text-light);">⚠️ Ce diagnostic est fourni à titre indicatif. Consultez un agronome pour les cas graves.</p>
            </div>
        </div>

        <?php else: ?>
        <div class="result-placeholder">
            <div style="text-align: center; padding: 4rem 2rem;">
                <span style="font-size: 5rem; display: block; margin-bottom: 1.5rem; opacity: 0.3;">🔬</span>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--text-light); font-weight: 600;">
                    Le diagnostic apparaîtra ici
                </h3>
                <p style="color: var(--text-light); font-size: 0.85rem; margin-top: 0.5rem; line-height: 1.7;">
                    Remplissez le formulaire et lancez l'analyse pour obtenir votre diagnostic.
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>

</section>

<!-- STYLES -->
<style>
.diag-card {
    background: #fff;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
}
.tips-card {
    background: #f0faf5;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid rgba(45,158,95,0.15);
}
.form-group { margin-bottom: 1.5rem; }
.form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.5rem;
    letter-spacing: 0.2px;
}
.form-input {
    width: 100%;
    padding: 0.85rem 1rem;
    border: 1.5px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    color: var(--text);
    background: #fafafa;
    transition: all 0.2s ease;
    outline: none;
}
.form-input:focus {
    border-color: #2d9e5f;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(45,158,95,0.1);
}
.form-textarea {
    min-height: 130px;
    resize: vertical;
    line-height: 1.6;
}
.upload-zone {
    border: 2px dashed rgba(0,0,0,0.12);
    border-radius: 14px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #fafafa;
}
.upload-zone:hover {
    border-color: #2d9e5f;
    background: #f0faf5;
}
.btn-submit {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #2d9e5f, #4cce85);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(45,158,95,0.35);
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(45,158,95,0.45);
}
.btn-submit:active { transform: translateY(0); }

.result-card {
    background: #fff;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05);
}
.result-success { border-top: 4px solid #2d9e5f; }
.result-error {
    text-align: center;
    padding: 2rem;
    border-top: 4px solid #e05c1a;
}
.result-placeholder {
    background: #f8f9fa;
    border-radius: 20px;
    border: 2px dashed rgba(0,0,0,0.08);
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.diagnostic-text {
    font-size: 0.92rem;
    line-height: 1.8;
    color: var(--text);
    white-space: pre-wrap;
}

@media (max-width: 768px) {
    section[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photo-preview').src = e.target.result;
            document.getElementById('photo-preview').style.display = 'block';
            document.getElementById('upload-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('btn-text').style.display = 'none';
    document.getElementById('btn-loading').style.display = 'inline';
    document.getElementById('submit-btn').disabled = true;
});
</script>

<?php include 'includes/footer.php'; ?>