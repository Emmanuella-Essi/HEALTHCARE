<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil Patient</title>
</head>
<body>
    <div class="page" id="patient-dashboard">
  <div class="app-layout">
    <div class="sidebar">
      <div class="sidebar-logo">
        <div class="logo">Health<span>Care</span></div>
        <div class="sidebar-role">Espace Patient</div>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-section">
          <div class="nav-section-title">Principal</div>
          <div class="nav-item active" onclick="showPatientSection('accueil',this)">
            <span class="nav-icon">🏠</span>
          </div>
          <div class="nav-item" onclick="showPatientSection('rdv',this)">
            <span class="nav-icon">📅</span>
            <span class="nav-badge">4</span>
          </div>
          <div class="nav-item" onclick="showPatientSection('consultation',this)">
            <span class="nav-icon">🎥</span>
          </div>
        </div>
        <div class="nav-section">
          <div class="nav-section-title">Santé</div>
          <div class="nav-item" onclick="showPatientSection('vaccins',this)">
            <span class="nav-icon">💉</span> 
          </div>
          <div class="nav-item" onclick="showPatientSection('dossier',this)">
            <span class="nav-icon">📂</span> 
          </div>
          <div class="nav-item" onclick="showPatientSection('ordonnances',this)">
            <span class="nav-icon">📋</span> 
          </div>
        </div>
        <div class="nav-section">
          <div class="nav-section-title">Compte</div>
          <div class="nav-item" onclick="showPatientSection('profil',this)">
            <span class="nav-icon">👤</span> 
          </div>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="back-btn" onclick="showPage('landing')">← Déconnexion</div>
        <div class="sidebar-user">
          <div class="avatar avatar-teal">MD</div>
          <div class="sidebar-user-info">
            <div class="name">Martin Dupont</div>
            <div class="role">Patient</div>
          </div>
        </div>
      </div>
    </div>
 
    <div class="main-content">
    
</body>
</html>