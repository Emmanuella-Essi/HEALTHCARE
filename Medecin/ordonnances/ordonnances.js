/* =====================================================
   ORDONNANCES.JS — LOGIQUE JAVASCRIPT
   Dashboard Médecin — Module Ordonnances Numériques
   Tous les commentaires expliquent chaque ligne/bloc
   ===================================================== */

/* =============================================
   1. DONNÉES SIMULÉES
   (En production : remplacées par des appels PHP/API)
   ============================================= */

/* Tableau principal des ordonnances fictives */
const ORDONNANCES_DATA = [
  {
    id: 1,                                           /* Identifiant unique */
    numero: "ORD-2025-001",                          /* Numéro lisible */
    patient: {
      nom: "Marie Dupont",                           /* Nom complet */
      age: 34,                                       /* Âge en années */
      avatar: "https://ui-avatars.com/api/?name=Marie+Dupont&background=B3CFE5&color=1A3D63&size=60"
    },
    diagnostic: "Infection respiratoire haute",      /* Motif de la prescription */
    medicaments: [                                   /* Liste des médicaments */
      { nom: "Amoxicilline", dose: "1g",    freq: "2×/jour",  duree: "7 jours"  },
      { nom: "Paracétamol",  dose: "500mg", freq: "3×/jour",  duree: "5 jours"  },
      { nom: "Ibuprofène",   dose: "400mg", freq: "Au besoin", duree: "3 jours" }
    ],
    instructions: "Prendre les antibiotiques jusqu'à la fin du traitement. Bien s'hydrater.",
    date:       "2025-04-10",                        /* Date de création */
    expiration: "2025-05-10",                        /* Date d'expiration */
    statut:     "active",                            /* Statut : active | expiree | renouvelee | annulee */
    envoye:     true                                 /* Envoyée au patient par email */
  },
  {
    id: 2,
    numero: "ORD-2025-002",
    patient: {
      nom: "Paul Bernard",
      age: 62,
      avatar: "https://ui-avatars.com/api/?name=Paul+Bernard&background=4A7FA7&color=fff&size=60"
    },
    diagnostic: "Hypertension artérielle",
    medicaments: [
      { nom: "Amlodipine",   dose: "5mg",  freq: "1×/jour", duree: "30 jours" },
      { nom: "Ramipril",     dose: "10mg", freq: "1×/jour", duree: "30 jours" }
    ],
    instructions: "Surveiller la tension quotidiennement. Régime sans sel.",
    date:       "2025-03-01",
    expiration: "2025-03-31",
    statut:     "expiree",
    envoye:     true
  },
  {
    id: 3,
    numero: "ORD-2025-003",
    patient: {
      nom: "Sophie Lambert",
      age: 8,
      avatar: "https://ui-avatars.com/api/?name=Sophie+Lambert&background=0A8C74&color=fff&size=60"
    },
    diagnostic: "Otite moyenne aiguë",
    medicaments: [
      { nom: "Amoxicilline/Clavulanate", dose: "500mg", freq: "2×/jour", duree: "10 jours" },
      { nom: "Ibuprofène pédiatrique",   dose: "200mg", freq: "3×/jour", duree: "5 jours"  }
    ],
    instructions: "Administrer les médicaments avec de la nourriture. Revoir dans 10 jours.",
    date:       "2025-04-22",
    expiration: "2025-05-22",
    statut:     "active",
    envoye:     false
  },
  {
    id: 4,
    numero: "ORD-2025-004",
    patient: {
      nom: "Ahmed Karim",
      age: 45,
      avatar: "https://ui-avatars.com/api/?name=Ahmed+Karim&background=1A3D63&color=fff&size=60"
    },
    diagnostic: "Diabète type 2 — traitement de fond",
    medicaments: [
      { nom: "Metformine",  dose: "1000mg", freq: "2×/jour", duree: "90 jours" },
      { nom: "Sitagliptine", dose: "100mg", freq: "1×/jour", duree: "90 jours" }
    ],
    instructions: "Prendre avec les repas. Contrôle glycémique hebdomadaire.",
    date:       "2025-02-15",
    expiration: "2025-05-15",
    statut:     "renouvelee",
    envoye:     true
  },
  {
    id: 5,
    numero: "ORD-2025-005",
    patient: {
      nom: "Claire Moreau",
      age: 29,
      avatar: "https://ui-avatars.com/api/?name=Claire+Moreau&background=047857&color=fff&size=60"
    },
    diagnostic: "Anxiété — trouble du sommeil",
    medicaments: [
      { nom: "Mélatonine",  dose: "1mg",   freq: "Le soir",  duree: "30 jours" },
      { nom: "Hydroxyzine", dose: "25mg",  freq: "Le soir",  duree: "14 jours" }
    ],
    instructions: "Prendre 30 minutes avant le coucher. Ne pas conduire après la prise.",
    date:       "2025-04-05",
    expiration: "2025-05-05",
    statut:     "active",
    envoye:     true
  },
  {
    id: 6,
    numero: "ORD-2025-006",
    patient: {
      nom: "Lucas Petit",
      age: 5,
      avatar: "https://ui-avatars.com/api/?name=Lucas+Petit&background=7C3AED&color=fff&size=60"
    },
    diagnostic: "Rhinopharyngite",
    medicaments: [
      { nom: "Doliprane pédiatrique", dose: "250mg", freq: "3×/jour", duree: "5 jours"  },
      { nom: "Physiomer",             dose: "Spray", freq: "3×/jour", duree: "7 jours"  }
    ],
    instructions: "Lavage de nez avant chaque application. Bien aérer la chambre.",
    date:       "2025-04-28",
    expiration: "2025-05-28",
    statut:     "active",
    envoye:     false
  },
  {
    id: 7,
    numero: "ORD-2025-007",
    patient: {
      nom: "Fatou Diallo",
      age: 38,
      avatar: "https://ui-avatars.com/api/?name=Fatou+Diallo&background=D97706&color=fff&size=60"
    },
    diagnostic: "Gastrite chronique",
    medicaments: [
      { nom: "Oméprazole",   dose: "20mg", freq: "1×/jour", duree: "14 jours" },
      { nom: "Sucralfate",   dose: "1g",   freq: "3×/jour", duree: "14 jours" }
    ],
    instructions: "Prendre à jeun le matin. Éviter café, alcool et anti-inflammatoires.",
    date:       "2025-01-10",
    expiration: "2025-02-10",
    statut:     "expiree",
    envoye:     true
  },
  {
    id: 8,
    numero: "ORD-2025-008",
    patient: {
      nom: "Marc Lefevre",
      age: 71,
      avatar: "https://ui-avatars.com/api/?name=Marc+Lefevre&background=DC2626&color=fff&size=60"
    },
    diagnostic: "Insuffisance cardiaque — suivi",
    medicaments: [
      { nom: "Furosémide",   dose: "40mg",   freq: "Le matin",  duree: "60 jours" },
      { nom: "Bisoprolol",   dose: "2.5mg",  freq: "1×/jour",   duree: "60 jours" },
      { nom: "Spironolactone", dose: "25mg", freq: "1×/jour",   duree: "60 jours" }
    ],
    instructions: "Peser quotidiennement. Consulter si prise de poids > 2kg en 48h.",
    date:       "2025-04-01",
    expiration: "2025-06-01",
    statut:     "active",
    envoye:     true
  },
  {
    id: 9,
    numero: "ORD-2025-009",
    patient: {
      nom: "Isabelle Roux",
      age: 52,
      avatar: "https://ui-avatars.com/api/?name=Isabelle+Roux&background=2563EB&color=fff&size=60"
    },
    diagnostic: "Hypothyroïdie",
    medicaments: [
      { nom: "Lévothyroxine", dose: "75µg", freq: "Le matin", duree: "90 jours" }
    ],
    instructions: "Prendre à jeun, 30 min avant le petit-déjeuner. Contrôle TSH dans 6 semaines.",
    date:       "2025-03-20",
    expiration: "2025-06-20",
    statut:     "active",
    envoye:     true
  },
  {
    id: 10,
    numero: "ORD-2025-010",
    patient: {
      nom: "Thomas Garcia",
      age: 16,
      avatar: "https://ui-avatars.com/api/?name=Thomas+Garcia&background=0A8C74&color=fff&size=60"
    },
    diagnostic: "Acné inflammatoire",
    medicaments: [
      { nom: "Doxycycline",     dose: "100mg",  freq: "1×/jour",  duree: "60 jours" },
      { nom: "Érythromycine gel", dose: "2%",   freq: "2×/jour",  duree: "60 jours" }
    ],
    instructions: "Protéger du soleil pendant le traitement. Éviter les produits irritants.",
    date:       "2025-04-15",
    expiration: "2025-06-15",
    statut:     "active",
    envoye:     false
  },
  {
    id: 11,
    numero: "ORD-2025-011",
    patient: {
      nom: "Nadia Ben Ali",
      age: 41,
      avatar: "https://ui-avatars.com/api/?name=Nadia+Ben+Ali&background=4A7FA7&color=fff&size=60"
    },
    diagnostic: "Migraine chronique",
    medicaments: [
      { nom: "Sumatriptan", dose: "50mg",  freq: "Au besoin", duree: "—"        },
      { nom: "Propranolol", dose: "40mg",  freq: "2×/jour",   duree: "30 jours" }
    ],
    instructions: "Prendre le sumatriptan dès les premiers signes. Max 2 prises/24h.",
    date:       "2025-02-28",
    expiration: "2025-03-28",
    statut:     "annulee",
    envoye:     false
  },
  {
    id: 12,
    numero: "ORD-2025-012",
    patient: {
      nom: "René Blanc",
      age: 66,
      avatar: "https://ui-avatars.com/api/?name=René+Blanc&background=1A3D63&color=fff&size=60"
    },
    diagnostic: "Arthrose du genou",
    medicaments: [
      { nom: "Ibuprofène",    dose: "400mg", freq: "3×/jour",  duree: "10 jours" },
      { nom: "Glucosamine",   dose: "1500mg", freq: "1×/jour", duree: "90 jours" }
    ],
    instructions: "Appliquer du froid après effort. Kinésithérapie recommandée.",
    date:       "2025-04-18",
    expiration: "2025-07-18",
    statut:     "active",
    envoye:     true
  }
];

