<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedConnect – Vue d'ensemble</title>
    <!-- Font Awesome 6 (version stable et disponible sur cdnjs) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- CSS dédié (chemin corrigé : le fichier PHP est dans /admin/) -->
    <link rel="stylesheet" href="../css/admin_accueil.css">
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
          <div class="nav-item active" onclick="window.location.href='accueil.php'">
            <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Vue d'ensemble
          </div>
          <div class="nav-item" onclick="window.location.href='utilisateur.php'">
            <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Utilisateurs
          </div>
          <div class="nav-item" onclick="window.location.href='medecin.php'">
            <span class="nav-icon"><i class="fa-solid fa-user-doctor"></i></span> Médecins
          </div>
        </div>

        <div class="nav-section">
          <div class="nav-section-title">Système</div>
          <div class="nav-item" onclick="window.location.href='consultation.php'">
            <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Consultations
          </div>
          <div class="nav-item" onclick="window.location.href='vaccin.php'">
            <span class="nav-icon"><i class="fa-solid fa-syringe"></i></span> Suivi vaccinal
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
        <div class="back-btn" onclick="window.location.href='../index.php'">
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
      <div id="a-accueil" class="admin-section">

        <!-- Top bar -->
        <div class="top-bar">
          <h2>Vue d'ensemble – MedConnect</h2>
          <div class="top-bar-actions">
            <div class="notif-btn" title="Notifications">
              <i class="fa-solid fa-bell"></i>
              <span class="notif-dot"></span>
            </div>
            <span class="badge badge-green" style="padding:8px 16px;font-size:0.85rem">
              <i class="fa-solid fa-circle-check"></i> Système opérationnel
            </span>
          </div>
        </div>

        <!-- Contenu -->
        <div class="content-area">

          <!-- ── KPI Cards ── -->
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-hospital-user" style="color:#0ea5e9"></i></div>
              <div class="stat-value">1 284</div>
              <div class="stat-label">Patients inscrits</div>
              <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +47 ce mois</div>
            </div>
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-user-doctor" style="color:#10b981"></i></div>
              <div class="stat-value">38</div>
              <div class="stat-label">Médecins actifs</div>
              <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +2 ce mois</div>
            </div>
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-video" style="color:#7c3aed"></i></div>
              <div class="stat-value">342</div>
              <div class="stat-label">Téléconsultations / mois</div>
              <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +18 %</div>
            </div>
            <div class="stat-card">
              <div class="stat-icon"><i class="fa-solid fa-syringe" style="color:#f59e0b"></i></div>
              <div class="stat-value">97,3 %</div>
              <div class="stat-label">Taux vaccination</div>
              <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +2,1 %</div>
            </div>
          </div>

          <!-- ── Graphique + Statut système ── -->
          <div class="grid-2 mb-24">

            <!-- Consultations mensuelles -->
            <div class="card">
              <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-chart-column"></i> Consultations mensuelles</span>
              </div>
              <div class="card-body">
                <div class="chart-bars">
                  <div class="bar-wrap"><div class="bar-val">280</div><div class="bar" style="height:70px"></div><div class="bar-label">Nov</div></div>
                  <div class="bar-wrap"><div class="bar-val">310</div><div class="bar" style="height:77px"></div><div class="bar-label">Déc</div></div>
                  <div class="bar-wrap"><div class="bar-val">295</div><div class="bar" style="height:74px"></div><div class="bar-label">Jan</div></div>
                  <div class="bar-wrap"><div class="bar-val">318</div><div class="bar" style="height:80px"></div><div class="bar-label">Fév</div></div>
                  <div class="bar-wrap"><div class="bar-val">302</div><div class="bar" style="height:76px"></div><div class="bar-label">Mar</div></div>
                  <div class="bar-wrap"><div class="bar-val">328</div><div class="bar" style="height:82px"></div><div class="bar-label">Avr</div></div>
                  <div class="bar-wrap"><div class="bar-val">342</div><div class="bar" style="height:86px;opacity:0.7"></div><div class="bar-label">Mai*</div></div>
                </div>
              </div>
            </div>

            <!-- Statut système -->
            <div class="card">
              <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-server"></i> Statut système</span>
              </div>
              <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:0">

                  <div class="status-row">
                    <div>
                      <div class="status-name">API Auth</div>
                      <div class="status-sub">Authentification</div>
                    </div>
                    <span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:.55rem"></i> Opérationnel</span>
                  </div>

                  <div class="status-row">
                    <div>
                      <div class="status-name">API Consultation</div>
                      <div class="status-sub">Vidéo &amp; messagerie</div>
                    </div>
                    <span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:.55rem"></i> Opérationnel</span>
                  </div>

                  <div class="status-row">
                    <div>
                      <div class="status-name">API Vaccination</div>
                      <div class="status-sub">Carnet vaccinal</div>
                    </div>
                    <span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:.55rem"></i> Opérationnel</span>
                  </div>

                  <div class="status-row">
                    <div>
                      <div class="status-name">Base de données</div>
                      <div class="status-sub">PostgreSQL cluster</div>
                    </div>
                    <span class="badge badge-green"><i class="fa-solid fa-circle" style="font-size:.55rem"></i> Opérationnel</span>
                  </div>

                  <div class="status-row">
                    <div>
                      <div class="status-name">Serveur backup</div>
                      <div class="status-sub">Sauvegarde auto</div>
                    </div>
                    <span class="badge badge-orange"><i class="fa-solid fa-triangle-exclamation" style="font-size:.7rem"></i> Latence +120 ms</span>
                  </div>

                </div>
              </div>
            </div>
          </div><!-- /grid-2 -->

          <!-- ── Activité récente + Alertes ── -->
          <div class="grid-2">

            <!-- Activité récente -->
            <div class="card">
              <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> Activité récente</span>
              </div>
              <div class="card-body" style="padding:10px 20px">
                <div class="activity-feed">
                  <div class="activity-item">
                    <div class="activity-icon ai-teal"><i class="fa-solid fa-syringe" style="color:#10b981"></i></div>
                    <div class="activity-text">
                      <div class="title">Vaccination enregistrée – Paul Fontaine</div>
                      <div class="time">Il y a 12 minutes &bull; Dr. Sophie Martin</div>
                    </div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-icon ai-blue"><i class="fa-solid fa-video" style="color:#0ea5e9"></i></div>
                    <div class="activity-text">
                      <div class="title">Téléconsultation terminée – Marie Leclerc</div>
                      <div class="time">Il y a 28 minutes</div>
                    </div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-icon ai-orange"><i class="fa-solid fa-user-plus" style="color:#f59e0b"></i></div>
                    <div class="activity-text">
                      <div class="title">Nouveau patient inscrit – Karim Ndiaye</div>
                      <div class="time">Il y a 1 h 14</div>
                    </div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-icon ai-teal"><i class="fa-solid fa-file-prescription" style="color:#10b981"></i></div>
                    <div class="activity-text">
                      <div class="title">Ordonnance créée – Robert Tissier</div>
                      <div class="time">Il y a 2 h 05 &bull; Dr. Martin</div>
                    </div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-icon ai-blue"><i class="fa-solid fa-lock" style="color:#0ea5e9"></i></div>
                    <div class="activity-text">
                      <div class="title">Connexion admin – accès dossiers</div>
                      <div class="time">Il y a 3 h 22 &bull; IP 192.168.1.12</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Alertes système -->
            <div class="card">
              <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-bell"></i> Alertes système</span>
              </div>
              <div class="card-body">

                <div class="appt-card" style="border-color:rgba(245,158,11,0.3);background:rgba(255,237,213,0.4)">
                  <i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b;font-size:1.1rem"></i>
                  <div class="appt-info">
                    <div class="appt-title">Latence serveur backup</div>
                    <div class="appt-meta">+120 ms détecté depuis 06 h 00 – Monitoring actif</div>
                  </div>
                  <span class="badge badge-orange">Mineur</span>
                </div>

                <div class="appt-card" style="background:rgba(219,234,254,0.4);border-color:rgba(59,130,246,0.25)">
                  <i class="fa-solid fa-circle-info" style="color:#3b82f6;font-size:1.1rem"></i>
                  <div class="appt-info">
                    <div class="appt-title">Mise à jour disponible v2.4.1</div>
                    <div class="appt-meta">Correctifs sécurité – Planifier la maintenance</div>
                  </div>
                  <button class="btn-sm btn-sm-primary" onclick="window.location.href='securite.php'">
                    Planifier
                  </button>
                </div>

              </div>
            </div>

          </div><!-- /grid-2 -->
        </div><!-- /content-area -->
      </div><!-- /a-accueil -->
    </div><!-- /main-content -->

  </div><!-- /app-layout -->
</div><!-- /page -->

</body>
</html>