<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mes Ordonnances — HealthCare</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/ordonnances.css" />
</head>
<body>

  <!-- SIDEBAR  -->
  <!-- <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon">⚕</div>
      <span>HealthCare</span>
    </div>
    <nav class="sidebar-nav">
      <a href="dashboard.html" class="nav-item">
        <span class="nav-icon">🏠</span><span>Tableau de bord</span>
      </a>
      <a href="rendezvous.html" class="nav-item">
        <span class="nav-icon">📅</span><span>Rendez-vous</span>
      </a>
      <a href="vaccins.html" class="nav-item">
        <span class="nav-icon">💉</span><span>Vaccins</span>
      </a>
      <a href="ordonnance.html" class="nav-item active">
        <span class="nav-icon">📋</span><span>Ordonnances</span>
      </a>
      <a href="carnet.html" class="nav-item">
        <span class="nav-icon">📘</span><span>Carnet de santé</span>
      </a>
      <a href="consultation.html" class="nav-item">
        <span class="nav-icon">💬</span><span>Téléconsultation</span>
      </a>
      <a href="profil.html" class="nav-item">
        <span class="nav-icon">👤</span><span>Profil</span>
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="login.html" class="nav-item logout">
        <span class="nav-icon">🚪</span><span>Déconnexion</span>
      </a>
    </div>
  </aside> -->

  <!-- MAIN -->
  <main class="main">

    <!-- TOPBAR -->
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle">☰</button>
        <div class="page-title">
          <h1>Mes Ordonnances</h1>
          <p>Consultez et gérez vos prescriptions médicales</p>
        </div>
      </div>
      <div class="topbar-right">
        <div class="notif-btn">🔔<span class="notif-badge">2</span></div>
        <div class="user-avatar">KD</div>
      </div>
    </header>

    <!-- STATS -->
    <section class="stats-row">
      <div class="stat-card animate-in" style="--delay:.1s">
        <div class="stat-icon" style="background:var(--color-primary)">📋</div>
        <div class="stat-info">
          <span class="stat-number" id="statTotal">0</span>
          <span class="stat-label">Total</span>
        </div>
      </div>
      <div class="stat-card animate-in" style="--delay:.2s">
        <div class="stat-icon" style="background:var(--color-teal)">✅</div>
        <div class="stat-info">
          <span class="stat-number" id="statActive">0</span>
          <span class="stat-label">Actives</span>
        </div>
      </div>
      <div class="stat-card animate-in" style="--delay:.3s">
        <div class="stat-icon" style="background:#F59E0B">⏰</div>
        <div class="stat-info">
          <span class="stat-number" id="statExpire">0</span>
          <span class="stat-label">Expirées</span>
        </div>
      </div>
      <div class="stat-card animate-in" style="--delay:.4s">
        <div class="stat-icon" style="background:var(--color-accent)">💊</div>
        <div class="stat-info">
          <span class="stat-number" id="statMedicaments">0</span>
          <span class="stat-label">Médicaments</span>
        </div>
      </div>
    </section>

    <!-- TOOLBAR -->
    <div class="toolbar animate-in" style="--delay:.5s">
      <div class="search-box">
        <span>🔍</span>
        <input type="text" id="searchInput" placeholder="Rechercher médecin, médicament…" />
      </div>
      <div class="filters">
        <select id="filterStatut">
          <option value="all">Tous les statuts</option>
          <option value="active">Active</option>
          <option value="expiree">Expirée</option>
        </select>
      </div>
      <button class="btn-primary" id="openModalBtn">+ Ajouter</button>
    </div>

    <!-- LISTE ORDONNANCES -->
    <section class="ordo-grid animate-in" id="ordoGrid" style="--delay:.6s">
      <!-- Injecté par JS -->
    </section>

  </main>

  <!-- MODAL AJOUT/MODIF -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
      <div class="modal-header">
        <h2 id="modalTitle">Nouvelle Ordonnance</h2>
        <button class="modal-close" id="modalClose">✕</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Médecin prescripteur</label>
          <input type="text" id="inMedecin" placeholder="Dr. Kouassi Brou" />
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Date de prescription</label>
            <input type="date" id="inDate" />
          </div>
          <div class="form-group">
            <label>Date d'expiration</label>
            <input type="date" id="inExpiration" />
          </div>
        </div>
        <div class="form-group">
          <label>Spécialité / Motif</label>
          <input type="text" id="inMotif" placeholder="Cardiologue, suivi tension…" />
        </div>

        <!-- MÉDICAMENTS -->
        <div class="meds-section">
          <div class="meds-header">
            <label>💊 Médicaments prescrits</label>
            <button type="button" class="btn-add-med" id="addMedBtn">+ Ajouter</button>
          </div>
          <div id="medsList">
            <!-- Lignes médicaments injectées par JS -->
          </div>
        </div>

        <div class="form-group">
          <label>Notes / Instructions</label>
          <textarea id="inNotes" rows="3" placeholder="Conseils du médecin, précautions…"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" id="cancelBtn">Annuler</button>
        <button class="btn-primary" id="saveBtn">Enregistrer</button>
      </div>
    </div>
  </div>

  <!-- MODAL DETAIL -->
  <div class="modal-overlay" id="detailOverlay">
    <div class="modal modal-lg">
      <div class="modal-header">
        <h2>📋 Détail de l'ordonnance</h2>
        <button class="modal-close" id="detailClose">✕</button>
      </div>
      <div class="modal-body" id="detailContent">
        <!-- Injecté par JS -->
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" id="detailCloseBtn">Fermer</button>
        <button class="btn-primary" id="detailPrintBtn">🖨️ Imprimer</button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast" id="toast"></div>

  <script src="js/ordonnances.js"></script>
</body>
</html>