/* Compteur pour générer les nouveaux numéros d'ordonnance */
let nextOrdNum = 13;    /* Prochain numéro auto-incrémenté */

/* =============================================
   2. ÉTAT GLOBAL DE L'APPLICATION
   ============================================= */

/* Objet centralisé contenant l'état courant de la page */
const State = {
  all:       [...ORDONNANCES_DATA],  /* Toutes les ordonnances (source de vérité) */
  filtered:  [...ORDONNANCES_DATA],  /* Ordonnances après application des filtres */
  page:      1,                      /* Page courante de la pagination */
  perPage:   8,                      /* Nombre de lignes par page */
  sortCol:   "date",                 /* Colonne de tri active */
  sortDir:   "desc",                 /* Direction du tri : "asc" ou "desc" */
  editId:    null,                   /* ID de l'ordonnance en cours d'édition (null = ajout) */
  previewId: null,                   /* ID de l'ordonnance affichée en aperçu */
  medCount:  1                       /* Compteur de lignes médicament dans le formulaire */
};

/* =============================================
   3. SÉLECTEURS DOM
   (Tous les éléments HTML récupérés une seule fois)
   ============================================= */

const D = {
  /* Sidebar & navigation */
  sidebar:        document.getElementById("sidebar"),          /* Barre latérale */
  sidebarClose:   document.getElementById("sidebarClose"),     /* Bouton fermer sidebar */
  sidebarOverlay: document.getElementById("sidebarOverlay"),   /* Fond sombre mobile */
  hamburger:      document.getElementById("hamburger"),        /* Bouton menu hamburger */

  /* Topbar */
  topbarDate:     document.getElementById("topbarDate"),       /* Affichage date */

  /* Statistiques */
  statTotal:      document.getElementById("statTotal"),        /* Compteur total */
  statActive:     document.getElementById("statActive"),       /* Compteur actives */
  statExpiree:    document.getElementById("statExpiree"),      /* Compteur expirées */
  statEnvoyee:    document.getElementById("statEnvoyee"),      /* Compteur envoyées */

  /* Filtres */
  searchInput:    document.getElementById("searchInput"),      /* Champ recherche texte */
  filterStatut:   document.getElementById("filterStatut"),     /* Filtre par statut */
  filterPatient:  document.getElementById("filterPatient"),    /* Filtre par patient */
  filterPeriode:  document.getElementById("filterPeriode"),    /* Filtre par période */
  btnApply:       document.getElementById("btnApply"),         /* Bouton appliquer filtres */
  btnReset:       document.getElementById("btnReset"),         /* Bouton réinitialiser */

  /* Tableau */
  ordTableBody:   document.getElementById("ordTableBody"),     /* Corps du tableau */
  resultCount:    document.getElementById("resultCount"),      /* Compteur résultats */
  checkAll:       document.getElementById("checkAll"),         /* Checkbox tout sélectionner */
  sortables:      document.querySelectorAll(".sortable"),       /* En-têtes triables */
  btnExport:      document.getElementById("btnExport"),        /* Bouton export CSV */
  btnPrint:       document.getElementById("btnPrint"),         /* Bouton imprimer */

  /* Pagination */
  paginInfo:      document.getElementById("paginInfo"),        /* Texte info pagination */
  paginBtns:      document.getElementById("paginBtns"),        /* Boutons pages */

  /* Modal formulaire */
  modalForm:      document.getElementById("modalForm"),        /* Fond modal formulaire */
  modalFormTitle: document.getElementById("modalFormTitle"),   /* Titre de la modal */
  modalFormClose: document.getElementById("modalFormClose"),   /* Bouton fermer modal */
  btnCancelForm:  document.getElementById("btnCancelForm"),    /* Bouton annuler */
  btnSave:        document.getElementById("btnSave"),          /* Bouton enregistrer */
  btnPreview:     document.getElementById("btnPreview"),       /* Bouton aperçu */
  btnNewOrdonnance: document.getElementById("btnNewOrdonnance"), /* Bouton nouvelle ordonnance */

  /* Champs du formulaire */
  formId:           document.getElementById("formId"),           /* ID caché */
  formPatient:      document.getElementById("formPatient"),      /* Select patient */
  formDiagnostic:   document.getElementById("formDiagnostic"),   /* Diagnostic */
  formDate:         document.getElementById("formDate"),         /* Date ordonnance */
  formExpiration:   document.getElementById("formExpiration"),   /* Date expiration */
  formInstructions: document.getElementById("formInstructions"), /* Instructions */
  formStatut:       document.getElementById("formStatut"),       /* Statut */
  formEnvoyer:      document.getElementById("formEnvoyer"),      /* Toggle envoi email */
  medsList:         document.getElementById("medsList"),         /* Conteneur médicaments */
  btnAddMed:        document.getElementById("btnAddMed"),        /* Ajouter médicament */

  /* Modal aperçu */
  modalPreview:     document.getElementById("modalPreview"),     /* Fond modal aperçu */
  previewBody:      document.getElementById("previewBody"),      /* Corps aperçu */
  previewClose:     document.getElementById("previewClose"),     /* Fermer aperçu */
  btnClosePreview:  document.getElementById("btnClosePreview"),  /* Fermer aperçu (pied) */
  btnPrintPreview:  document.getElementById("btnPrintPreview"),  /* Imprimer aperçu */
  btnDownloadPDF:   document.getElementById("btnDownloadPDF"),   /* Télécharger PDF */
  btnSendEmail:     document.getElementById("btnSendEmail"),     /* Envoyer email */

  /* Zone toasts */
  toastZone:        document.getElementById("toastZone")         /* Conteneur notifications */
};

