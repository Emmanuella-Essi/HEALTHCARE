<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dossier patient — HealthCare</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <link rel="stylesheet" href="../css/dossier.css">
  <link rel="stylesheet" href="../css/dashboard.css">
   

  <!-- Fonts (cohérence avec le dashboard) -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
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
 

    <div class="nav-item" onclick="Suivant('rdv', this)">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <span class="ni-label">Rendez-vous</span>
    </div>

    

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
    <a href="dossier.php" class="nav-item active">
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

    <div class="nav-item" onclick="Suivant('profil', this)">
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
onclick="window.location.href='../Accueil/home.php'"

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
        <div class="user-role">Dossier</div>
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
      <h1 class="page-title">Dossier médical</h1>
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

    <!-- RÉSUMÉ PATIENT -->
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
          <div class="summary-label">Examens / résultats</div>
          <div class="summary-value" id="statResultats">0</div>
        </div>
      </div>

      <div class="summary-card allergy">
        <div class="summary-icon" style="color:#F59E0B"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="summary-info">
          <div class="summary-label">Allergies</div>
          <div class="summary-value" id="statAllergies">—</div>
        </div>
      </div>

      <div class="summary-card blood">
        <div class="summary-icon" style="color:#EF4444"><i class="fa-solid fa-shield-heart"></i></div>
        <div class="summary-info">
          <div class="summary-label">Dernière mise à jour</div>
          <div class="summary-value" id="statLastUpdate">—</div>
        </div>
      </div>
    </div>

    <!-- FICHE MÉDICALE (APERÇU) -->
    <section class="dossier-grid">
      <div class="dossier-card dossier-card--hero">
        <div class="dossier-card-header">
          <div class="dossier-card-title">
            <i class="fa-solid fa-user-doctor"></i>
            <span>Fiche clinique</span>
          </div>
          <div class="dossier-card-subtitle">Aperçu structuré (données placeholders)</div>
        </div>

        <div class="clinical-stats">
          <div class="clinical-stat">
            <div class="cs-label">Patient</div>
            <div class="cs-value" id="clPatientName">—</div>
          </div>
          <div class="clinical-stat">
            <div class="cs-label">Date de naissance</div>
            <div class="cs-value" id="clDob">—</div>
          </div>
          <div class="clinical-stat">
            <div class="cs-label">Sexe</div>
            <div class="cs-value" id="clGender">—</div>
          </div>
          <div class="clinical-stat">
            <div class="cs-label">Groupe sanguin</div>
            <div class="cs-value" id="clBloodGroup">—</div>
          </div>
          <div class="clinical-stat">
            <div class="cs-label">Taille / Poids</div>
            <div class="cs-value" id="clStats">—</div>
          </div>
          <div class="clinical-stat">
            <div class="cs-label">Résumé</div>
            <div class="cs-value cs-value--muted" id="clSummary">—</div>
          </div>
        </div>

        <div class="dossier-hint">
          <i class="fa-solid fa-circle-info"></i>
          <span>Le dossier ci-dessous est structuré pour couvrir les informations médicales essentielles (branchable ensuite à la base de données).</span>
        </div>
      </div>

      <!-- COLONNE DROITE : DIAGNOSTICS / TRAITEMENTS RAPIDES -->
      <div class="dossier-card">
        <div class="dossier-card-header">
          <div class="dossier-card-title">
            <i class="fa-solid fa-notes-medical"></i>
            <span>Diagnostics & pathologies</span>
          </div>
          <div class="dossier-card-subtitle">Mises à jour (placeholders)</div>
        </div>

        <div class="tag-list" id="diagnosisTags"></div>

        <div class="divider"></div>

        <div class="dossier-card-title dossier-card-title--small">
          <i class="fa-solid fa-pills"></i>
          <span>Traitements en cours</span>
        </div>
        <div class="list-items" id="treatmentsList"></div>

        <div class="quick-links">
          <a class="quick-link" href="ordonnances.php"><i class="fa-solid fa-file-medical"></i> Ordonnances</a>
          <a class="quick-link" href="vaccins.php"><i class="fa-solid fa-syringe"></i> Vaccins</a>
        </div>
      </div>
    </section>

    <!-- SECTION : ANTECEDENTS -->
    <section class="section-block">
      <div class="section-title">
        <i class="fa-solid fa-book-medical"></i>
        <h2>Antécédents médicaux</h2>
      </div>

      <div class="two-col">
        <div class="dossier-subcard">
          <div class="dossier-subcard-header">
            <span class="subcard-title"><i class="fa-solid fa-stethoscope"></i> Personnels</span>
          </div>
          <ul class="bullet-list" id="antecedentsPersonnels"></ul>
          <div class="empty-state" id="antecedentsPersonnelsEmpty">—</div>
        </div>

        <div class="dossier-subcard">
          <div class="dossier-subcard-header">
            <span class="subcard-title"><i class="fa-solid fa-people-roof"></i> Familiaux</span>
          </div>
          <ul class="bullet-list" id="antecedentsFamiliaux"></ul>
          <div class="empty-state" id="antecedentsFamiliauxEmpty">—</div>
        </div>
      </div>

      <div class="two-col">
        <div class="dossier-subcard">
          <div class="dossier-subcard-header">
            <span class="subcard-title"><i class="fa-solid fa-heart-pulse"></i> Habitudes / mode de vie</span>
          </div>
          <div class="kv-list" id="habitsList"></div>
        </div>

        <div class="dossier-subcard">
          <div class="dossier-subcard-header">
            <span class="subcard-title"><i class="fa-solid fa-shield-virus"></i> Immunisation</span>
          </div>
          <div class="kv-list" id="immunizationList"></div>
          <div class="muted-block">
            Pour le détail complet, consultez l’onglet <b>Vaccins</b>.
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION : ALLERGIES -->
    <section class="section-block">
      <div class="section-title">
        <i class="fa-solid fa-allergies"></i>
        <h2>Allergies & réactions</h2>
      </div>

      <div class="dossier-subcard">
        <div class="table-responsive">
          <table class="medical-table" role="table">
            <thead>
              <tr>
                <th>Substance</th>
                <th>Type</th>
                <th>Réaction</th>
                <th>Gravité</th>
                <th>Statut</th>
              </tr>
            </thead>
            <tbody id="allergiesTableBody"></tbody>
          </table>
        </div>

        <div class="empty-state" id="allergiesEmpty">
          Aucune allergie déclarée pour le moment.
        </div>
      </div>
    </section>

    <!-- SECTION : EXAMENS & RÉSULTATS -->
    <section class="section-block">
      <div class="section-title">
        <i class="fa-solid fa-vial-circle-check"></i>
        <h2>Examens & résultats</h2>
      </div>

      <div class="timeline-section">
        <div class="controls-section" style="margin-top:4px;">
          <div class="filters">
            <button class="filter-btn active" data-filter="tous">Tous</button>
            <button class="filter-btn" data-filter="biologie">Biologie</button>
            <button class="filter-btn" data-filter="imagerie">Imagerie</button>
            <button class="filter-btn" data-filter="autre">Autre</button>
          </div>

          <div class="search-bar" style="margin-left:auto;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" placeholder="Rechercher un examen…" />
          </div>
        </div>

        <div class="timeline" id="timeline"></div>
      <div id="emptyState" style="display:none; margin-top:16px; padding:14px 18px; border:1px dashed var(--color-border); border-radius:var(--radius); background:rgba(255,255,255,0.7);">
          Aucun examen ne correspond à votre recherche.
        </div>

        <!-- placeholders : IDs déjà utilisés ailleurs dans la page, pour éviter les collisions on fournit un id unique -->
        <div id="emptyStateTimeline" style="display:none;"></div>

      </div>
    </section>

    <!-- SECTION : CONSULTATIONS / COMPTES RENDUS -->
    <section class="section-block">
      <div class="section-title">
        <i class="fa-solid fa-file-signature"></i>
        <h2>Consultations & comptes rendus</h2>
      </div>

      <div class="two-col">
        <div class="dossier-subcard">
          <div class="dossier-subcard-header">
            <span class="subcard-title"><i class="fa-solid fa-calendar-days"></i> Historique</span>
          </div>
          <div class="history-list" id="consultationsList"></div>
        </div>

        <div class="dossier-subcard">
          <div class="dossier-subcard-header">
            <span class="subcard-title"><i class="fa-solid fa-file-medical"></i> Documents associés</span>
          </div>
          <div class="history-list" id="documentsList"></div>

          <div class="muted-block">
            Le dossier médical contient des documents PDF / images, gérés via l’interface d’administration.
          </div>
        </div>
      </div>
    </section>

  </div>
