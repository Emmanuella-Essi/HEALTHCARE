<<<<<<< HEAD
<?php
header('Location: dashboard.php');
exit();
?>
=======
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Accueil patient — HealthCare</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/accueil_patient.css">
  <link rel="stylesheet" href="../css/dossier.css">
</head>
<body>

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

    <a href="accueil.php" class="nav-item active">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/>
          <polyline points="9 21 9 12 15 12 15 21"/>
        </svg>
      </div>
      <span class="ni-label">Accueil</span>
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
    </div>
  </nav>

  <div class="sb-footer">
    <div class="deconnect-btn" onclick="window.location.href='accueil.php'">


      <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      <span class="deconnect-label">Déconnexion</span>
    </div>

    <div class="sb-user">
      <div class="avatar">MD</div>
      <div class="user-info">
        <div class="user-name">Patient</div>
        <div class="user-role">Accueil</div>
      </div>
    </div>
  </div>
</aside>

<!-- MAIN -->
<main class="main-content">

  <div class="topbar">
    <button class="menu-toggle" id="menuToggle">
      <i class="fas fa-bars"></i>
    </button>

    <div class="topbar-left">
      <h1 class="page-title">Accueil patient</h1>
      <span class="page-subtitle" id="topDate"></span>
    </div>

    <div class="topbar-right">
      <div class="notif-btn" title="Notifications">
        <i class="fa-solid fa-bell"></i>
        <span class="notif-dot"></span>
      </div>
      <div class="topbar-avatar">KA</div>
    </div>
  </div>

  <div class="page-body">

    <!-- Résumé -->
    <div class="summary-section">
      <div class="summary-card entries">
        <div class="summary-icon" style="color:#4A7FA7"><i class="fa-solid fa-file-medical"></i></div>
        <div class="summary-info">
          <div class="summary-label">Documents</div>
          <div class="summary-value" id="statDocuments">0</div>
        </div>
      </div>

      <div class="summary-card last">
        <div class="summary-icon" style="color:#0A8C74"><i class="fa-solid fa-stethoscope"></i></div>
        <div class="summary-info">
          <div class="summary-label">Résultats</div>
          <div class="summary-value" id="statResultats">0</div>
        </div>
      </div>

      <div class="summary-card allergy">
        <div class="summary-icon" style="color:#F59E0B"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="summary-info">
          <div class="summary-label">Dernier jour de passage</div>
          <div class="summary-value" id="statDernierJour">—</div>
        </div>
      </div>

      <div class="summary-card blood">
        <div class="summary-icon" style="color:#EF4444"><i class="fa-solid fa-link"></i></div>
        <div class="summary-info">
          <div class="summary-label">Accès dossier</div>
          <div class="summary-value"><a href="dossier.php" style="color:inherit;text-decoration:none">Ouvrir</a></div>
        </div>
      </div>
    </div>

    <!-- Résultats (exemples / démo) -->
    <section class="timeline-section">
      <div class="controls-section" style="margin-top:4px;">
        <div class="filters">
          <button class="filter-btn active" data-filter="tous">Tous</button>
          <button class="filter-btn" data-filter="resultat">Résultats</button>
          <button class="filter-btn" data-filter="document">Documents</button>
        </div>

        <div class="search-bar" style="margin-left:auto;">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="searchInput" placeholder="Rechercher un résultat…" />
        </div>
      </div>

      <div class="timeline" id="timeline"></div>

      <div id="emptyState" style="display:none; margin-top:16px; padding:14px 18px; border:1px dashed var(--color-border); border-radius:var(--radius); background:rgba(255,255,255,0.7);">
        Aucun résultat pour le moment.
      </div>
    </section>

    <!-- Actions rapides -->
    <section class="controls-section" style="margin-top:6px;">
      <a class="filter-btn" href="dossier.php"><i class="fa-solid fa-clock-rotate-left"></i> Voir tout le dossier</a>
      <a class="filter-btn" href="ordonnances.php"><i class="fa-solid fa-pills"></i> Ordonnances</a>
      <a class="filter-btn" href="vaccins.php"><i class="fa-solid fa-syringe"></i> Vaccins</a>
      <a class="filter-btn" href="profil.php"><i class="fa-solid fa-user"></i> Profil</a>
    </section>

  </div>
</main>

<!-- On réutilise le même script démo que sur l’ancienne page accueil_dossier.php -->
<script>
  const entries = [
    {
      id: 1,
      type: 'document',
      title: 'Compte rendu consultation',
      date: '2026-01-12',
      details: 'Document médical (démo)'
    },
    {
      id: 2,
      type: 'resultat',
      title: 'Résultat analyse sanguine',
      date: '2026-02-03',
      details: 'Résultat (démo)'
    }
  ];

  // Petites helpers (démo) si le JS original n’est pas chargé.
  const timeline = document.getElementById('timeline');
  const emptyState = document.getElementById('emptyState');
  const statDocuments = document.getElementById('statDocuments');
  const statResultats = document.getElementById('statResultats');

  function renderTimeline(list) {
    timeline.innerHTML = '';
    if (!list || list.length === 0) {
      emptyState.style.display = 'block';
      return;
    }
    emptyState.style.display = 'none';

    list.forEach(e => {
      const card = document.createElement('div');
      card.className = 'timeline-item';
      card.innerHTML = `
        <div class="ti-date">${e.date}</div>
        <div class="ti-title">${e.title}</div>
        <div class="ti-details">${e.details}</div>
      `;
      timeline.appendChild(card);
    });
  }

  function applyStats() {
    const docs = entries.filter(x => x.type === 'document').length;
    const res = entries.filter(x => x.type === 'resultat').length;
    if (statDocuments) statDocuments.textContent = docs;
    if (statResultats) statResultats.textContent = res;
  }

  renderTimeline(entries);
  applyStats();
</script>

</body>
</html>
>>>>>>> e25acc184000af7b472b37e31288af39fec1882a