/* =============================================
   4. UTILITAIRES
   ============================================= */

/**
 * Formate une date ISO "YYYY-MM-DD" en "DD/MM/YYYY"
 * @param {string} dateStr  - Date au format ISO
 * @returns {string}        - Date formatée ou "—" si vide
 */
function fmtDate(dateStr) {
  if (!dateStr) return "—";                   /* Retourne tiret si vide */
  const [y, m, d] = dateStr.split("-");       /* Décompose la chaîne */
  return `${d}/${m}/${y}`;                    /* Reconstruit au format FR */
}

/**
 * Calcule l'écart en jours entre aujourd'hui et une date cible
 * @param {string} dateStr  - Date cible ISO
 * @returns {number|null}   - Nombre de jours (négatif = passé), null si vide
 */
function joursRestants(dateStr) {
  if (!dateStr) return null;
  const ms = new Date(dateStr) - new Date();  /* Différence en millisecondes */
  return Math.round(ms / 86400000);           /* Conversion ms → jours (86400000 ms/jour) */
}

/**
 * Retourne le texte relatif d'une date passée
 * @param {string} dateStr  - Date ISO
 * @returns {string}        - Ex: "il y a 3j", "Aujourd'hui"
 */
function dateRelative(dateStr) {
  const j = joursRestants(dateStr);
  if (j === null)  return "";
  if (j === 0)     return "Aujourd'hui";
  if (j > 0)       return `dans ${j}j`;
  return `il y a ${Math.abs(j)}j`;
}

/**
 * Retourne les propriétés CSS et texte d'un statut
 * @param {string} statut   - Code statut
 * @returns {object}        - {cls, icon, label}
 */
function getStatutProps(statut) {
  /* Correspondance statut → style et libellé */
  const map = {
    active:     { cls: "badge-active",  icon: "fa-circle-check",        label: "Active"     },
    expiree:    { cls: "badge-expiree", icon: "fa-hourglass-end",        label: "Expirée"    },
    renouvelee: { cls: "badge-renouv",  icon: "fa-rotate",               label: "Renouvelée" },
    annulee:    { cls: "badge-annulee", icon: "fa-circle-xmark",         label: "Annulée"    }
  };
  return map[statut] || { cls: "", icon: "fa-circle", label: statut }; /* Fallback générique */
}

/**
 * Affiche un toast de notification contextuel
 * @param {string} msg    - Message à afficher
 * @param {string} type   - "success" | "error" | "warning" | "info"
 */
function toast(msg, type = "success") {
  /* Icônes associées à chaque type */
  const icons = {
    success: "fa-circle-check",
    error:   "fa-circle-xmark",
    warning: "fa-triangle-exclamation",
    info:    "fa-circle-info"
  };

  /* Création de l'élément toast */
  const el = document.createElement("div");
  el.className = `toast ${type}`;              /* Classes pour le style */
  el.innerHTML = `
    <i class="fa-solid ${icons[type]} toast-ico"></i>
    <span class="toast-msg">${msg}</span>
  `;

  D.toastZone.appendChild(el);                /* Ajout dans la zone de notification */

  /* Suppression automatique après 3.8 secondes */
  setTimeout(() => el.remove(), 3800);
}

/* =============================================
   5. DATE TOPBAR
   ============================================= */

/**
 * Affiche la date actuelle formatée dans la topbar
 */
function updateTopbarDate() {
  const opts = { weekday: "long", day: "numeric", month: "long", year: "numeric" };
  D.topbarDate.textContent = new Date().toLocaleDateString("fr-FR", opts);
}

/* =============================================
   6. STATISTIQUES
   ============================================= */

/**
 * Calcule les statistiques et anime les compteurs
 */
function renderStats() {
  const data = State.all;                              /* Source : toutes les ordonnances */

  /* Calcul des valeurs */
  const total    = data.length;                        /* Total général */
  const active   = data.filter(o => o.statut === "active").length;     /* Actives */
  const expiree  = data.filter(o => o.statut === "expiree").length;    /* Expirées */
  const envoyee  = data.filter(o => o.envoye === true).length;         /* Envoyées */

  /* Animation des compteurs (de 0 à la valeur cible) */
  animNum(D.statTotal,   total);
  animNum(D.statActive,  active);
  animNum(D.statExpiree, expiree);
  animNum(D.statEnvoyee, envoyee);
}

/**
 * Anime un compteur numérique de 0 jusqu'à la valeur cible
 * @param {HTMLElement} el     - Élément à mettre à jour
 * @param {number}      target - Valeur finale
 */
function animNum(el, target) {
  let cur = 0;
  const step = Math.ceil(target / 18);         /* Incrément par étape */
  const t = setInterval(() => {
    cur = Math.min(cur + step, target);        /* Ne dépasse pas la cible */
    el.textContent = cur;
    if (cur >= target) clearInterval(t);       /* Arrête l'animation */
  }, 45);                                      /* 45ms × 18 étapes ≈ 800ms */
}

/* =============================================
   7. POPULATION DES SELECTS
   ============================================= */

/**
 * Remplit le select "filterPatient" avec les patients uniques
 */
function populateFilterPatient() {
  /* Extrait les patients uniques par nom */
  const patients = [...new Set(State.all.map(o => o.patient.nom))].sort();

  D.filterPatient.innerHTML = `<option value="">Tous les patients</option>`;

  /* Crée une option pour chaque patient */
  patients.forEach(nom => {
    const opt = document.createElement("option");
    opt.value       = nom;                    /* Valeur de l'option */
    opt.textContent = nom;                    /* Texte affiché */
    D.filterPatient.appendChild(opt);
  });
}

/**
 * Remplit le select "formPatient" du formulaire de création
 */
function populateFormPatient() {
  const patients = [...new Set(State.all.map(o => o.patient.nom))].sort();

  D.formPatient.innerHTML = `<option value="">— Sélectionner un patient —</option>`;

  patients.forEach(nom => {
    const opt = document.createElement("option");
    opt.value       = nom;
    opt.textContent = nom;
    D.formPatient.appendChild(opt);
  });
}

/* =============================================
   8. FILTRAGE
   ============================================= */

/**
 * Applique tous les filtres actifs sur State.all
 * et stocke le résultat dans State.filtered
 */
