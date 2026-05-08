<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Rendez-vous — HealthCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="../css/rdv.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
 
  <!-- Logo -->
  <div class="sb-logo">
    <div class="logo-icon">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1de9b6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
      </svg>
    </div>
    <div class="logo-text">Health<span>Care</span></div>
  </div>
 
  <!-- Navigation -->
  <nav class="sb-nav">
 
    <div class="section-label">Principal</div>
 
    <a href="dashboard.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/>
          <polyline points="9 21 9 12 15 12 15 21"/>
        </svg>
      </div>
      <span class="ni-label">Tableau de bord</span>
    </a>
 
    <a href="rdv.php" class="nav-item active">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <span class="ni-label">Rendez-vous</span>
      <span class="ni-badge">4</span>
    </a>
 
    <a href="telexp.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="23 7 16 12 23 17 23 7"/>
          <rect x="1" y="5" width="15" height="14" rx="2"/>
        </svg>
      </div>
      <span class="ni-label">Télé-expertise</span>
    </a>
 
    <div class="section-label">Santé</div>
 
    <a href="vaccins.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="16" y2="8"/>
          <line x1="16" y1="2" x2="22" y2="8"/>
          <path d="M16 8l-3 3-1-1-5.5 5.5a2.5 2.5 0 0 0 3.5 3.5L15.5 13l-1-1 3-3"/>
          <line x1="5" y1="20" x2="2" y2="23"/>
        </svg>
      </div>
      <span class="ni-label">Vaccins</span>
    </a>
 
    <a href="dossier.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
      </div>
      <span class="ni-label">Dossier médical</span>
    </a>

    <a href="ordonnances.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
          <rect x="8" y="2" width="8" height="4" rx="1"/>
          <line x1="8" y1="12" x2="16" y2="12"/>
          <line x1="8" y1="16" x2="12" y2="16"/>
        </svg>
      </div>
      <span class="ni-label">Ordonnances</span>
    </a>
 
    <div class="section-label">Compte</div>
 
    <a href="profil.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="4"/>
          <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
        </svg>
      </div>
      <span class="ni-label">Mon profil</span>
    </a>
 
  </nav>
 
  <!-- Footer -->
  <div class="sb-footer">
    <div class="deconnect-btn" onclick="window.location.href='../index.php'">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      <span class="deconnect-label">Déconnexion</span>
    </div>
 
    <div class="sb-user">
      <div class="avatar">KD</div>
      <div class="user-info">
        <div class="user-name">Kofi Doe</div>
        <div class="user-role">Patient</div>
      </div>
    </div>
  </div>
 
</aside>

