<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<!-- ============================================================ -->
<!-- ADMIN DASHBOARD                                               -->
<!-- ============================================================ -->
<div class="page" id="admin-dashboard">
  <div class="app-layout">
    <div class="sidebar">
      <div class="sidebar-logo">
        <div class="logo">Med<span>Connect</span></div>
        <div class="sidebar-role">Administration</div>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-section">
          <div class="nav-section-title">Supervision</div>
          <div class="nav-item active" onclick="showAdminSection('accueil',this)"><span class="nav-icon">📊</span> Vue d'ensemble</div>
          <div class="nav-item" onclick="showAdminSection('utilisateurs',this)"><span class="nav-icon">👥</span> Utilisateurs</div>
          <div class="nav-item" onclick="showAdminSection('medecins-admin',this)"><span class="nav-icon">👨‍⚕️</span> Médecins</div>
        </div>
        <div class="nav-section">
          <div class="nav-section-title">Système</div>
          <div class="nav-item" onclick="showAdminSection('consultations-admin',this)"><span class="nav-icon">🎥</span> Consultations</div>
          <div class="nav-item" onclick="showAdminSection('vaccins-admin',this)"><span class="nav-icon">💉</span> Suivi vaccinal</div>
          <div class="nav-item" onclick="showAdminSection('rapports',this)"><span class="nav-icon">📈</span> Rapports</div>
          <div class="nav-item" onclick="showAdminSection('securite',this)"><span class="nav-icon">🔐</span> Sécurité & Logs</div>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="back-btn" onclick="showPage('landing')">← Déconnexion</div>
        <div class="sidebar-user">
          <div class="avatar avatar-purple">AD</div>
          <div class="sidebar-user-info">
            <div class="name">Admin Système</div>
            <div class="role">Super Administrateur</div>
          </div>
        </div>
      </div>
    </div>
    <div class="main-content">
      <!-- VUE D'ENSEMBLE -->
      <div id="a-accueil" class="admin-section">
        <div class="top-bar">
          <h2>Vue d'ensemble – MedConnect</h2>
          <div class="top-bar-actions">
            <div class="notif-btn">🔔<span class="notif-dot"></span></div>
            <span class="badge badge-green" style="padding:8px 16px;font-size:0.85rem">Système opérationnel ✓</span>
          </div>
        </div>
        <div class="content-area">
          <div class="stats-grid">
            <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-value">1 284</div><div class="stat-label">Patients inscrits</div><div class="stat-change change-up">+47 ce mois</div></div>
            <div class="stat-card"><div class="stat-icon">👨‍⚕️</div><div class="stat-value">38</div><div class="stat-label">Médecins actifs</div><div class="stat-change change-up">+2 ce mois</div></div>
            <div class="stat-card"><div class="stat-icon">🎥</div><div class="stat-value">342</div><div class="stat-label">Téléconsultations/mois</div><div class="stat-change change-up">+18%</div></div>
            <div class="stat-card"><div class="stat-icon">💉</div><div class="stat-value">97.3%</div><div class="stat-label">Taux vaccination</div><div class="stat-change change-up">+2.1%</div></div>
          </div>
 
          <div class="grid-2 mb-24">
            <div class="card">
              <div class="card-header"><span class="card-title">📊 Consultations mensuelles</span></div>
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
            <div class="card">
              <div class="card-header"><span class="card-title">🖥️ Statut système</span></div>
              <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:14px">
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div><div style="font-size:0.88rem;font-weight:500">API Auth</div><div style="font-size:0.75rem;color:var(--muted)">Authentification</div></div>
                    <span class="badge badge-green">● Opérationnel</span>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div><div style="font-size:0.88rem;font-weight:500">API Consultation</div><div style="font-size:0.75rem;color:var(--muted)">Vidéo & messagerie</div></div>
                    <span class="badge badge-green">● Opérationnel</span>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div><div style="font-size:0.88rem;font-weight:500">API Vaccination</div><div style="font-size:0.75rem;color:var(--muted)">Carnet vaccinal</div></div>
                    <span class="badge badge-green">● Opérationnel</span>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div><div style="font-size:0.88rem;font-weight:500">Base de données</div><div style="font-size:0.75rem;color:var(--muted)">PostgreSQL cluster</div></div>
                    <span class="badge badge-green">● Opérationnel</span>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div><div style="font-size:0.88rem;font-weight:500">Serveur backup</div><div style="font-size:0.75rem;color:var(--muted)">Sauvegarde auto</div></div>
                    <span class="badge badge-orange">⚠ Latence +120ms</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
 
          <div class="grid-2">
            <div class="card">
              <div class="card-header"><span class="card-title">📋 Activité récente</span></div>
              <div class="card-body" style="padding:16px 24px">
                <div class="activity-feed">
                  <div class="activity-item"><div class="activity-icon ai-teal">💉</div><div class="activity-text"><div class="title">Vaccination enregistrée – Paul Fontaine</div><div class="time">Il y a 12 minutes • Dr. Sophie Martin</div></div></div>
                  <div class="activity-item"><div class="activity-icon ai-blue">🎥</div><div class="activity-text"><div class="title">Téléconsultation terminée – Marie Leclerc</div><div class="time">Il y a 28 minutes</div></div></div>
                  <div class="activity-item"><div class="activity-icon ai-orange">👤</div><div class="activity-text"><div class="title">Nouveau patient inscrit – Karim Ndiaye</div><div class="time">Il y a 1h14</div></div></div>
                  <div class="activity-item"><div class="activity-icon ai-teal">📋</div><div class="activity-text"><div class="title">Ordonnance créée – Robert Tissier</div><div class="time">Il y a 2h05 • Dr. Martin</div></div></div>
                  <div class="activity-item"><div class="activity-icon ai-blue">🔐</div><div class="activity-text"><div class="title">Connexion admin – accès dossiers</div><div class="time">Il y a 3h22 • IP 192.168.1.12</div></div></div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">⚠️ Alertes système</span></div>
              <div class="card-body">
                <div class="appt-card" style="border-color:rgba(232,119,60,0.3);background:rgba(253,232,216,0.3)">
                  <div>⚠️</div>
                  <div class="appt-info"><div class="appt-title">Latence serveur backup</div><div class="appt-meta">+120ms détecté depuis 06h00 – Monitoring actif</div></div>
                  <span class="badge badge-orange">Mineur</span>
                </div>
                <div class="appt-card" style="background:rgba(219,234,254,0.4);border-color:rgba(59,130,246,0.2)">
                  <div>ℹ️</div>
                  <div class="appt-info"><div class="appt-title">Mise à jour disponible v2.4.1</div><div class="appt-meta">Correctifs sécurité – Planifier la maintenance</div></div>
                  <button class="btn-sm btn-sm-primary">Planifier</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
 
     
 
    
      
 
     
     

</body>
</html>