function applyFilters() {
  /* Récupération des valeurs des filtres */
  const search  = D.searchInput.value.toLowerCase().trim(); /* Recherche texte */
  const statut  = D.filterStatut.value;                     /* Statut sélectionné */
  const patient = D.filterPatient.value;                    /* Patient sélectionné */
  const periode = parseInt(D.filterPeriode.value) || 0;     /* Jours de période */

  /* Date limite pour le filtre période */
  const limitDate = periode > 0
    ? new Date(Date.now() - periode * 86400000)             /* Soustraction en ms */
    : null;

  /* Application de tous les filtres */
  State.filtered = State.all.filter(o => {

    /* Filtre texte : cherche dans n°, patient, diagnostic, médicaments */
    if (search) {
      const haystack = [
        o.numero,
        o.patient.nom.toLowerCase(),
        o.diagnostic.toLowerCase(),
        ...o.medicaments.map(m => m.nom.toLowerCase())
      ].join(" ");
      if (!haystack.includes(search)) return false;  /* Exclut si pas trouvé */
    }

    /* Filtre statut */
    if (statut && o.statut !== statut) return false;

    /* Filtre patient */
    if (patient && o.patient.nom !== patient) return false;

    /* Filtre période : date de création dans la plage */
    if (limitDate) {
      const dateOrd = new Date(o.date);
      if (dateOrd < limitDate) return false;          /* Exclut si trop ancien */
    }

    return true;  /* Ordonnance passe tous les filtres */
  });

  /* Tri appliqué après filtrage */
  sortFiltered();

  /* Retour à la première page */
  State.page = 1;

  /* Rendu tableau + pagination */
  renderTable();
  renderPagination();

  /* Feedback utilisateur si aucun résultat */
  if (State.filtered.length === 0) {
    toast("Aucun résultat pour ces critères.", "warning");
  }
}

/**
 * Réinitialise tous les filtres et restaure l'affichage complet
 */
function resetFilters() {
  D.searchInput.value  = "";   /* Vide la recherche */
  D.filterStatut.value = "";   /* Réinitialise statut */
  D.filterPatient.value = "";  /* Réinitialise patient */
  D.filterPeriode.value = "";  /* Réinitialise période */

  State.filtered = [...State.all];   /* Restaure toutes les données */
  State.page     = 1;

  renderTable();
  renderPagination();
  toast("Filtres réinitialisés.", "info");
}

/* =============================================
   9. TRI
   ============================================= */

/**
 * Trie State.filtered selon la colonne et direction actives
 */
function sortFiltered() {
  const { sortCol, sortDir } = State;

  State.filtered.sort((a, b) => {
    let va, vb;

    /* Extraction de la valeur selon la colonne */
    switch (sortCol) {
      case "numero":  va = a.numero;        vb = b.numero;        break;
      case "patient": va = a.patient.nom;   vb = b.patient.nom;   break;
      case "date":    va = a.date;          vb = b.date;          break;
      case "statut":  va = a.statut;        vb = b.statut;        break;
      default:        va = a.date;          vb = b.date;
    }

    /* Comparaison selon la direction */
    if (va < vb) return sortDir === "asc" ? -1 :  1;
    if (va > vb) return sortDir === "asc" ?  1 : -1;
    return 0;
  });
}

/**
 * Gère le clic sur un en-tête de colonne triable
 * @param {string} col - Identifiant de colonne
 */
function handleSort(col) {
  /* Inverse la direction si c'est la même colonne */
  if (State.sortCol === col) {
    State.sortDir = State.sortDir === "asc" ? "desc" : "asc";
  } else {
    State.sortCol = col;      /* Nouvelle colonne */
    State.sortDir = "asc";   /* Direction par défaut */
  }

  /* Met à jour les icônes de tri dans les en-têtes */
  D.sortables.forEach(th => {
    const ico = th.querySelector("i");
    if (!ico) return;
    if (th.dataset.col === State.sortCol) {
      ico.className = `fa-solid ${State.sortDir === "asc" ? "fa-sort-up" : "fa-sort-down"}`;
    } else {
      ico.className = "fa-solid fa-sort"; /* Icône neutre */
    }
  });

  /* Applique le tri et réaffiche */
  sortFiltered();
  renderTable();
}

/* =============================================
   10. RENDU DU TABLEAU
   ============================================= */

/**
 * Génère et insère les lignes du tableau pour la page courante
 */
function renderTable() {
  const tbody = D.ordTableBody;
  tbody.innerHTML = "";  /* Vide le tableau */

  /* Calcul des indices de la page courante */
  const start = (State.page - 1) * State.perPage;    /* Index de début */
  const end   = start + State.perPage;                /* Index de fin */
  const items = State.filtered.slice(start, end);     /* Données de la page */

  /* Mise à jour du compteur résultats */
  const n = State.filtered.length;
  D.resultCount.textContent = `${n} ordonnance${n > 1 ? "s" : ""}`;

  /* Aucune donnée : affiche le message vide */
  if (items.length === 0) {
    tbody.innerHTML = `
      <tr class="empty-row">
        <td colspan="8">
          <div class="empty-icon"><i class="fa-solid fa-file-prescription"></i></div>
          <p class="empty-text">Aucune ordonnance trouvée</p>
          <p class="empty-hint">Modifiez vos filtres ou créez une nouvelle ordonnance.</p>
        </td>
      </tr>`;
    return;
  }

  /* Rendu de chaque ligne avec délai d'animation progressif */
  items.forEach((o, idx) => {
    const sp       = getStatutProps(o.statut);   /* Propriétés du badge statut */
    const expHtml  = buildExpCell(o.expiration); /* HTML de la cellule expiration */
    const medsHtml = buildMedsCell(o.medicaments); /* HTML des pilules médicaments */

    const tr = document.createElement("tr");
    tr.style.animationDelay = `${idx * 0.04}s`;  /* Décalage animation entrée */

    tr.innerHTML = `
      <!-- Checkbox de sélection -->
      <td>
        <input type="checkbox" class="row-chk" data-id="${o.id}" />
      </td>

      <!-- Numéro de l'ordonnance -->
      <td>
        <span class="num-badge">
          <i class="fa-solid fa-file-prescription"></i>
          ${o.numero}
        </span>
      </td>

      <!-- Informations patient -->
      <td>
        <div class="patient-cell">
          <img
            src="${o.patient.avatar}"
            alt="${o.patient.nom}"
            class="patient-thumb"
          />
          <div>
            <p class="patient-name">${o.patient.nom}</p>
            <p class="patient-age">${o.patient.age} ans</p>
          </div>
        </div>
      </td>

      <!-- Médicaments (pilules) -->
      <td>
        <div class="meds-cell">${medsHtml}</div>
      </td>

      <!-- Date de création -->
      <td>
        <p class="date-cell">${fmtDate(o.date)}</p>
        <p class="date-sub">${dateRelative(o.date)}</p>
      </td>

      <!-- Date d'expiration -->
      <td>${expHtml}</td>

      <!-- Badge statut -->
      <td>
        <span class="badge ${sp.cls}">
          <i class="fa-solid ${sp.icon}"></i> ${sp.label}
        </span>
      </td>

      <!-- Boutons d'action -->
      <td>
        <div class="act-cell">
          <!-- Voir l'aperçu -->
          <button
            class="act-btn act-btn-view"
            title="Aperçu"
            onclick="openPreview(${o.id})"
          ><i class="fa-solid fa-eye"></i></button>

          <!-- Modifier -->
          <button
            class="act-btn act-btn-edit"
            title="Modifier"
            onclick="openEdit(${o.id})"
          ><i class="fa-solid fa-pen"></i></button>

          <!-- Télécharger PDF -->
          <button
            class="act-btn act-btn-pdf"
            title="PDF"
            onclick="downloadPDF(${o.id})"
          ><i class="fa-solid fa-file-pdf"></i></button>

          <!-- Supprimer -->
          <button
            class="act-btn act-btn-delete"
            title="Supprimer"
            onclick="deleteOrd(${o.id})"
          ><i class="fa-solid fa-trash"></i></button>
        </div>
      </td>
    `;

    tbody.appendChild(tr);  /* Ajout de la ligne au tableau */
  });

  /* Synchronise la checkbox "tout sélectionner" */
  D.checkAll.checked = false;
}

