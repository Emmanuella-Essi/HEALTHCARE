<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Vaccins — HealthCare</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/vaccins.css" />
  <link rel="stylesheet" href="../css/dashboard.css" />
  <style>
    body {
      background: #F6FAFD;
    }
    .main-content {
      width: calc(100% - var(--sb-w-collapsed));
      height: 100vh;
      margin-left: var(--sb-w-collapsed);
      max-width: none;
      overflow-y: auto;
      overflow-x: hidden;
      transition: margin-left .28s cubic-bezier(0.4, 0, 0.2, 1), width .28s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar:hover + .main-content {
      width: calc(100% - var(--sb-w-expanded));
      margin-left: var(--sb-w-expanded);
    }
    .topbar {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(246, 250, 253, 0.94);
      backdrop-filter: blur(14px);
      padding-top: .25rem;
    }
    @media (max-width: 768px) {
      .main-content,
      .sidebar:hover + .main-content {
        width: calc(100% - var(--sb-w-collapsed));
        margin-left: var(--sb-w-collapsed);
        padding: 1rem;
      }
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->

<!-- SIDEBAR (même structure que les autres pages patient) -->
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

    <a href="accueil.php" class="nav-item">

      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/>
          <polyline points="9 21 9 12 15 12 15 21"/>
        </svg>
      </div>
      <span class="ni-label">Accueil</span>
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
 

    <a href="rdv.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <span class="ni-label">Rendez-vous</span>
    </a>

    

    <div class="section-label">Santé</div>

    <a href="vaccins.php" class="nav-item active">
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

  <div class="sb-footer">
    <a class="deconnect-btn" href="../Accueil/home.php">

      <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      <span class="deconnect-label">Déconnexion</span>
    </a>

    <div class="sb-user">
      <div class="avatar">MD</div>
      <div class="user-info">
        <div class="user-name">Patient</div>
        <div class="user-role">Dossier</div>
      </div>
    </div>
  </div>
</aside>

<!-- ═══════════════ MAIN ═══════════════ -->
<main class="main-content">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <button class="menu-toggle" id="menuToggle">☰</button>
      <div>
        <h1 class="page-title">Mes Vaccins</h1>
        <p class="page-subtitle">Suivi vaccinal complet</p>
      </div>
    </div>
    <div class="topbar-right">
      <button class="btn-notif" id="notifBtn">
        🔔
        <span class="notif-badge" id="notifCount">2</span>
      </button>
      <div class="user-avatar">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=patient" alt="avatar" />
      </div>
    </div>
  </header>

  <!-- STATS RAPIDES -->
  <section class="stats-row" id="statsRow">
    <div class="stat-card" data-delay="0">
      <div class="stat-icon" style="background: #e8f4fd;">💉</div>
      <div>
        <p class="stat-label">Total vaccins</p>
        <p class="stat-value" id="totalVaccins">0</p>
      </div>
    </div>
    <div class="stat-card" data-delay="1">
      <div class="stat-icon" style="background: #e8f7f0;">✅</div>
      <div>
        <p class="stat-label">Effectués</p>
        <p class="stat-value" id="vaccinsEffectues">0</p>
      </div>
    </div>
    <div class="stat-card" data-delay="2">
      <div class="stat-icon" style="background: #fff8e1;">⏳</div>
      <div>
        <p class="stat-label">À venir</p>
        <p class="stat-value" id="vaccinsAVenir">0</p>
      </div>
    </div>
    <div class="stat-card" data-delay="3">
      <div class="stat-icon" style="background: #fdecea;">⚠️</div>
      <div>
        <p class="stat-label">Rappels urgents</p>
        <p class="stat-value" id="vaccinsUrgents">0</p>
      </div>
    </div>
  </section>

  <!-- TOOLBAR -->
  <div class="toolbar">
    <div class="search-wrap">
      <span class="search-icon">🔍</span>
      <input type="text" id="searchInput" class="search-input" placeholder="Rechercher un vaccin…" />
    </div>
    <div class="filter-wrap">
      <button class="filter-btn active" data-filter="tous">Tous</button>
      <button class="filter-btn" data-filter="fait">Effectués</button>
      <button class="filter-btn" data-filter="a-venir">À venir</button>
      <button class="filter-btn" data-filter="rappel">Rappels</button>
    </div>
    <button class="btn-primary" id="btnAjouter">
      <span>＋</span> Ajouter un vaccin
    </button>
  </div>

  <!-- LISTE VACCINS -->
  <section class="vaccins-grid" id="vaccinsGrid">
    <!-- Cartes générées dynamiquement par vaccins.js -->
  </section>

  <p class="empty-state hidden" id="emptyState">
    Aucun vaccin trouvé. <br/>
    <small>Essayez un autre filtre ou ajoutez un vaccin.</small>
  </p>

</main>

<!-- ═══════════════ MODAL AJOUTER / MODIFIER ═══════════════ -->
<div class="modal-overlay hidden" id="modalOverlay">
  <div class="modal" id="modal">
    <div class="modal-header">
      <h2 id="modalTitle">Ajouter un vaccin</h2>
      <button class="modal-close" id="modalClose">✕</button>
    </div>
    <form id="vaccineForm" class="modal-form">
      <input type="hidden" id="vaccineId" />

      <div class="form-row">
        <div class="form-group">
          <label for="vaccinNom">Nom du vaccin *</label>
          <input type="text" id="vaccinNom" placeholder="Ex : BCG, Hépatite B…" required />
        </div>
        <div class="form-group">
          <label for="vaccinStatut">Statut *</label>
          <select id="vaccinStatut" required>
            <option value="">— Choisir —</option>
            <option value="fait">✅ Effectué</option>
            <option value="a-venir">⏳ À venir</option>
            <option value="rappel">⚠️ Rappel</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="vaccinDate">Date d'administration</label>
          <input type="date" id="vaccinDate" />
        </div>
        <div class="form-group">
          <label for="vaccinRappel">Date de rappel</label>
          <input type="date" id="vaccinRappel" />
        </div>
      </div>

      <div class="form-group">
        <label for="vaccinNotes">Notes / Observations</label>
        <textarea id="vaccinNotes" rows="3" placeholder="Effets secondaires, lot, établissement…"></textarea>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-secondary" id="btnAnnuler">Annuler</button>
        <button type="submit" class="btn-primary">💾 Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- ═══════════════ MODAL CONFIRMER SUPPRESSION ═══════════════ -->
<div class="modal-overlay hidden" id="deleteOverlay">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h2>Confirmer la suppression</h2>
      <button class="modal-close" id="deleteClose">✕</button>
    </div>
    <p style="padding: 0 1.5rem; color: var(--color-text-dark); opacity:.7;">
      Voulez-vous vraiment supprimer ce vaccin ? Cette action est irréversible.
    </p>
    <div class="modal-actions" style="margin-top:1.5rem; padding: 0 1.5rem 1.5rem;">
      <button class="btn-secondary" id="deleteCancelBtn">Annuler</button>
      <button class="btn-danger" id="deleteConfirmBtn">🗑️ Supprimer</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast hidden" id="toast"></div>

<script src="../js/vaccins.js"></script>
</body>
</html>
