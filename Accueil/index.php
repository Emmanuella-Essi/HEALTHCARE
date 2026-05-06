 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare - Connexion</title>
    <link rel="stylesheet" href="../css/index.css">
    <style>
        .tab-form {
            display: none;
        }
        .tab-form.active {
            display: block;
        }
        .login-tab.active {
            background-color: #1a3a5c;
            color: white;
            border-radius: 6px;
        }
    </style>
</head>
<body>


    <a href="home.php" class="btn-retour">&#8592; Retour</a>

    <div class="login-page">
        <div class="login-box">

            <!-- TITRE -->
            <div class="logo">Healthcare</div>
            <p class="login-subtitle">Votre plateforme de santé numérique</p>

            <!-- ONGLETS -->
            <div class="login-tabs">
                <div class="login-tab active" onclick="switchTab('patient', this)">Patient</div>
                <div class="login-tab" onclick="switchTab('medecin', this)">Médecin</div>
                <div class="login-tab" onclick="switchTab('admin', this)">Admin</div>
            </div>

            <!-- ============ FORMULAIRE PATIENT ============ -->
            <div id="form-patient" class="tab-form active">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" type="email" placeholder="nom&prenom@gmail.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password" placeholder="••••••••">
                </div>
                <button class="btn-submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte"
                          onclick="window.location.href='inscription.php?role=patient'">
                        Créer un compte
                    </span>
                </p>
            </div>

            <!-- ============ FORMULAIRE MÉDECIN ============ -->
            <div id="form-medecin" class="tab-form">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" type="email" placeholder="nom&prenom@gmail.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Numéro d'ordre médical</label>
                    <input class="form-input" type="text" placeholder="Ex: CM-12345">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password" placeholder="••••••••">
                </div>
                <button class="btn-submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte"
                          onclick="window.location.href='inscription.php?role=medecin'">
                        Créer un compte
                    </span>
                </p>
            </div>

            <!-- ============ FORMULAIRE ADMIN ============ -->
            <div id="form-admin" class="tab-form">
                <div class="form-group">
                    <label class="form-label">Identifiant admin</label>
                    <input class="form-input" type="text" placeholder="admin@healthcare.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Clé secrète</label>
                    <input class="form-input" type="password" placeholder="••••••••">
                </div>
                <button class="btn-submit">Se connecter</button>
            </div>

        </div>
    </div>

    <!-- ============ JAVASCRIPT ============ -->
    <script>
        function switchTab(role, el) {

            // 1. Cacher TOUS les formulaires
            var forms = document.querySelectorAll('.tab-form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].classList.remove('active');
            }

            // 2. Désactiver TOUS les onglets
            var tabs = document.querySelectorAll('.login-tab');
            for (var j = 0; j < tabs.length; j++) {
                tabs[j].classList.remove('active');
            }

            // 3. Afficher le bon formulaire
            document.getElementById('form-' + role).classList.add('active');

            // 4. Activer le bon onglet
            el.classList.add('active');
        }
    </script>

</body>
</html>