/**
 * Génère le HTML des pilules médicaments pour le tableau
 * @param {Array}  meds  - Liste des médicaments
 * @returns {string}     - HTML des pilules
 */
function buildMedsCell(meds) {
  const MAX_SHOW = 2;                         /* Nombre max de pilules affichées */
  let html = "";

  /* Affiche les N premiers médicaments */
  meds.slice(0, MAX_SHOW).forEach(m => {
    html += `<span class="med-pill">${m.nom}</span>`;
  });

  /* Si d'autres médicaments existent, affiche "+N" */
  if (meds.length > MAX_SHOW) {
    html += `<span class="med-more">+${meds.length - MAX_SHOW}</span>`;
  }

  return html;
}

/**
 * Génère le HTML de la cellule d'expiration selon l'état
 * @param {string} expDate  - Date d'expiration ISO
 * @returns {string}        - HTML stylisé
 */
function buildExpCell(expDate) {
  if (!expDate) {
    return `<span class="exp-cell exp-none">Aucune</span>`;
  }

  const jours = joursRestants(expDate);  /* Jours restants */

  /* Expirée */
  if (jours < 0) {
    return `<p class="exp-cell exp-past">
      <i class="fa-solid fa-circle-exclamation"></i> ${fmtDate(expDate)}
    </p>
    <p class="date-sub" style="color:var(--c-danger)">Expirée depuis ${Math.abs(jours)}j</p>`;
  }

  /* Expire dans moins de 14 jours */
  if (jours <= 14) {
    return `<p class="exp-cell exp-soon">
      <i class="fa-solid fa-triangle-exclamation"></i> ${fmtDate(expDate)}
    </p>
    <p class="date-sub" style="color:var(--c-warning)">Dans ${jours} jours</p>`;
  }

  /* Date valide */
  return `<p class="exp-cell exp-ok">${fmtDate(expDate)}</p>
  <p class="date-sub">Dans ${jours}j</p>`;
}

/* =============================================
   11. PAGINATION
   ============================================= */

/**
 * Génère les contrôles de pagination
 */
function renderPagination() {
  const total  = State.filtered.length;                /* Total items filtrés */
  const pages  = Math.ceil(total / State.perPage);     /* Nombre total de pages */
  const cur    = State.page;

  /* Texte informatif */
  const from = (cur - 1) * State.perPage + 1;
  const to   = Math.min(cur * State.perPage, total);
  D.paginInfo.textContent = total > 0
    ? `Affichage ${from}–${to} sur ${total}`
    : "Aucun résultat";

  D.paginBtns.innerHTML = "";               /* Vide les boutons existants */

  if (pages <= 1) return;                   /* Pas de pagination si une seule page */

  /* Bouton précédent */
  const prev = makePgBtn(`<i class="fa-solid fa-chevron-left"></i>`, cur === 1, () => goPage(cur - 1));
  D.paginBtns.appendChild(prev);

  /* Boutons numérotés */
  for (let p = 1; p <= pages; p++) {
    /* Affiche uniquement : première, dernière, pages proches de la courante */
    if (p === 1 || p === pages || (p >= cur - 1 && p <= cur + 1)) {
      const btn = makePgBtn(p, false, () => goPage(p));
      if (p === cur) btn.classList.add("active"); /* Marque la page active */
      D.paginBtns.appendChild(btn);
    } else if (p === cur - 2 || p === cur + 2) {
      /* Ellipsis (...) pour les pages non affichées */
      const ell = document.createElement("span");
      ell.textContent = "…";
      ell.style.cssText = "padding:0 4px;color:var(--c-muted);font-size:13px;";
      D.paginBtns.appendChild(ell);
    }
  }

  /* Bouton suivant */
  const next = makePgBtn(`<i class="fa-solid fa-chevron-right"></i>`, cur === pages, () => goPage(cur + 1));
  D.paginBtns.appendChild(next);
}

/**
 * Crée un bouton de page
 * @param {string|number} label    - Texte ou HTML du bouton
 * @param {boolean}       disabled - État désactivé
 * @param {Function}      onClick  - Handler de clic
 * @returns {HTMLButtonElement}
 */
function makePgBtn(label, disabled, onClick) {
  const btn = document.createElement("button");
  btn.className = "pg-btn";
  btn.innerHTML = label;
  btn.disabled  = disabled;
  if (!disabled) btn.addEventListener("click", onClick);
  return btn;
}

/**
 * Navigue vers une page spécifique
 * @param {number} p - Numéro de page cible
 */
function goPage(p) {
  const pages = Math.ceil(State.filtered.length / State.perPage);
  if (p < 1 || p > pages) return;      /* Vérifie les bornes */
  State.page = p;
  renderTable();
  renderPagination();
  /* Scroll vers le haut du tableau */
  document.querySelector(".table-card").scrollIntoView({ behavior: "smooth", block: "start" });
}

/* =============================================
   12. MODAL FORMULAIRE — OUVERTURE
   ============================================= */

/**
 * Ouvre la modal en mode "Nouvelle ordonnance"
 * Réinitialise tous les champs
 */
function openAdd() {
  State.editId = null;                                /* Mode ajout */
  D.modalFormTitle.textContent = "Nouvelle ordonnance";
  resetForm();                                        /* Vide le formulaire */
  populateFormPatient();                              /* Charge la liste patients */
  D.formDate.valueAsDate = new Date();                /* Date = aujourd'hui par défaut */
  openModal(D.modalForm);
}

/**
 * Ouvre la modal en mode "Modifier" avec les données existantes
 * @param {number} id - ID de l'ordonnance à modifier
 */
function openEdit(id) {
  const o = State.all.find(x => x.id === id);        /* Cherche l'ordonnance */
  if (!o) return;

  State.editId = id;                                  /* Stocke l'ID en édition */
  D.modalFormTitle.textContent = `Modifier — ${o.numero}`;

  populateFormPatient();

  /* Pré-remplit les champs avec les données existantes */
  D.formId.value           = o.id;
  D.formPatient.value      = o.patient.nom;
  D.formDiagnostic.value   = o.diagnostic;
  D.formDate.value         = o.date;
  D.formExpiration.value   = o.expiration || "";
  D.formInstructions.value = o.instructions || "";
  D.formStatut.value       = o.statut;
  D.formEnvoyer.checked    = o.envoye;

  /* Reconstruit les lignes de médicaments */
  buildMedRows(o.medicaments);

  openModal(D.modalForm);
}