</main>

<script>
  // ===============================
  // Données placeholders (remplaçables par BD)
  // ===============================
  const patientRecord = {
    meta: {
      patientName: "Martin Dupont",
      dob: "1986-04-12",
      gender: "Masculin",
      bloodGroup: "A+",
      lastUpdate: "2026-05-02",
      heightCm: 175,
      weightKg: 72,
      summary: "Hypertension artérielle — suivi régulier."
    },
    clinical: {
      diagnoses: [
        { label: "Hypertension artérielle", since: "2019", status: "En suivi" },
        { label: "Diabète de type 2", since: "2022", status: "Contrôlé" }
      ],
      treatments: [
        { name: "Ramipril", dosage: "5 mg", frequency: "1/j", note: "Traitement de fond" },
        { name: "Metformine", dosage: "500 mg", frequency: "2/j", note: "Avec repas" }
      ],
      antecedentsPersonnels: [
        "Chirurgie appendicectomie (2010)",
        "Allergie médicamenteuse : voir section Allergies"
      ],
      antecedentsFamiliaux: [
        "Hypertension artérielle chez le père",
        "Diabète de type 2 chez la mère"
      ],
      habits: {
        tabac: "Non",
        alcool: "Occasionnel",
        activite: "Marche 30 min / jour"
      },
      immunization: {
        derniereGrip: "2025-10",
        rappelTetan: "2024-06"
      },
      allergies: [
        { substance: "Amoxicilline", type: "Médicamenteuse", reaction: "Éruption cutanée", severity: "Modérée", status: "Confirmée" },
        { substance: "AINS (ibuprofène)", type: "Médicamenteuse", reaction: "Douleurs abdominales", severity: "Faible", status: "À éviter" }
      ],
      exams: [
        {
          id: "EX-001",
          type: "biologie",
          date: "2026-04-15",
          title: "Bilan sanguin — HbA1c / Lipides",
          description: "HbA1c : 6,8% — LDL : 0,94 g/L. ",
          tags: ["HbA1c", "LDL", "Contrôle"]
        },
        {
          id: "EX-002",
          type: "imagerie",
          date: "2026-03-21",
          title: "Échographie abdominale",
          description: "Résultats globalement sans anomalie majeure.",
          tags: ["Imagerie", "Abdomen"]
        },
        {
          id: "EX-003",
          type: "biologie",
          date: "2026-02-10",
          title: "Créatinine / Débit de filtration",
          description: "Fonction rénale dans les limites.",
          tags: ["Rénal", "Surveillance"]
        }
      ],
      consultations: [
        { id: "CS-019", date: "2026-04-20", doctor: "Dr. Kouamé", motif: "Suivi HTA & diabète", resultat: "Ajustement posologie — contrôle prévu." },
        { id: "CS-018", date: "2026-03-18", doctor: "Dr. Kouamé", motif: "Douleurs abdominales", resultat: "Examen complémentaire demandé." }
      ],
      documents: [
        { id: "DOC-101", date: "2026-04-15", label: "Compte-rendu bilan sanguin" },
        { id: "DOC-102", date: "2026-03-21", label: "Compte-rendu échographie" },
        { id: "DOC-103", date: "2026-02-10", label: "Résultats fonction rénale" }
      ]
    }
  };

  // ===============================
  // Utilitaires
  // ===============================
  const months = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"];
  function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    return `${String(d.getDate()).padStart(2,'0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
  }
  function capFirst(str){
    if(!str) return str;
    return str.charAt(0).toUpperCase()+str.slice(1);
  }

  // ===============================
  // DOM
  // ===============================
  const el = {
    topDate: document.getElementById('topDate'),
    statDocuments: document.getElementById('statDocuments'),
    statResultats: document.getElementById('statResultats'),
    statAllergies: document.getElementById('statAllergies'),
    statLastUpdate: document.getElementById('statLastUpdate'),

    clPatientName: document.getElementById('clPatientName'),
    clDob: document.getElementById('clDob'),
    clGender: document.getElementById('clGender'),
    clBloodGroup: document.getElementById('clBloodGroup'),
    clStats: document.getElementById('clStats'),
    clSummary: document.getElementById('clSummary'),

    diagnosisTags: document.getElementById('diagnosisTags'),
    treatmentsList: document.getElementById('treatmentsList'),

    antecedentsPersonnels: document.getElementById('antecedentsPersonnels'),
    antecedentsPersonnelsEmpty: document.getElementById('antecedentsPersonnelsEmpty'),
    antecedentsFamiliaux: document.getElementById('antecedentsFamiliaux'),
    antecedentsFamiliauxEmpty: document.getElementById('antecedentsFamiliauxEmpty'),

    habitsList: document.getElementById('habitsList'),
    immunizationList: document.getElementById('immunizationList'),

    allergiesTableBody: document.getElementById('allergiesTableBody'),
    allergiesEmpty: document.getElementById('allergiesEmpty'),

    timeline: document.getElementById('timeline'),
    emptyState: document.getElementById('emptyState'),

    consultationsList: document.getElementById('consultationsList'),
    documentsList: document.getElementById('documentsList'),

    searchInput: document.getElementById('searchInput'),
    filterBtns: document.querySelectorAll('.filter-btn')
  };

  // ===============================
  // Init
  // ===============================
  document.addEventListener('DOMContentLoaded', () => {
    // Date en haut
    if (el.topDate) el.topDate.textContent = `Mis à jour le ${formatDate(new Date().toISOString().split('T')[0])}`;

    // Stats
    const docsCount = patientRecord.clinical.documents.length;
    const examsCount = patientRecord.clinical.exams.length;
    const allergiesCount = patientRecord.clinical.allergies.length;

    if (el.statDocuments) el.statDocuments.textContent = docsCount;
    if (el.statResultats) el.statResultats.textContent = examsCount;
    if (el.statAllergies) el.statAllergies.textContent = allergiesCount ? `${allergiesCount}` : '0';
    if (el.statLastUpdate) el.statLastUpdate.textContent = formatDate(patientRecord.meta.lastUpdate);

    // Fiche clinique
    if (el.clPatientName) el.clPatientName.textContent = patientRecord.meta.patientName;
    if (el.clDob) el.clDob.textContent = formatDate(patientRecord.meta.dob);
    if (el.clGender) el.clGender.textContent = patientRecord.meta.gender;
    if (el.clBloodGroup) el.clBloodGroup.textContent = patientRecord.meta.bloodGroup;
    if (el.clStats) el.clStats.textContent = `${patientRecord.meta.heightCm} cm / ${patientRecord.meta.weightKg} kg`;
    if (el.clSummary) el.clSummary.textContent = patientRecord.meta.summary;

    // Diagnostics
    if (el.diagnosisTags) {
      el.diagnosisTags.innerHTML = patientRecord.clinical.diagnoses.map(d => `
        <span class="tag tag--diagnosis">${d.label}</span>
      `).join('');
    }

    // Traitements
    if (el.treatmentsList) {
      el.treatmentsList.innerHTML = patientRecord.clinical.treatments.map(t => `
        <div class="list-item">
          <div class="li-title"><i class="fa-solid fa-pills"></i> ${t.name}</div>
          <div class="li-meta">${t.dosage} — ${t.frequency}</div>
          <div class="li-note">${t.note}</div>
        </div>
      `).join('');
    }

    // Antécédents
    const ap = patientRecord.clinical.antecedentsPersonnels;
    if (el.antecedentsPersonnels) {
      el.antecedentsPersonnels.innerHTML = ap.map(x => `<li>${x}</li>`).join('');
      el.antecedentsPersonnelsEmpty.style.display = ap.length ? 'none' : 'block';
    }

    const af = patientRecord.clinical.antecedentsFamiliaux;
    if (el.antecedentsFamiliaux) {
      el.antecedentsFamiliaux.innerHTML = af.map(x => `<li>${x}</li>`).join('');
      el.antecedentsFamiliauxEmpty.style.display = af.length ? 'none' : 'block';
    }

    // Habitudes / immunisation
    if (el.habitsList) {
      el.habitsList.innerHTML = `
        <div class="kv"><span class="kv-k">Tabac</span><span class="kv-v">${patientRecord.clinical.habits.tabac}</span></div>
        <div class="kv"><span class="kv-k">Alcool</span><span class="kv-v">${patientRecord.clinical.habits.alcool}</span></div>
        <div class="kv"><span class="kv-k">Activité</span><span class="kv-v">${patientRecord.clinical.habits.activite}</span></div>
      `;
    }

    if (el.immunizationList) {
      el.immunizationList.innerHTML = `
        <div class="kv"><span class="kv-k">Grippe</span><span class="kv-v">${patientRecord.clinical.immunization.derniereGrip}</span></div>
        <div class="kv"><span class="kv-k">Tétanos</span><span class="kv-v">${patientRecord.clinical.immunization.rappelTetan}</span></div>
      `;
    }

    // Allergies
    if (el.allergiesTableBody) {
      const allergies = patientRecord.clinical.allergies;
      el.allergiesTableBody.innerHTML = allergies.map(a => `
        <tr>
          <td><b>${a.substance}</b></td>
          <td>${a.type}</td>
          <td>${a.reaction}</td>
          <td>
            <span class="severity severity--${a.severity.toLowerCase().includes('mod') ? 'mod' : a.severity.toLowerCase().includes('faib') ? 'low' : 'high'}">
              ${a.severity}
            </span>
          </td>
          <td>${a.status}</td>
        </tr>
      `).join('');

      if (el.allergiesEmpty) el.allergiesEmpty.style.display = allergies.length ? 'none' : 'block';
    }

    // Consultations
    if (el.consultationsList) {
      el.consultationsList.innerHTML = patientRecord.clinical.consultations.map(c => `
        <div class="history-item">
          <div class="history-item-top">
            <div class="history-title"><i class="fa-solid fa-user-doctor"></i> ${c.doctor}</div>
            <div class="history-date">${formatDate(c.date)}</div>
          </div>
          <div class="history-motif"><b>${c.id}</b> — ${c.motif}</div>
          <div class="history-result">${c.resultat}</div>
        </div>
      `).join('');
    }

    // Documents
    if (el.documentsList) {
      el.documentsList.innerHTML = patientRecord.clinical.documents.map(d => `
        <div class="history-item history-item--doc">
          <div class="history-item-top">
            <div class="history-title"><i class="fa-solid fa-file-medical"></i> ${d.label}</div>
            <div class="history-date">${formatDate(d.date)}</div>
          </div>
          <div class="history-result">ID : ${d.id}</div>
        </div>
      `).join('');
    }

    // Timeline examens
    initExamsTimeline();

    // Sidebar mobile
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (menuToggle && sidebar) {
      menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
      });

      document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
          sidebar.classList.remove('open');
        }
      });
    }
  });

  // ===============================
  // Timeline examens (filtre + recherche)
  // ===============================
  let currentFilter = 'tous';
  const exams = patientRecord.clinical.exams;

  function examTypeLabel(type) {
    if (type === 'biologie') return 'Biologie';
    if (type === 'imagerie') return 'Imagerie';
    return 'Autre';
  }

  function getDotClass(type){
    if(type==='biologie') return 'analyse';
    if(type==='imagerie') return 'chirurgie';
    return 'traitement';
  }

  function renderExamsTimeline(list){
    const timeline = el.timeline;
    if (!timeline) return;

    timeline.innerHTML = '';
    if (!list.length) {
      if (el.emptyState) el.emptyState.style.display = 'block';
      return;
    }
    if (el.emptyState) el.emptyState.style.display = 'none';

    list.sort((a,b)=> new Date(b.date) - new Date(a.date));

    list.forEach((entry, i) => {
      const tagsHTML = (entry.tags || []).map(t => `<span class="tag">${t}</span>`).join('');

      const item = document.createElement('div');
      item.className = 'timeline-item';
      item.style.animationDelay = `${i * 0.06}s`;

      item.innerHTML = `
        <div class="timeline-dot ${getDotClass(entry.type)}"></div>
        <div class="timeline-card">
          <div class="timeline-card-header">
            <span class="timeline-type ${entry.type}">${examTypeLabel(entry.type)}</span>
            <span class="timeline-date">${formatDate(entry.date)}</span>
          </div>
          <div class="timeline-card-body">
            <h3 class="timeline-title">${entry.title}</h3>
            <p class="timeline-desc">${entry.description}</p>
            <div class="timeline-tags">${tagsHTML}</div>
          </div>
          <div class="timeline-card-actions">
            <div class="card-tags" style="margin-top:2px;">
              <span class="tag tag--id">${entry.id}</span>
            </div>
          </div>
        </div>
      `;

      timeline.appendChild(item);
    });
  }

  function applyExamsFilters(){
    const query = (el.searchInput?.value || '').trim().toLowerCase();

    let filtered = [...exams];

    if (currentFilter !== 'tous') {
      filtered = filtered.filter(x => x.type === currentFilter);
    }

    if (query) {
      filtered = filtered.filter(x => {
        const hay = `${x.title} ${x.description} ${(x.tags||[]).join(' ')}`.toLowerCase();
        return hay.includes(query);
      });
    }

    renderExamsTimeline(filtered);
  }

  function initExamsTimeline(){
    if (!el.timeline) return;

    // Filtres
    el.filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        el.filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = btn.dataset.filter;
        applyExamsFilters();
      });
    });

    // Recherche
    if (el.searchInput) {
      let t;
      el.searchInput.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(() => applyExamsFilters(), 200);
      });
    }

    applyExamsFilters();
  }
</script>

</body>
</html>