<!-- MAIN CONTENT -->
<main class="main">

    <!-- TOPBAR -->
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle">☰</button>
        <div class="page-title">
          <h1>Mes Rendez-vous</h1>
          <p>Gérez vos consultations médicales</p>
        </div>
      </div>
      <div class="topbar-right">
        <div class="notif-btn" id="notifBtn">
          🔔
          <span class="notif-badge" id="notifBadge">2</span>
        </div>
        <div class="user-avatar">KD</div>
      </div>
    </header>

    <!-- STATS RDV -->
    <section class="stats-row" id="statsRow">
      <div class="stat-card animate-in" style="--delay:0.1s">
        <div class="stat-icon" style="background:var(--color-primary)">📅</div>
        <div class="stat-info">
          <span class="stat-number" id="totalRdv">0</span>
          <span class="stat-label">Total RDV</span>
        </div>
      </div>
      <div class="stat-card animate-in" style="--delay:0.2s">
        <div class="stat-icon" style="background:var(--color-teal)">✅</div>
        <div class="stat-info">
          <span class="stat-number" id="confirmedRdv">0</span>
          <span class="stat-label">Confirmés</span>
        </div>
      </div>
      <div class="stat-card animate-in" style="--delay:0.3s">
        <div class="stat-icon" style="background:#F59E0B">⏳</div>
        <div class="stat-info">
          <span class="stat-number" id="pendingRdv">0</span>
          <span class="stat-label">En attente</span>
        </div>
      </div>
      <div class="stat-card animate-in" style="--delay:0.4s">
        <div class="stat-icon" style="background:#EF4444">❌</div>
        <div class="stat-info">
          <span class="stat-number" id="canceledRdv">0</span>
          <span class="stat-label">Annulés</span>
        </div>
      </div>
    </section>

    <!-- PROCHAIN RDV HIGHLIGHT -->
    <section class="next-rdv animate-in" id="nextRdvSection" style="--delay:0.5s">
      <div class="next-rdv-label">📌 Prochain rendez-vous</div>
      <div class="next-rdv-content" id="nextRdvContent">
        <p class="no-next">Aucun rendez-vous à venir</p>
      </div>
    </section>

    <!-- TOOLBAR -->
    <div class="toolbar animate-in" style="--delay:0.6s">
      <div class="search-box">
        <span>🔍</span>
        <input type="text" id="searchInput" placeholder="Rechercher un médecin, spécialité…" />
      </div>
      <div class="filters">
        <select id="filterStatus">
          <option value="all">Tous les statuts</option>
          <option value="confirme">Confirmé</option>
          <option value="en_attente">En attente</option>
          <option value="annule">Annulé</option>
          <option value="termine">Terminé</option>
        </select>
        <select id="filterMonth">
          <option value="all">Tous les mois</option>
          <option value="0">Janvier</option>
          <option value="1">Février</option>
          <option value="2">Mars</option>
          <option value="3">Avril</option>
          <option value="4">Mai</option>
          <option value="5">Juin</option>
          <option value="6">Juillet</option>
          <option value="7">Août</option>
          <option value="8">Septembre</option>
          <option value="9">Octobre</option>
          <option value="10">Novembre</option>
          <option value="11">Décembre</option>
        </select>
      </div>
      <button class="btn-primary" id="openModalBtn">
        + Nouveau RDV
      </button>
    </div>

    <!-- LISTE RDV -->
    <section class="rdv-list animate-in" id="rdvList" style="--delay:0.7s">
      <!-- Injecté par JS -->
    </section>

  </main>

  <!-- MODAL AJOUT/MODIF RDV -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal" id="modal">
      <div class="modal-header">
        <h2 id="modalTitle">Nouveau Rendez-vous</h2>
        <button class="modal-close" id="modalClose">✕</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="rdvId" />
        <div class="form-group">
          <label>Médecin / Établissement</label>
          <input type="text" id="inputMedecin" placeholder="Dr. Kouassi, Clinique Saint-Paul…" />
        </div>
        <div class="form-group">
          <label>Spécialité</label>
          <select id="inputSpecialite">
            <option value="">Choisir une spécialité</option>
            <option>Médecin généraliste</option>
            <option>Cardiologue</option>
            <option>Dermatologue</option>
            <option>Gynécologue</option>
            <option>Ophtalmologue</option>
            <option>Pédiatre</option>
            <option>Dentiste</option>
            <option>Radiologue</option>
            <option>Autre</option>
          </select>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Date</label>
            <input type="date" id="inputDate" />
          </div>
          <div class="form-group">
            <label>Heure</label>
            <input type="time" id="inputHeure" />
          </div>
        </div>
        <div class="form-group">
          <label>Lieu / Adresse</label>
          <input type="text" id="inputLieu" placeholder="Adresse ou lien téléconsultation" />
        </div>
        <div class="form-group">
          <label>Statut</label>
          <select id="inputStatut">
            <option value="en_attente">En attente</option>
            <option value="confirme">Confirmé</option>
            <option value="annule">Annulé</option>
            <option value="termine">Terminé</option>
          </select>
        </div>
        <div class="form-group">
          <label>Notes (optionnel)</label>
          <textarea id="inputNotes" placeholder="Motif de consultation, documents à apporter…" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" id="cancelBtn">Annuler</button>
        <button class="btn-primary" id="saveBtn">Enregistrer</button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast" id="toast"></div>
</body>
</html>