/**
 * Reconstruit les lignes de médicaments dans le formulaire
 * @param {Array} meds - Tableau de médicaments
 */
function buildMedRows(meds) {
  D.medsList.innerHTML = "";   /* Vide la liste actuelle */
  State.medCount = 0;          /* Réinitialise le compteur */

  /* Crée une ligne pour chaque médicament */
  meds.forEach(m => addMedRow(m));
}

/* =============================================
   13. LIGNES DE MÉDICAMENTS (formulaire dynamique)
   ============================================= */

/**
 * Ajoute une nouvelle ligne médicament dans le formulaire
 * @param {object|null} data - Données à pré-remplir (null = vide)
 */
function addMedRow(data = null) {
  State.medCount++;                      /* Incrémente le compteur de lignes */
  const idx = State.medCount;            /* Index de cette ligne */

  /* Création de la div de la ligne */
  const row = document.createElement("div");
  row.className    = "med-row";
  row.dataset.index = idx;

  row.innerHTML = `
    <!-- Numéro de la ligne -->
    <span class="med-num">${idx}</span>

    <!-- Nom du médicament -->
    <input
      type="text"
      class="form-ctrl med-name"
      placeholder="Nom du médicament"
      value="${data ? data.nom : ""}"
    />

    <!-- Dosage -->
    <input
      type="text"
      class="form-ctrl med-dose"
      placeholder="Dosage"
      value="${data ? data.dose : ""}"
    />

    <!-- Fréquence de prise -->
    <select class="form-ctrl med-freq">
      <option value="">Fréquence</option>
      <option value="1×/jour"  ${data?.freq === "1×/jour"   ? "selected" : ""}>1×/jour</option>
      <option value="2×/jour"  ${data?.freq === "2×/jour"   ? "selected" : ""}>2×/jour</option>
      <option value="3×/jour"  ${data?.freq === "3×/jour"   ? "selected" : ""}>3×/jour</option>
      <option value="Au besoin"${data?.freq === "Au besoin"  ? "selected" : ""}>Au besoin</option>
      <option value="Le matin" ${data?.freq === "Le matin"   ? "selected" : ""}>Le matin</option>
      <option value="Le soir"  ${data?.freq === "Le soir"    ? "selected" : ""}>Le soir</option>
    </select>

    <!-- Durée du traitement -->
    <input
      type="text"
      class="form-ctrl med-duree"
      placeholder="Durée"
      value="${data ? data.duree : ""}"
    />

    <!-- Bouton supprimer cette ligne -->
    <button
      class="btn-del-med"
      type="button"
      title="Supprimer ce médicament"
      style="${idx === 1 ? "visibility:hidden" : ""}"
    >
      <i class="fa-solid fa-trash"></i>
    </button>
  `;

  /* Attache l'événement de suppression sur le bouton trash */
  row.querySelector(".btn-del-med").addEventListener("click", () => {
    row.remove();              /* Supprime la ligne du DOM */
    renumMedRows();            /* Renumérote les lignes restantes */
  });

  D.medsList.appendChild(row);   /* Ajoute la ligne dans le conteneur */
}

/**
 * Renumérote les lignes médicament après une suppression
 */
function renumMedRows() {
  /* Sélectionne toutes les lignes restantes */
  D.medsList.querySelectorAll(".med-row").forEach((row, i) => {
    const num = row.querySelector(".med-num");
    if (num) num.textContent = i + 1;   /* Remet le bon numéro */
    row.dataset.index = i + 1;          /* Met à jour l'index */

    /* Cache le bouton delete sur la première ligne */
    const delBtn = row.querySelector(".btn-del-med");
    if (delBtn) delBtn.style.visibility = i === 0 ? "hidden" : "visible";
  });
  State.medCount = D.medsList.querySelectorAll(".med-row").length; /* Recalcule */
}

/* =============================================
   14. LECTURE DU FORMULAIRE
   ============================================= */

/**
 * Lit et valide les données du formulaire
 * @returns {object|null} - Objet ordonnance ou null si invalide
 */
function readForm() {
  /* Champs principaux */
  const patientNom    = D.formPatient.value.trim();
  const diagnostic    = D.formDiagnostic.value.trim();
  const date          = D.formDate.value;
  const expiration    = D.formExpiration.value;
  const instructions  = D.formInstructions.value.trim();
  const statut        = D.formStatut.value;
  const envoye        = D.formEnvoyer.checked;

  /* Validation des champs requis */
  if (!patientNom) { toast("Veuillez sélectionner un patient.", "error"); return null; }
  if (!date)       { toast("La date est obligatoire.", "error");          return null; }
  if (!statut)     { toast("Veuillez choisir un statut.", "error");       return null; }

  /* Lecture des lignes de médicaments */
  const medicaments = [];
  D.medsList.querySelectorAll(".med-row").forEach(row => {
    const nom   = row.querySelector(".med-name")?.value.trim();   /* Nom */
    const dose  = row.querySelector(".med-dose")?.value.trim();   /* Dosage */
    const freq  = row.querySelector(".med-freq")?.value;          /* Fréquence */
    const duree = row.querySelector(".med-duree")?.value.trim();  /* Durée */

    if (nom) {
      medicaments.push({ nom, dose: dose || "—", freq: freq || "—", duree: duree || "—" });
    }
  });

  /* Au moins un médicament requis */
  if (medicaments.length === 0) {
    toast("Ajoutez au moins un médicament.", "error");
    return null;
  }

  /* Recherche les infos patient dans les données existantes */
  const patientExist = State.all.find(o => o.patient.nom === patientNom);
  const patient = patientExist
    ? patientExist.patient                            /* Réutilise les infos existantes */
    : {
        nom:    patientNom,
        age:    0,
        avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(patientNom)}&background=4A7FA7&color=fff&size=60`
      };

  /* Retourne l'objet ordonnance complet */
  return { patient, diagnostic, date, expiration, instructions, statut, envoye, medicaments };
}

/* =============================================
   15. SAUVEGARDE
   ============================================= */

/**
 * Enregistre l'ordonnance (ajout ou modification)
 */
function saveOrdonnance() {
  const data = readForm();     /* Lecture + validation */
  if (!data) return;            /* Annule si invalide */

  if (State.editId !== null) {
    /* ── MODE MODIFICATION ── */
    const idx = State.all.findIndex(o => o.id === State.editId);
    if (idx !== -1) {
      /* Met à jour l'ordonnance existante */
      State.all[idx] = { ...State.all[idx], ...data };
    }
    toast("Ordonnance mise à jour avec succès !", "success");
    /* Appel API réel : fetch('/api/ordonnances/update', { method: 'POST', body: ... }) */

  } else {
    /* ── MODE AJOUT ── */
    const newId  = Math.max(0, ...State.all.map(o => o.id)) + 1; /* Nouvel ID */
    const numero = `ORD-2025-${String(nextOrdNum++).padStart(3, "0")}`; /* Nouveau numéro formaté */

    State.all.unshift({ id: newId, numero, ...data }); /* Ajoute en tête de liste */

    /* Si envoi activé : notification */
    if (data.envoye) toast("Ordonnance envoyée au patient par email !", "info");

    toast("Ordonnance créée avec succès !", "success");
    /* Appel API réel : fetch('/api/ordonnances/add', { method: 'POST', body: ... }) */
  }

  /* Mise à jour de l'état et réaffichage */
  State.filtered = [...State.all];
  State.page     = 1;

  closeModal(D.modalForm);
  renderStats();
  populateFilterPatient();
  renderTable();
  renderPagination();
}

/* =============================================
   16. SUPPRESSION
   ============================================= */

/**
 * Supprime une ordonnance après confirmation utilisateur
 * @param {number} id - ID à supprimer
 */
function deleteOrd(id) {
  const o = State.all.find(x => x.id === id);
  if (!o) return;

  /* Demande confirmation avant suppression */
  if (!confirm(`Supprimer l'ordonnance ${o.numero} ? Cette action est irréversible.`)) return;

  /* Suppression dans les deux tableaux */
  State.all      = State.all.filter(x => x.id !== id);
  State.filtered = State.filtered.filter(x => x.id !== id);

  /* Ajuste la page si la dernière est vide */
  const pages = Math.ceil(State.filtered.length / State.perPage);
  if (State.page > pages) State.page = Math.max(1, pages);

  /* Rafraîchissement */
  renderStats();
  renderTable();
  renderPagination();
  toast(`Ordonnance ${o.numero} supprimée.`, "warning");
  /* Appel API réel : fetch('/api/ordonnances/delete', { method: 'POST', body: JSON.stringify({id}) }) */
}

