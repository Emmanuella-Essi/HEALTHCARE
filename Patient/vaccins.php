<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Vaccins – Dashboard Patient</title>
  <link rel="stylesheet" href="css/vaccins.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
</head>
<body>

<!-- ═══════════════ SIDEBAR ═══════════════ -->
 <!-- TU DOIS CHANGE LE SIDEBAR PAS CELUI DU DASHOARD PRINCIPAL -->
<!-- <aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">🏥</div>
    <span class="logo-text">MediCare</span>
  </div>

  <nav class="sidebar-nav">
    <a href="dashboard.html" class="nav-item">
      <span class="nav-icon">🏠</span>
      <span>Tableau de bord</span>
    </a>
    <a href="vaccins.html" class="nav-item active">
      <span class="nav-icon">💉</span>
      <span>Vaccins</span>
    </a>
    <a href="carnet.html" class="nav-item">
      <span class="nav-icon">📘</span>
      <span>Carnet de santé</span>
    </a>
    <a href="consultation.html" class="nav-item">
      <span class="nav-icon">💬</span>
      <span>Consultation</span>
    </a>
    <a href="profil.html" class="nav-item">
      <span class="nav-icon">👤</span>
      <span>Profil</span>
    </a>
  </nav>

  <a href="logout.php" class="sidebar-logout">
    <span>🚪</span>
    <span>Déconnexion</span>
  </a>
</aside> -->

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

<script src="js/vaccins.js"></script>
</body>
</html>