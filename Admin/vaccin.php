<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Suivi vaccinal - Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/admin_accueil.css">
  <link rel="stylesheet" href="../css/admin_shared.css">
  <script src="admin-sidebar.js" defer></script>
</head>
<body>
<div class="page" id="admin-vaccins">
  <div class="app-layout">
    <div class="sidebar">
      <div class="sb-logo">
        <div class="logo-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1de9b6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
          </svg>
        </div>
        <div class="logo-text">Health<span>Care</span></div>
      </div>

      <nav class="sb-nav">
        <div class="section-label">Supervision</div>
        <a href="accueil.php" class="nav-item"><div class="ni-icon"><i class="fa-solid fa-chart-pie"></i></div><span class="ni-label">Tableau de bord</span></a>
        <a href="utilisateur.php" class="nav-item"><div class="ni-icon"><i class="fa-solid fa-users"></i></div><span class="ni-label">Utilisateurs</span></a>
        <a href="medecin.php" class="nav-item"><div class="ni-icon"><i class="fa-solid fa-user-doctor"></i></div><span class="ni-label">Medecins</span></a>

        <div class="section-label">Systeme</div>
        <a href="consultation.php" class="nav-item"><div class="ni-icon"><i class="fa-solid fa-video"></i></div><span class="ni-label">Consultations</span></a>
        <a href="vaccin.php" class="nav-item active"><div class="ni-icon"><i class="fa-solid fa-syringe"></i></div><span class="ni-label">Suivi vaccinal</span></a>
        <a href="rapport.php" class="nav-item"><div class="ni-icon"><i class="fa-solid fa-chart-line"></i></div><span class="ni-label">Rapports</span></a>
        <a href="securite.php" class="nav-item"><div class="ni-icon"><i class="fa-solid fa-shield-halved"></i></div><span class="ni-label">Securite &amp; Logs</span></a>
      </nav>

      <div class="sb-footer" onclick="window.location.href='accueil.php?logout=1'">
        <i class="fa-solid fa-arrow-left-from-bracket"></i>
        <span class="deconnect-label">Deconnexion</span>
      </div>
      <div class="sb-user">
        <div class="avatar"><?= htmlspecialchars($adminInitiales, ENT_QUOTES, 'UTF-8') ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars(trim($adminPrenom . ' ' . $adminNom), ENT_QUOTES, 'UTF-8') ?></div>
          <div class="user-role">Super Administrateur</div>
        </div>
      </div>
    </div>

    <div class="main-content">
      <div class="admin-section">
        <div class="top-bar">
          <h2><i class="fa-solid fa-syringe"></i> Suivi vaccinal</h2>
          <div class="top-bar-actions">
            <span class="badge badge-green" style="padding:8px 16px;font-size:0.85rem">
              <i class="fa-solid fa-circle-check"></i> Donnees synchronisees
            </span>
          </div>
        </div>

        <div class="content-area">
          <div class="admin-welcome">
            <div>
              <span class="welcome-kicker">Prévention et suivi</span>
              <h1>Suivi vaccinal global</h1>
              <p>Surveillez la couverture vaccinale, les rappels en attente, les certificats à valider et les activités récentes du carnet vaccinal.</p>
            </div>
            <div class="welcome-actions">
              <a href="rapport.php" class="welcome-btn primary"><i class="fa-solid fa-chart-line"></i> Rapports</a>
              <a href="utilisateur.php" class="welcome-btn"><i class="fa-solid fa-users"></i> Patients</a>
            </div>
          </div>

          <div class="admin-context-grid">
            <div class="context-card">
              <i class="fa-solid fa-clock"></i>
              <strong>Rappels</strong>
              <span>Identifier les rappels en retard et prioriser les patients à contacter.</span>
            </div>
            <div class="context-card">
              <i class="fa-solid fa-file-circle-check"></i>
              <strong>Certificats</strong>
              <span>Contrôler les certificats déposés avant validation administrative.</span>
            </div>
            <div class="context-card">
              <i class="fa-solid fa-syringe"></i>
              <strong>Couverture</strong>
              <span>Suivre les vaccins administrés et les tendances mensuelles de prévention.</span>
            </div>
          </div>

          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-syringe" style="color:#0ea5e9"></i></div>
              <div class="stat-value">97,3 %</div>
              <div class="stat-label">Couverture vaccinale</div>
              <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +2,1 %</div>
            </div>
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-clock" style="color:#f59e0b"></i></div>
              <div class="stat-value">42</div>
              <div class="stat-label">Rappels en attente</div>
              <div class="stat-change change-down"><i class="fa-solid fa-triangle-exclamation"></i> A surveiller</div>
            </div>
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-circle-check" style="color:#10b981"></i></div>
              <div class="stat-value">1 126</div>
              <div class="stat-label">Vaccins administres</div>
              <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +86 ce mois</div>
            </div>
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-file-medical" style="color:#7c3aed"></i></div>
              <div class="stat-value">18</div>
              <div class="stat-label">Certificats a valider</div>
              <div class="stat-change">Validation admin</div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <span class="card-title"><i class="fa-solid fa-list-check"></i> Activite recente</span>
            </div>
            <div class="card-body">
              <div class="status-row">
                <div><div class="status-name">BCG</div><div class="status-sub">Centre HealthCare Plateau</div></div>
                <span class="badge badge-green">Effectue</span>
              </div>
              <div class="status-row">
                <div><div class="status-name">Hepatite B</div><div class="status-sub">Rappel programme</div></div>
                <span class="badge badge-yellow">En attente</span>
              </div>
              <div class="status-row">
                <div><div class="status-name">ROR</div><div class="status-sub">Certificat depose</div></div>
                <span class="badge badge-blue">A valider</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