/* =============================================
   17. APERÇU ORDONNANCE
   ============================================= */

/**
 * Ouvre la modal d'aperçu pour une ordonnance donnée
 * @param {number} id - ID de l'ordonnance à afficher
 */
function openPreview(id) {
  const o = State.all.find(x => x.id === id);
  if (!o) return;

  State.previewId = id;   /* Stocke l'ID pour les actions (imprimer, PDF, email) */

  /* Construction du document ordonnance stylisé */
  D.previewBody.innerHTML = buildOrdDoc(o);

  openModal(D.modalPreview);
}

/**
 * Génère le HTML complet du document ordonnance
 * @param {object} o - Objet ordonnance
 * @returns {string} - HTML du document
 */
function buildOrdDoc(o) {
  /* Génère les lignes médicaments du document */
  const medsHtml = o.medicaments.map((m, i) => `
    <div class="ord-med-item">
      <!-- Numéro circulaire -->
      <div class="ord-med-number">${i + 1}</div>
      <div>
        <!-- Nom et dosage -->
        <p class="ord-med-name">${m.nom} — ${m.dose}</p>
        <!-- Fréquence et durée -->
        <p class="ord-med-detail">
          <i class="fa-solid fa-clock"></i> ${m.freq}
          &nbsp;·&nbsp;
          <i class="fa-solid fa-calendar"></i> ${m.duree}
        </p>
      </div>
    </div>
  `).join("");

  /* Bloc instructions (si présentes) */
  const instrHtml = o.instructions ? `
    <div class="ord-instructions">
      <p class="ord-instr-label"><i class="fa-solid fa-note-sticky"></i> Instructions</p>
      <p class="ord-instr-text">${o.instructions}</p>
    </div>` : "";

  /* HTML complet du document */
  return `
    <div class="ord-doc">

      <!-- En-tête cabinet médical -->
      <div class="ord-doc-header">
        <div>
          <p class="ord-cabinet-name">Dr. Jean Martin</p>
          <p class="ord-cabinet-info">
            Médecin Généraliste<br>
            123 Rue de la Santé, Paris<br>
            Tél : 01 23 45 67 89<br>
            N° RPPS : 12345678901
          </p>
        </div>
        <!-- Numéro et date -->
        <div class="ord-doc-num">
          <p class="ord-num-label">Ordonnance</p>
          <p class="ord-num-val">${o.numero}</p>
          <p class="ord-date-val">
            <i class="fa-solid fa-calendar"></i> ${fmtDate(o.date)}
          </p>
        </div>
      </div>

      <!-- Bloc patient -->
      <div class="ord-patient-block">
        <p class="ord-patient-label">Patient</p>
        <p class="ord-patient-name">${o.patient.nom}</p>
        <p class="ord-patient-info">${o.patient.age} ans</p>
      </div>

      <!-- Diagnostic -->
      ${o.diagnostic ? `<p class="ord-diag"><i class="fa-solid fa-stethoscope"></i> ${o.diagnostic}</p>` : ""}

      <!-- Titre Rp -->
      <p class="ord-rx-title">Rp/</p>

      <!-- Liste des médicaments -->
      <div class="ord-meds-list">${medsHtml}</div>

      <!-- Instructions -->
      ${instrHtml}

      <!-- Signature -->
      <div class="ord-signature">
        <div class="ord-sign-block">
          <p style="font-size:11px;color:var(--c-muted);">Signature du médecin</p>
          <div class="ord-sign-line"></div>
          <p class="ord-sign-name">Dr. Jean Martin</p>
          <p class="ord-sign-title">Médecin Généraliste</p>
        </div>
      </div>

    </div>
  `;
}

/* =============================================
   18. ACTIONS APERÇU
   ============================================= */

/**
 * Lance l'impression de l'aperçu de l'ordonnance
 */
function printPreview() {
  window.print();   /* Déclenche la boîte de dialogue d'impression du navigateur */
}

/**
 * Simule le téléchargement PDF de l'ordonnance
 * (En production : appel à un générateur PDF côté serveur)
 * @param {number|null} id - ID de l'ordonnance (null = celle en aperçu)
 */
function downloadPDF(id = null) {
  const targetId = id || State.previewId;
  const o = State.all.find(x => x.id === targetId);
  if (!o) return;

  /* Simulation : en production appeler /api/ordonnances/pdf?id=X */
  toast(`PDF de ${o.numero} en cours de génération...`, "info");

  /* Simulation du téléchargement après 1.5s */
  setTimeout(() => {
    toast(`PDF ${o.numero} prêt au téléchargement.`, "success");
  }, 1500);
}

/**
 * Simule l'envoi de l'ordonnance par email au patient
 */
function sendEmailPreview() {
  const o = State.all.find(x => x.id === State.previewId);
  if (!o) return;

  /* Simulation envoi email */
  toast(`Envoi de ${o.numero} à ${o.patient.nom}...`, "info");

  setTimeout(() => {
    /* Marque l'ordonnance comme envoyée */
    const idx = State.all.findIndex(x => x.id === State.previewId);
    if (idx !== -1) State.all[idx].envoye = true;

    renderStats();   /* Met à jour les stats */
    toast(`Ordonnance envoyée avec succès !`, "success");
    /* En production : fetch('/api/ordonnances/send', { method: 'POST', body: JSON.stringify({id}) }) */
  }, 1500);
}

/* =============================================
   19. EXPORT CSV
   ============================================= */

/**
 * Exporte les données filtrées au format CSV (encodage UTF-8 BOM)
 */
