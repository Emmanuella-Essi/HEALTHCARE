<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthCare - Plateforme de santé numérique</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="../css/home.css">
    
</head>
<body>
    
<div class="big page" id="home">
  <div class="nav-bar">
    <div class="logo">Health<span>Care</span></div>
    <div class="land-nav-links">
      <button class="btn btn-ghost" onclick="showLogin('patient')">Connexion</button>
      <button class="btn btn-primary" onclick="showLogin('signup')">Inscription</button>
    </div>
  </div>
  
  <div class="hero">
    <div class="hero-tag">
      <span class="dot"></span>
      Plateforme de santé numérique
    </div>
    <h1>Prends soin de ton corps, c&#39;est le seul endroit que tu as pour vivre.</h1>
    <p>Télé-expertise médicale, suivi vaccinal intelligent et carnet de santé numérique unifié pour patients, médecins et administrateurs.</p>
  </div>
</div>

<!-- Login Modal -->
<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeLogin()">&times;</span>
    <h2><i class="fas fa-user-lock"></i> Connexion Patient</h2>
    <form id="loginForm">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="modal-buttons">
        <button type="button" class="btn btn-ghost" onclick="closeLogin()">Annuler</button>
        <button type="submit" class="btn btn-primary">Se connecter</button>
      </div>
    </form>
  </div>
</div>

<!-- Signup Modal -->
<div id="signupModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeLogin()">&times;</span>
    <h2><i class="fas fa-user-plus"></i> Inscription</h2>
    <form id="signupForm">
      <div class="form-group">
        <label for="signupEmail">Email</label>
        <input type="email" id="signupEmail" name="email" required>
      </div>
      <div class="form-group">
        <label for="signupPassword">Mot de passe</label>
        <input type="password" id="signupPassword" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirmer mot de passe</label>
        <input type="password" id="confirmPassword" name="confirm_password" required>
      </div>
      <div class="modal-buttons">
        <button type="button" class="btn btn-ghost" onclick="closeLogin()">Annuler</button>
        <button type="submit" class="btn btn-primary">S&#39;inscrire</button>
      </div>
    </form>
  </div>
</div>

<script>
function showLogin(type) {
  if (type === 'patient') {
    document.getElementById('loginModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
  } else if (type === 'signup') {
    document.getElementById('signupModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
}

function closeLogin() {
  document.querySelectorAll('.modal').forEach(modal => {
    modal.style.display = 'none';
  });
  document.body.style.overflow = 'auto';
}

// Close modal on outside click
window.onclick = function(event) {
  if (event.target.classList.contains('modal')) {
    closeLogin();
  }
}

// Close on ESC
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeLogin();
  }
});

// Form handlers (demo - replace with PHP/AJAX)
document.getElementById('loginForm').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Connexion simulée - Intégrez votre backend PHP ici !');
  closeLogin();
});

document.getElementById('signupForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const pwd = document.getElementById('signupPassword').value;
  const confirm = document.getElementById('confirmPassword').value;
  if (pwd !== confirm) {
    alert('Les mots de passe ne correspondent pas !');
    return;
  }
  alert('Inscription simulée - Intégrez votre backend PHP ici !');
  closeLogin();
});
</script>

</body>
</html>
