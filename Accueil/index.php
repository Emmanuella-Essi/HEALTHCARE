 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare - Connexion</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
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

  
            <div class="logo">Healthcare</div>
            <p class="login-subtitle">Votre plateforme de santé numérique</p>

            <div class="login-tabs">
                <div class="login-tab active" onclick="switchTab('patient', this)">Patient</div>
                <div class="login-tab" onclick="switchTab('medecin', this)">Médecin</div>
                <div class="login-tab" onclick="switchTab('admin', this)">Admin</div>
            </div>


            <div id="form-patient" class="tab-form active">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" type="email" placeholder="nom&prenom@gmail.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password" >
                </div>
                <button class="btn-submit">Se connecter</button>
                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    <span class="lien-compte"
                          onclick="window.location.href='inscription.php?role=patient'">
                        Créer un compte
                    </span>
                </p>
            </div>

            <div id="form-medecin" class="tab-form">
                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" type="email" placeholder="nom&prenom@gmail.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Id medecin</label>
                    <input class="form-input" type="text"">
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

            <div id="form-admin" class="tab-form">
                <div class="form-group">
                    <label class="form-label">Identifiant admin</label>
                    <input class="form-input" type="text" placeholder="admin@healthcare.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input class="form-input" type="password">
                </div>
                <button class="btn-submit">Se connecter</button>
            </div>

        </div>
    </div>

  
    <script>
        function switchTab(role, el) {

            var forms = document.querySelectorAll('.tab-form');
            for (var i = 0; i < forms.length; i++) {
                forms[i].classList.remove('active');
            }

            var tabs = document.querySelectorAll('.login-tab');
            for (var j = 0; j < tabs.length; j++) {
                tabs[j].classList.remove('active');
            }


            document.getElementById('form-' + role).classList.add('active');

            el.classList.add('active');
        }
    </script>

</body>
</html>