function exportCSV() {
  /* Définition des colonnes du CSV */
  const headers = ["N° Ordonnance", "Patient", "Âge", "Diagnostic", "Médicaments", "Date", "Expiration", "Statut", "Envoyée"];

  /* Construction des lignes */
  const rows = State.filtered.map(o => [
    o.numero,
    o.patient.nom,
    o.patient.age,
    o.diagnostic,
    o.medicaments.map(m => m.nom).join(" / "),  /* Médicaments séparés par " / " */
    fmtDate(o.date),
    fmtDate(o.expiration),
    getStatutProps(o.statut).label,
    o.envoye ? "Oui" : "Non"
  ]);

  /* Assemblage CSV */
  const csv = [headers, ...rows]
    .map(r => r.map(c => `"${c}"`).join(";"))   /* Valeurs entre guillemets, séparateur ";" */
    .join("\n");

  /* Création et déclenchement du téléchargement */
  const blob = new Blob(["\uFEFF" + csv], { type: "text/csv;charset=utf-8;" }); /* BOM UTF-8 */
  const url  = URL.createObjectURL(blob);
  const a    = document.createElement("a");
  a.href     = url;
  a.download = `ordonnances_${new Date().toISOString().slice(0, 10)}.csv`; /* Nom fichier avec date */
  document.body.appendChild(a);
  a.click();                              /* Déclenche le téléchargement */
  a.remove();
  URL.revokeObjectURL(url);               /* Libère la mémoire */

  toast("Export CSV téléchargé !", "success");
}

/* =============================================
   20. RÉINITIALISATION DU FORMULAIRE
   ============================================= */

/**
 * Réinitialise tous les champs du formulaire modal
 */
function resetForm() {
  D.formId.value           = "";    /* Vide l'ID */
  D.formPatient.value      = "";    /* Vide le patient */
  D.formDiagnostic.value   = "";    /* Vide le diagnostic */
  D.formDate.value         = "";    /* Vide la date */
  D.formExpiration.value   = "";    /* Vide l'expiration */
  D.formInstructions.value = "";    /* Vide les instructions */
  D.formStatut.value       = "active"; /* Statut par défaut */
  D.formEnvoyer.checked    = false;  /* Toggle email désactivé */

  /* Réinitialise la liste des médicaments à une seule ligne vide */
  D.medsList.innerHTML = "";
  State.medCount = 0;
  addMedRow(null);  /* Ajoute une ligne vide */
}

/* =============================================
   21. OUVERTURE / FERMETURE DES MODALS
   ============================================= */

/**
 * Ouvre une modal (affiche le backdrop)
 * @param {HTMLElement} modal - Élément .modal-backdrop
 */
function openModal(modal) {
  modal.classList.add("open");            /* Rend la modal visible */
  modal.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden"; /* Bloque le scroll de la page */
}

/**
 * Ferme une modal
 * @param {HTMLElement} modal - Élément .modal-backdrop
 */
function closeModal(modal) {
  modal.classList.remove("open");          /* Cache la modal */
  modal.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";       /* Rétablit le scroll */
}

/* =============================================
   22. SIDEBAR MOBILE
   ============================================= */

/** Ouvre la sidebar sur mobile */
function openSidebar() {
  D.sidebar.classList.add("open");
  D.sidebarOverlay.classList.add("show");
}

/** Ferme la sidebar sur mobile */
function closeSidebar() {
  D.sidebar.classList.remove("open");
  D.sidebarOverlay.classList.remove("show");
}

/* =============================================
   23. SÉLECTION TOUT / DÉCOCHER
   ============================================= */

/**
 * Coche ou décoche toutes les lignes du tableau
 */
function toggleAll() {
  const state = D.checkAll.checked;
  document.querySelectorAll(".row-chk").forEach(cb => { cb.checked = state; });
}

/* =============================================
   24. ATTACHEMENT DES ÉVÉNEMENTS
   ============================================= */

/**
 * Attache tous les listeners aux éléments du DOM
 */
function bindEvents() {

  /* ── Sidebar mobile ── */
  D.hamburger.addEventListener("click",      openSidebar);    /* Ouvrir */
  D.sidebarClose.addEventListener("click",   closeSidebar);   /* Fermer via bouton */
  D.sidebarOverlay.addEventListener("click", closeSidebar);   /* Fermer via fond */

  /* ── Filtres ── */
  D.btnApply.addEventListener("click", applyFilters);         /* Appliquer */
  D.btnReset.addEventListener("click", resetFilters);         /* Réinitialiser */

  /* Recherche en temps réel avec debounce (délai 320ms) */
  D.searchInput.addEventListener("input", () => {
    clearTimeout(D._debounce);                                 /* Annule le timer précédent */
    D._debounce = setTimeout(applyFilters, 320);              /* Nouveau timer */
  });

  /* ── Tri des colonnes ── */
  D.sortables.forEach(th => {
    th.addEventListener("click", () => handleSort(th.dataset.col));
  });

  /* ── Checkbox tout sélectionner ── */
  D.checkAll.addEventListener("change", toggleAll);

  /* ── Export & impression ── */
  D.btnExport.addEventListener("click", exportCSV);
  D.btnPrint.addEventListener("click",  () => window.print());

  /* ── Bouton nouvelle ordonnance ── */
  D.btnNewOrdonnance.addEventListener("click", openAdd);

  /* ── Modal formulaire ── */
  D.modalFormClose.addEventListener("click",  () => closeModal(D.modalForm));
  D.btnCancelForm.addEventListener("click",   () => closeModal(D.modalForm));
  D.btnSave.addEventListener("click",         saveOrdonnance);

  /* Fermeture par clic sur le fond */
  D.modalForm.addEventListener("click", e => {
    if (e.target === D.modalForm) closeModal(D.modalForm);
  });

  /* Bouton aperçu depuis le formulaire (avant enregistrement) */
  D.btnPreview.addEventListener("click", () => {
    const data = readForm();          /* Lit les données actuelles */
    if (!data) return;

    /* Crée un objet temporaire pour l'aperçu */
    const temp = {
      id:       -1,
      numero:   State.editId ? State.all.find(o => o.id === State.editId)?.numero : "ORD-APERÇU",
      ...data
    };
    State.previewId = -1;

    /* Affiche l'aperçu avec les données du formulaire */
    D.previewBody.innerHTML = buildOrdDoc(temp);
    openModal(D.modalPreview);
  });

  /* Bouton ajouter un médicament */
  D.btnAddMed.addEventListener("click", () => addMedRow(null));

  /* ── Modal aperçu ── */
  D.previewClose.addEventListener("click",    () => closeModal(D.modalPreview));
  D.btnClosePreview.addEventListener("click", () => closeModal(D.modalPreview));
  D.btnPrintPreview.addEventListener("click", printPreview);
  D.btnDownloadPDF.addEventListener("click",  () => downloadPDF());
  D.btnSendEmail.addEventListener("click",    sendEmailPreview);

  /* Fermeture aperçu par clic fond */
  D.modalPreview.addEventListener("click", e => {
    if (e.target === D.modalPreview) closeModal(D.modalPreview);
  });

  /* ── Touche Échap ferme toutes les modals ── */
  document.addEventListener("keydown", e => {
    if (e.key === "Escape") {
      closeModal(D.modalForm);       /* Ferme le formulaire */
      closeModal(D.modalPreview);    /* Ferme l'aperçu */
      closeSidebar();                /* Ferme la sidebar mobile */
    }
  });
}

/* =============================================
   25. INITIALISATION
   ============================================= */

/**
 * Point d'entrée — initialise tous les composants de la page
 */
function init() {
  updateTopbarDate();           /* Affiche la date courante */
  sortFiltered();               /* Tri initial par date décroissante */
  renderStats();                /* Calcule et affiche les statistiques */
  populateFilterPatient();      /* Charge le filtre patients */
  renderTable();                /* Affiche le tableau */
  renderPagination();           /* Affiche la pagination */
  bindEvents();                 /* Attache tous les événements */
}

/* Lance l'initialisation quand le DOM est prêt */
document.addEventListener("DOMContentLoaded", init);