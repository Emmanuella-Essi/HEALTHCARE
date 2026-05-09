<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Médecins</title>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- CSS dédié -->
    <link rel="stylesheet" href="../css/admin_medecin.css">
</head>
<body>

<div class="page" id="admin-dashboard">
  <div class="app-layout">

    <!-- ══════════════════════════════════
         SIDEBAR
    ══════════════════════════════════ -->
    <div class="sidebar">
      <div class="sidebar-logo">
        <div class="logo">Health<span>Care</span></div>
        <div class="sidebar-role">Administration</div>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section">
          <div class="nav-section-title">Supervision</div>
          <div class="nav-item" onclick="window.location.href='accueil.php'">
            <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Vue d'ensemble
          </div>
          <div class="nav-item" onclick="window.location.href='utilisateur.php'">
            <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Utilisateurs
          </div>
          <div class="nav-item active" onclick="window.location.href='medecin.php'">
            <span class="nav-icon"><i class="fa-solid fa-user-doctor"></i></span> Médecins
          </div>
        </div>

        <div class="nav-section">
          <div class="nav-section-title">Système</div>
          <div class="nav-item" onclick="window.location.href='consultation.php'">
            <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Consultations
          </div>

          <div class="nav-item" onclick="window.location.href='rapport.php'">
            <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Rapports
          </div>
          <div class="nav-item" onclick="window.location.href='securite.php'">
            <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span> Sécurité &amp; Logs
          </div>
        </div>
      </nav>

      <div class="sidebar-footer">
onclick="window.location.href='../Accueil/home.php'"
          <i class="fa-solid fa-arrow-left-from-bracket"></i> Déconnexion
        </div>
        <div class="sidebar-user">
          <div class="avatar avatar-purple">AD</div>
          <div class="sidebar-user-info">
            <div class="name">Admin Système</div>
            <div class="role">Super Administrateur</div>
          </div>
        </div>
      </div>
    </div><!-- /sidebar -->

    <!-- ══════════════════════════════════
         CONTENU PRINCIPAL
    ══════════════════════════════════ -->
    <div class="main-content">
      <div id="a-medecins-admin" class="admin-section">

        <!-- Top bar -->
        <div class="top-bar">
          <h2><i class="fa-solid fa-user-doctor"></i> Gestion des Médecins</h2>
          <div class="top-bar-actions">
            <button class="btn-primary" onclick="alert('Ajouter un nouveau médecin')">
              <i class="fa-solid fa-plus"></i> Ajouter médecin
            </button>
          </div>
        </div>

        <!-- Contenu -->
        <div class="content-area">
          <div class="card">
            <div class="card-header">
              <span class="card-title"><i class="fa-solid fa-stethoscope"></i> Liste des médecins</span>
              <span class="card-subtitle">38 médecins actifs / 1 en attente</span>
            </div>
            <div class="card-body" style="padding:0">
              <table class="table">
                <thead>
                  <tr>
                    <th>Médecin</th>
                    <th>Spécialité</th>
                    <th>Patients</th>
                    <th>Consultations/mois</th>
                    <th>Note</th>
                    <th>Statut</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong>Dr. Sophie Martin</strong></td>
                    <td>Médecine générale</td>
                    <td>142</td>
                    <td>48</td>
                    <td>⭐ 4.9</td>
                    <td><span class="badge badge-green">Actif</span></td>
                  </tr>
                  <tr>
                    <td><strong>Dr. Ahmed Benali</strong></td>
                    <td>Pédiatrie / Vaccinations</td>
                    <td>98</td>
                    <td>62</td>
                    <td>⭐ 4.7</td>
                    <td><span class="badge badge-green">Actif</span></td>
                  </tr>
                  <tr>
                    <td><strong>Dr. Yasmine Koné</strong></td>
                    <td>Dermatologie</td>
                    <td>73</td>
                    <td>35</td>
                    <td>⭐ 4.8</td>
                    <td><span class="badge badge-green">Actif</span></td>
                  </tr>
                  <tr>
                    <td><strong>Dr. Paul Renaud</strong></td>
                    <td>Cardiologie</td>
                    <td>55</td>
                    <td>28</td>
                    <td>⭐ 4.6</td>
                    <td><span class="badge badge-green">Actif</span></td>
                  </tr>
                  <tr>
                    <td><strong>Dr. Inès Dubois</strong></td>
                    <td>Médecine générale</td>
                    <td>0</td>
                    <td>0</td>
                    <td>–</td>
                    <td><span class="badge badge-orange">En attente valid.</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div><!-- /admin-section -->
    </div><!-- /main-content -->
  </div><!-- /app-layout -->
</div><!-- /page -->

</body>
</html>