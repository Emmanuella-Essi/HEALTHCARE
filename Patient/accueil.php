<?php
session_start();

if (isset($_GET['logout'])) {
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
    header('Location: ../Accueil/index.php');
    exit;
}

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'patient') {
    header('Location: ../Accueil/index.php?role=patient');
    exit;
}

$prenom = $_SESSION['prenom'] ?? 'Patient';
$nom = $_SESSION['nom'] ?? '';
$initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
if (trim($initiales) === '') {
    $initiales = 'PT';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil patient - HealthCare</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: #F6FAFD;
    }
    .main-content {
      width: calc(100% - var(--sb-w-collapsed));
      height: 100vh;
      margin-left: var(--sb-w-collapsed);
      overflow-y: auto;
      transition: margin-left .28s cubic-bezier(0.4, 0, 0.2, 1), width .28s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar:hover + .main-content {
      width: calc(100% - var(--sb-w-expanded));
      margin-left: var(--sb-w-expanded);
    }
    .home-wrap {
      min-height: 100%;
      width: 100%;
      max-width: 1180px;
      margin: 0 auto;
      padding: 38px clamp(22px, 4vw, 52px) 48px;
    }
    .home-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 18px;
      margin-bottom: 26px;
      background: #fff;
      border: 1px solid #dce8f2;
      border-radius: 8px;
      padding: 26px;
      box-shadow: 0 10px 30px rgba(10, 25, 49, 0.07);
    }
    .home-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(28px, 4vw, 42px);
      color: #0A1931;
      margin-bottom: 8px;
    }
    .home-subtitle {
      color: #4A6278;
      font-size: 15px;
    }
    .home-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 13px;
      border-radius: 999px;
      background: #E8F2FA;
      color: #1A3D63;
      font-size: 13px;
      font-weight: 600;
      white-space: nowrap;
    }
    .home-badge i {
      color: #4A7FA7;
    }
    .quick-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 18px;
      margin-top: 20px;
    }
    .quick-card {
      display: block;
      background: #fff;
      border: 1px solid #dce8f2;
      border-radius: 8px;
      padding: 20px;
      color: #0A1931;
      box-shadow: 0 8px 26px rgba(10, 25, 49, 0.08);
      transition: transform .18s ease, box-shadow .18s ease;
    }
    .quick-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 34px rgba(10, 25, 49, 0.12);
    }
    .quick-card i {
      color: #4A7FA7;
      font-size: 24px;
      margin-bottom: 14px;
    }
    .quick-card h2 {
      font-size: 18px;
      margin-bottom: 8px;
    }
    .quick-card p {
      color: #64748b;
      font-size: 14px;
      line-height: 1.5;
    }
    .info-panel {
      background: linear-gradient(135deg, #0A1931, #1A3D63);
      color: white;
      border-radius: 8px;
      padding: 24px;
      margin-top: 26px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 18px;
    }
    .info-item strong {
      display: block;
      font-size: 24px;
      margin-bottom: 4px;
    }
    .info-item span {
      color: #B3CFE5;
      font-size: 13px;
    }
    @media (max-width: 720px) {
      .main-content,
      .sidebar:hover + .main-content {
        width: calc(100% - var(--sb-w-collapsed));
        margin-left: var(--sb-w-collapsed);
      }
      .home-wrap {
        padding: 20px 14px 34px;
      }
      .home-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="sb-logo">
      <div class="logo-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1de9b6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
        </svg>
      </div>
      <div class="logo-text">Health<span>Care</span></div>
    </div>

    <nav class="sb-nav">
      <div class="section-label">Principal</div>
      <a href="accueil.php" class="nav-item active">
        <div class="ni-icon"><i class="fa-solid fa-house"></i></div>
        <span class="ni-label">Accueil</span>
      </a>
      <a href="telexp.php" class="nav-item">
        <div class="ni-icon"><i class="fa-solid fa-video"></i></div>
        <span class="ni-label">Tele-expertise</span>
      </a>
      <a href="rdv.php" class="nav-item">
        <div class="ni-icon"><i class="fa-solid fa-calendar-check"></i></div>
        <span class="ni-label">Rendez-vous</span>
      </a>

      <div class="section-label">Sante</div>
      <a href="vaccins.php" class="nav-item">
        <div class="ni-icon"><i class="fa-solid fa-syringe"></i></div>
        <span class="ni-label">Vaccins</span>
      </a>
      <a href="dossier.php" class="nav-item">
        <div class="ni-icon"><i class="fa-solid fa-folder-medical"></i></div>
        <span class="ni-label">Dossier medical</span>
      </a>
      <a href="ordonnances.php" class="nav-item">
        <div class="ni-icon"><i class="fa-solid fa-file-prescription"></i></div>
        <span class="ni-label">Ordonnances</span>
      </a>

      <div class="section-label">Compte</div>
      <a href="profil.php" class="nav-item">
        <div class="ni-icon"><i class="fa-solid fa-user"></i></div>
        <span class="ni-label">Mon profil</span>
      </a>
    </nav>

    <div class="sb-footer">
      <a class="deconnect-btn" href="accueil.php?logout=1">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span class="deconnect-label">Deconnexion</span>
      </a>
      <div class="sb-user">
        <div class="avatar"><?= htmlspecialchars($initiales, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars(trim($prenom . ' ' . $nom), ENT_QUOTES, 'UTF-8') ?></div>
          <div class="user-role">Patient</div>
        </div>
      </div>
    </div>
  </aside>

  <main class="main-content">
    <section class="home-wrap">
      <div class="home-header">
        <div>
          <h1 class="home-title">Bonjour <?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?></h1>
          <p class="home-subtitle">Bienvenue dans votre espace patient HealthCare.</p>
        </div>
        <div class="home-badge">
          <i class="fa-solid fa-shield-heart"></i>
          Espace patient
        </div>
      </div>

      <div class="quick-grid">
        <a class="quick-card" href="dossier.php">
          <i class="fa-solid fa-folder-medical"></i>
          <h2>Dossier medical</h2>
          <p>Consultez vos informations de sante, documents et resultats.</p>
        </a>
        <a class="quick-card" href="rdv.php">
          <i class="fa-solid fa-calendar-check"></i>
          <h2>Rendez-vous</h2>
          <p>Suivez vos consultations et vos prochains rendez-vous.</p>
        </a>
        <a class="quick-card" href="vaccins.php">
          <i class="fa-solid fa-syringe"></i>
          <h2>Vaccins</h2>
          <p>Gardez un oeil sur votre carnet vaccinal et vos rappels.</p>
        </a>
        <a class="quick-card" href="ordonnances.php">
          <i class="fa-solid fa-file-prescription"></i>
          <h2>Ordonnances</h2>
          <p>Retrouvez vos prescriptions et documents medicaux.</p>
        </a>
      </div>

      <div class="info-panel">
        <div class="info-item">
          <strong>24h/24</strong>
          <span>Acces a vos informations</span>
        </div>
        <div class="info-item">
          <strong>1</strong>
          <span>Espace patient centralise</span>
        </div>
        <div class="info-item">
          <strong>Secure</strong>
          <span>Session protegee apres connexion</span>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
