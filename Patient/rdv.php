<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tele expertise</title>
    <link rel="stylesheet" href="../css/rdv.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>
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