<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare - Inscription</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <style>
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
        }
        .form-col {
            display: flex;
            flex-direction: column;
        }
        .login-box {
            width: 450px;
            margin: auto; /* Centrage */
        }
        .separateur {
            border: none;
            border-top: 1px dashed #ccc;
            margin: 15px 0;
        }
        .section-label {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 10px;
            font-weight: bold;
        }
        select, .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box; /* Évite que l'input dépasse */
        }
        /* Gestion de l'affichage des onglets */
        .tab-form { display: none; }
        .tab-form.active { display: block; }
    </style>
</head>
<body>
    <a href="home.php" class="btn-retour">&#8592; Retour</a>
    
    <div class="login-page">
        <div class="login-box">
            <div class="logo">Healthcare</div>
            <p class="login-subtitle">Votre plateforme de santé numérique</p>
            
            <div class="login-tabs">
                <div class="login-tab active" onclick="switchTab('patient', this)">Patient</div>
                <div class="login-tab" onclick="switchTab('medecin', this)">Médecin</div>
                <div class="login-tab" onclick="switchTab('admin', this)">Admin</div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label class="form-label">Nom</label>
                    <input class="form-input" type="text" required>
                </div>
                <div class="form-col">
                    <label class="form-label">Prénom</label>
                    <input class="form-input" type="text" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Adresse e-mail</label>
                <input class="form-input" type="email" required>
            </div>

            <div class="form-row" style="margin-top:15px;">
                <div class="form-col">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password" required>
                </div>
                <div class="form-col">
                    <label class="form-label">Téléphone</label>
                    <input class="form-input" type="tel">
                </div>
            </div>

            <div id="form-patient" class="tab-form active">
                <div class="form-group">
                    <label class="form-label">Groupe sanguin</label>
                    <select>
                        <option value="">-- Choisir --</option>
                        <option>A+</option><option>A-</option>
                        <option>B+</option><option>B-</option>
                        <option>O+</option><option>O-</option>
                    </select>
                </div>
            </div>

            <div id="form-medecin" class="tab-form">
                <hr class="separateur">
                <p class="section-label">Informations médecin</p>
                <div class="form-group">
                    <label class="form-label">Numéro d'ordre médical</label>
                    <input class="form-input" type="text" placeholder="Ex: CM-12345">
                </div>
                <div class="form-group">
                    <label class="form-label">Spécialité</label>
                    <select>
                        <option value="">-- Choisir --</option>
                        <option>Généraliste</option>
                        <option>Cardiologue</option>
                    </select>
                </div>
            </div>

            <div id="form-admin" class="tab-form">
                <hr class="separateur">
                <p class="section-label">Informations administrateur</p>
                <div class="form-group">
                    <label class="form-label">Code d'accès admin</label>
                    <input class="form-input" type="password" placeholder="••••••••">
                </div>
            </div>

            <button class="btn-submit" style="margin-top: 20px; width: 100%;" onclick="inscrire()">
                S'inscrire
            </button>

            <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                Déjà un compte ?
                <span class="lien-compte" style="color:blue; cursor:pointer;" onclick="window.location.href='index.php'">
                    Se connecter
                </span>
            </p>
        </div>
    </div>

    <script>
   var roleActif = 'patient';

function switchTab(role, el) {
    roleActif = role;
    var forms = document.querySelectorAll('.tab-form');
    forms.forEach(f => f.classList.remove('active'));
    var tabs = document.querySelectorAll('.login-tab');
    tabs.forEach(t => t.classList.remove('active'));
    document.getElementById('form-' + role).classList.add('active');
    el.classList.add('active');
}

function inscrire() {

    // Récupère les champs communs
    var nom = document.querySelectorAll('input[type="text"]')[0].value;
    var email = document.querySelector('input[type="email"]').value;
    var password = document.querySelectorAll('input[type="password"]')[0].value;
    var tel = document.querySelector('input[type="tel"]').value;

    // Vérifie les champs communs
    if (nom === '' || email === '' || password === '') {
        alert('Veuillez remplir : Nom, Email et Mot de passe !');
        return;
    }

    // Vérifie les champs spécifiques selon le rôle
    if (roleActif === 'medecin') {
        var idMedecin = document.querySelector('#form-medecin input[type="text"]').value;
        if (idMedecin === '') {
            alert('Veuillez remplir le numéro d\'ordre médical !');
            return;
        }
        window.location.href = '../Medecin/accueil.php';

    } else if (roleActif === 'admin') {
        var codeAdmin = document.querySelector('#form-admin input[type="password"]').value;
        if (codeAdmin === '') {
            alert('Veuillez remplir le code d\'accès admin !');
            return;
        }
        window.location.href = '../Admin/accueil.php';

    } else {
        // patient par défaut
        window.location.href = '../Patient/accueil.php';
    }
}

   
</script>
         


</body>
</html>