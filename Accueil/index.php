<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedConnect</title>

<!-- ✅ CSS bien placé -->
<link rel="stylesheet" href="../css/index.css">

</head>

<body>

<div class="page" id="login-page">
  <div class="login-page">
    <div class="login-box">

      <!-- LOGO -->
      <div class="logo">Med<span>Connect</span></div>
      <p class="login-subtitle">Votre plateforme de santé numérique</p>

      <!-- TABS (sans JS cassé) -->
      <div class="login-tabs">
        <div class="login-tab">Patient</div>
        <div class="login-tab">Médecin</div>
        <div class="login-tab">Admin</div>
      </div>

      <!-- ================= LOGIN ================= -->
      <div id="login-form">

        <div class="form-group">
          <label class="form-label">Adresse e-mail</label>
          <input class="form-input" type="email" placeholder="exemple@email.com">
        </div>

        <div class="form-group">
          <label class="form-label">Mot de passe</label>
          <input class="form-input" type="password">
        </div>

      
        <button class="btn-submit">
          Se connecter
        </button>

        <p style="text-align:center;margin-top:16px;font-size:0.8rem;">
          <span onclick="toggleForm()">
            Créer un compte
          </span>
        </p>

      </div>

      <!-- ================= REGISTER ================= -->
      <div id="register-form" style="display:none;">

        <div class="form-group">
          <label class="form-label">Nom</label>
          <input class="form-input" type="text" id="reg-nom">
        </div>

        <div class="form-group">
          <label class="form-label">Email</label>
          <input class="form-input" type="email" id="reg-email">
        </div>

        <div class="form-group">
          <label class="form-label">Mot de passe</label>
          <input class="form-input" type="password" id="reg-password">
        </div>

        <button class="btn-submit" onclick="register()">
          S'inscrire
        </button>

        <p style="text-align:center;margin-top:16px;font-size:0.8rem;">
          <span onclick="toggleForm()">
            Déjà un compte ? Se connecter
          </span>
        </p>

      </div>

    </div>
  </div>
</div>

<!-- ✅ JS propre -->
<script>

let isLogin = true;

function toggleForm() {
  const loginForm = document.getElementById("login-form");
  const registerForm = document.getElementById("register-form");

  if (isLogin) {
    loginForm.style.display = "none";
    registerForm.style.display = "block";
  } else {
    loginForm.style.display = "block";
    registerForm.style.display = "none";
  }

  isLogin = !isLogin;
}

function register() {
  const nom = document.getElementById("reg-nom").value;
  const email = document.getElementById("reg-email").value;
  const password = document.getElementById("reg-password").value;

  if (!nom || !email || !password) {
    alert("Remplis tous les champs !");
    return;
  }

  alert("Compte créé pour " + nom);
}

</script>

</body>
</html>