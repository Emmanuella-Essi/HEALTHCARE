/* =====================================================
   VACCINS.JS — LOGIQUE JAVASCRIPT PAGE VACCINS
   Dashboard Médecin — Module Vaccinations
   ===================================================== */

/* =============================================
   1. DONNÉES SIMULÉES (remplacées par PHP/API)
   ============================================= */

/* Tableau de données vaccins simulées */
const VACCINS_DATA = [
  {
    id: 1,
    patient: { nom: "Marie Dupont",   age: 34, avatar: "https://ui-avatars.com/api/?name=Marie+Dupont&background=B3CFE5&color=1A3D63&size=60" },
    vaccin:  "COVID-19",
    dose:    "2ème dose",
    date:    "2024-11-15",
    rappel:  "2025-11-15",
    statut:  "fait",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2024-1115",
    notes:   "Aucune réaction observée. Bonne tolérance."
  },
  {
    id: 2,
    patient: { nom: "Paul Bernard",   age: 62, avatar: "https://ui-avatars.com/api/?name=Paul+Bernard&background=4A7FA7&color=fff&size=60" },
    vaccin:  "Grippe saisonnière",
    dose:    "Rappel",
    date:    "2024-10-03",
    rappel:  "2025-10-03",
    statut:  "fait",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2024-1003",
    notes:   "Patient légèrement fiévreux 24h après."
  },
  {
    id: 3,
    patient: { nom: "Sophie Lambert", age: 8,  avatar: "https://ui-avatars.com/api/?name=Sophie+Lambert&background=0A8C74&color=fff&size=60" },
    vaccin:  "ROR (Rougeole)",
    dose:    "1ère dose",
    date:    "2025-01-20",
    rappel:  "2025-07-20",
    statut:  "planifie",
    praticien: "Dr. Jean Martin",
    lot:     "",
    notes:   ""
  },
  {
    id: 4,
    patient: { nom: "Ahmed Karim",    age: 45, avatar: "https://ui-avatars.com/api/?name=Ahmed+Karim&background=1A3D63&color=fff&size=60" },
    vaccin:  "Tétanos",
    dose:    "Rappel",
    date:    "2023-06-10",
    rappel:  "2024-06-10",
    statut:  "retard",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2023-0610",
    notes:   "Rappel en retard de 8 mois. Contacter le patient."
  },
  {
    id: 5,
    patient: { nom: "Claire Moreau",  age: 29, avatar: "https://ui-avatars.com/api/?name=Claire+Moreau&background=047857&color=fff&size=60" },
    vaccin:  "Hépatite B",
    dose:    "3ème dose",
    date:    "2025-02-05",
    rappel:  "",
    statut:  "fait",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2025-0205",
    notes:   "Schéma vaccinal complet."
  },
  {
    id: 6,
    patient: { nom: "Lucas Petit",    age: 5,  avatar: "https://ui-avatars.com/api/?name=Lucas+Petit&background=7C3AED&color=fff&size=60" },
    vaccin:  "Pneumocoque",
    dose:    "2ème dose",
    date:    "2025-03-12",
    rappel:  "2025-09-12",
    statut:  "rappel",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2025-0312",
    notes:   "Rappel dans 6 mois, noter dans agenda."
  },
  {
    id: 7,
    patient: { nom: "Fatou Diallo",   age: 38, avatar: "https://ui-avatars.com/api/?name=Fatou+Diallo&background=D97706&color=fff&size=60" },
    vaccin:  "COVID-19",
    dose:    "Rappel",
    date:    "2023-12-20",
    rappel:  "2024-12-20",
    statut:  "retard",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2023-1220",
    notes:   "Rappel annuel non effectué."
  },
  {
    id: 8,
    patient: { nom: "Marc Lefevre",   age: 71, avatar: "https://ui-avatars.com/api/?name=Marc+Lefevre&background=DC2626&color=fff&size=60" },
    vaccin:  "Grippe saisonnière",
    dose:    "Rappel",
    date:    "2025-04-01",
    rappel:  "2026-04-01",
    statut:  "planifie",
    praticien: "Dr. Jean Martin",
    lot:     "",
    notes:   "Patient âgé, priorité haute."
  },
  {
    id: 9,
    patient: { nom: "Isabelle Roux",  age: 52, avatar: "https://ui-avatars.com/api/?name=Isabelle+Roux&background=2563EB&color=fff&size=60" },
    vaccin:  "Varicelle",
    dose:    "1ère dose",
    date:    "2023-08-14",
    rappel:  "2024-08-14",
    statut:  "retard",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2023-0814",
    notes:   "Rappel dépassé, relance nécessaire."
  },
  {
    id: 10,
    patient: { nom: "Thomas Garcia",  age: 16, avatar: "https://ui-avatars.com/api/?name=Thomas+Garcia&background=0A8C74&color=fff&size=60" },
    vaccin:  "Méningocoque",
    dose:    "1ère dose",
    date:    "2025-01-08",
    rappel:  "2026-01-08",
    statut:  "fait",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2025-0108",
    notes:   ""
  },
  {
    id: 11,
    patient: { nom: "Nadia Ben Ali",  age: 41, avatar: "https://ui-avatars.com/api/?name=Nadia+Ben&background=4A7FA7&color=fff&size=60" },
    vaccin:  "Tétanos",
    dose:    "1ère dose",
    date:    "2025-02-18",
    rappel:  "2025-08-18",
    statut:  "rappel",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2025-0218",
    notes:   "Rappel à 6 mois obligatoire."
  },
  {
    id: 12,
    patient: { nom: "René Blanc",     age: 66, avatar: "https://ui-avatars.com/api/?name=René+Blanc&background=1A3D63&color=fff&size=60" },
    vaccin:  "Pneumocoque",
    dose:    "Rappel",
    date:    "2023-03-30",
    rappel:  "2024-03-30",
    statut:  "retard",
    praticien: "Dr. Jean Martin",
    lot:     "LOT-2023-0330",
    notes:   "Patient diabétique, rappel prioritaire."
  }
];

/* =============================================
   2. ÉTAT DE L'APPLICATION
   ============================================= */

/* Objet d'état global de la page */
const State = {
  allData:      [...VACCINS_DATA],    /* Toutes les données */
  filtered:     [...VACCINS_DATA],    /* Données après filtrage */
  currentPage:  1,                    /* Page courante */
  perPage:      8,                    /* Lignes par page */
  sortCol:      "date",               /* Colonne de tri */
  sortDir:      "desc",               /* Direction du tri */
  editId:       null,                 /* ID en cours d'édition */
  detailId:     null                  /* ID affiché en détail */
};

/* =============================================
   3. SÉLECTEURS DOM
   ============================================= */

/* Récupération des éléments HTML principaux */
const Dom = {
  sidebar:         document.getElementById("sidebar"),           /* Barre latérale */
  sidebarClose:    document.getElementById("sidebarClose"),      /* Bouton fermer sidebar */
  sidebarOverlay:  document.getElementById("sidebarOverlay"),    /* Overlay mobile */
  hamburger:       document.getElementById("hamburger"),         /* Bouton menu hamburger */
  topbarDate:      document.getElementById("topbarDate"),        /* Date dans topbar */
  notifCount:      document.getElementById("notifCount"),        /* Badge notifications */

  /* Statistiques */
  statTotal:       document.getElementById("statTotal"),         /* Total vaccinations */
  statFait:        document.getElementById("statFait"),          /* Nb effectués */
  statRetard:      document.getElementById("statRetard"),        /* Nb en retard */
  statRappels:     document.getElementById("statRappels"),       /* Nb rappels */

  /* Filtres */
  searchPatient:   document.getElementById("searchPatient"),     /* Champ recherche */
  filterStatut:    document.getElementById("filterStatut"),      /* Filtre statut */
  filterType:      document.getElementById("filterType"),        /* Filtre type vaccin */
  filterAge:       document.getElementById("filterAge"),         /* Filtre âge */
  filterDateDeb:   document.getElementById("filterDateDeb"),     /* Date début */
  filterDateFin:   document.getElementById("filterDateFin"),     /* Date fin */
  btnApplyFilters: document.getElementById("btnApplyFilters"),   /* Bouton appliquer */
  btnResetFilters: document.getElementById("btnResetFilters"),   /* Bouton réinitialiser */

  /* Tableau */
  vaccinTableBody: document.getElementById("vaccinTableBody"),   /* Corps du tableau */
  resultCount:     document.getElementById("resultCount"),       /* Compteur résultats */
  checkAll:        document.getElementById("checkAll"),          /* Checkbox tout sélectionner */
  paginationInfo:  document.getElementById("paginationInfo"),    /* Info pagination */
  paginationBtns:  document.getElementById("paginationBtns"),    /* Boutons pagination */
  btnExportCSV:    document.getElementById("btnExportCSV"),      /* Export CSV */
  btnPrint:        document.getElementById("btnPrint"),          /* Imprimer */

  /* En-têtes triables */
  sortables:       document.querySelectorAll(".sortable"),

  /* Alertes */
  alertsList:      document.getElementById("alertsList"),        /* Liste alertes */
  alertBadge:      document.getElementById("alertBadge"),        /* Badge alertes */

  /* Modal ajouter/modifier */
  modalVaccin:     document.getElementById("modalVaccin"),       /* Fond modal principal */
  modalTitle:      document.getElementById("modalTitle"),        /* Titre modal */
  modalClose:      document.getElementById("modalClose"),        /* Fermer modal */
  btnCancelModal:  document.getElementById("btnCancelModal"),    /* Annuler */
  btnSaveVaccin:   document.getElementById("btnSaveVaccin"),     /* Enregistrer */
  btnAddVaccin:    document.getElementById("btnAddVaccin"),      /* Ouvrir modal ajout */

  /* Champs du formulaire modal */
  vaccinId:        document.getElementById("vaccinId"),
  formPatient:     document.getElementById("formPatient"),
  formVaccin:      document.getElementById("formVaccin"),
  formDose:        document.getElementById("formDose"),
  formDate:        document.getElementById("formDate"),
  formRappel:      document.getElementById("formRappel"),
  formStatut:      document.getElementById("formStatut"),
  formPraticien:   document.getElementById("formPraticien"),
  formLot:         document.getElementById("formLot"),
  formNotes:       document.getElementById("formNotes"),
  toggleRappel:    document.getElementById("toggleRappel"),

  /* Modal détail */
  modalDetail:     document.getElementById("modalDetail"),       /* Fond modal détail */
  modalDetailBody: document.getElementById("modalDetailBody"),   /* Corps modal détail */
  detailClose:     document.getElementById("detailClose"),       /* Fermer détail */
  btnEditFromDetail: document.getElementById("btnEditFromDetail"), /* Modifier depuis détail */
  btnCloseDetail:  document.getElementById("btnCloseDetail"),    /* Fermer détail */

  /* Toast */
  toastContainer:  document.getElementById("toastContainer")    /* Zone toasts */
};

/* =============================================
   4. UTILITAIRES
   ============================================= */

/**
 * Formate une date ISO "YYYY-MM-DD" en "DD/MM/YYYY"
 * @param {string} dateStr - Date au format ISO
 * @returns {string} Date formatée ou tiret si vide
 */
function formatDate(dateStr) {
  if (!dateStr) return "—";                /* Retourne tiret si vide */
  const [y, m, d] = dateStr.split("-");   /* Découpe la date */
  return `${d}/${m}/${y}`;                /* Reformate JJ/MM/AAAA */
}

/**
 * Calcule le nombre de jours entre aujourd'hui et une date
 * @param {string} dateStr - Date cible ISO
 * @returns {number} Nombre de jours (négatif = passé)
 */
function joursDepuis(dateStr) {
  if (!dateStr) return null;
  const today  = new Date();                           /* Date actuelle */
  const target = new Date(dateStr);                    /* Date cible */
  return Math.round((target - today) / (1000 * 60 * 60 * 24)); /* Différence en jours */
}

/**
 * Génère un texte relatif de date
 * @param {string} dateStr - Date ISO
 * @returns {string} Texte relatif ("il y a 3j", "dans 5j")
 */
function relativeDate(dateStr) {
  const jours = joursDepuis(dateStr);
  if (jours === null) return "";
  if (jours === 0)    return "Aujourd'hui";
  if (jours > 0)      return `dans ${jours}j`;
  return `il y a ${Math.abs(jours)}j`;
}

/**
 * Retourne les classes CSS et l'icône du badge selon le statut
 * @param {string} statut - Statut vaccin
 * @returns {object} {cls, icon, label}
 */
function getStatutInfo(statut) {
  /* Correspondance statut → style */
  const map = {
    fait:     { cls: "badge-fait",     icon: "fa-circle-check",        label: "Effectué"   },
    planifie: { cls: "badge-planifie", icon: "fa-calendar",            label: "Planifié"   },
    retard:   { cls: "badge-retard",   icon: "fa-triangle-exclamation", label: "En retard" },
    rappel:   { cls: "badge-rappel",   icon: "fa-clock-rotate-left",   label: "Rappel"     }
  };
  return map[statut] || { cls: "", icon: "fa-circle", label: statut };
}

/**
 * Affiche un toast de notification
 * @param {string} msg     - Message à afficher
 * @param {string} type    - "success" | "error" | "warning" | "info"
 */
function showToast(msg, type = "success") {
  /* Correspondance type → icône */
  const icons = {
    success: "fa-circle-check",
    error:   "fa-circle-xmark",
    warning: "fa-triangle-exclamation",
    info:    "fa-circle-info"
  };

  /* Création du toast */
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;             /* Classes CSS */
  toast.innerHTML = `
    <i class="fa-solid ${icons[type] || icons.info} toast-icon"></i>
    <span class="toast-msg">${msg}</span>
  `;

  /* Ajout au conteneur */
  Dom.toastContainer.appendChild(toast);

  /* Suppression automatique après 3.5 secondes */
  setTimeout(() => {
    toast.remove();
  }, 3500);
}

/* =============================================
   5. AFFICHAGE DATE TOPBAR
   ============================================= */

/**
 * Affiche la date et l'heure courante dans la topbar
 * Met à jour chaque minute
 */
function updateTopbarDate() {
  const now = new Date();
  /* Options de formatage de la date */
  const opts = { weekday: "long", day: "numeric", month: "long", year: "numeric" };
  /* Formatage en français */
  Dom.topbarDate.textContent = now.toLocaleDateString("fr-FR", opts);
}

/* =============================================
   6. CALCUL DES STATISTIQUES
   ============================================= */

/**
 * Calcule et affiche les statistiques en temps réel
 */
function updateStats() {
  const data = State.allData;

  /* Compte total */
  const total   = data.length;

  /* Compte par statut */
  const fait    = data.filter(v => v.statut === "fait").length;
  const retard  = data.filter(v => v.statut === "retard").length;
  const rappels = data.filter(v => v.statut === "rappel" || v.statut === "planifie").length;

  /* Animation des valeurs : compte de 0 jusqu'à la valeur cible */
  animateCount(Dom.statTotal,   total);
  animateCount(Dom.statFait,    fait);
  animateCount(Dom.statRetard,  retard);
  animateCount(Dom.statRappels, rappels);

  /* Met à jour le badge notifications avec le nb d'alertes */
  Dom.notifCount.textContent = retard;
}

/**
 * Anime un compteur de 0 à la valeur cible
 * @param {HTMLElement} el    - Élément DOM à animer
 * @param {number}      target - Valeur cible
 */
function animateCount(el, target) {
  let current = 0;
  const step  = Math.ceil(target / 20);  /* Incréments pour 20 étapes */
  const timer = setInterval(() => {
    current = Math.min(current + step, target); /* Ne dépasse pas la cible */
    el.textContent = current;
    if (current >= target) clearInterval(timer); /* Arrête l'animation */
  }, 40); /* 40ms par étape = ~800ms total */
}

/* =============================================
   7. FILTRAGE DES DONNÉES
   ============================================= */

/**
 * Applique tous les filtres actifs sur les données
 * et remet à la première page
 */
function applyFilters() {
  /* Récupération des valeurs des filtres */
  const search  = Dom.searchPatient.value.toLowerCase().trim(); /* Recherche texte */
  const statut  = Dom.filterStatut.value;                       /* Statut sélectionné */
  const type    = Dom.filterType.value.toLowerCase();           /* Type vaccin */
  const age     = Dom.filterAge.value;                          /* Tranche d'âge */
  const dateDeb = Dom.filterDateDeb.value;                      /* Date début */
  const dateFin = Dom.filterDateFin.value;                      /* Date fin */

  /* Filtrage des données */
  State.filtered = State.allData.filter(v => {

    /* Filtre texte : patient ou vaccin */
    if (search && !v.patient.nom.toLowerCase().includes(search) && !v.vaccin.toLowerCase().includes(search)) {
      return false;
    }

    /* Filtre statut */
    if (statut && v.statut !== statut) return false;

    /* Filtre type vaccin */
    if (type && !v.vaccin.toLowerCase().includes(type)) return false;

    /* Filtre tranche d'âge */
    if (age) {
      const a = v.patient.age;
      if (age === "0-5"   && !(a >= 0  && a <= 5))  return false;
      if (age === "6-17"  && !(a >= 6  && a <= 17)) return false;
      if (age === "18-59" && !(a >= 18 && a <= 59)) return false;
      if (age === "60+"   && !(a >= 60))             return false;
    }

    /* Filtre date début */
    if (dateDeb && v.date < dateDeb) return false;

    /* Filtre date fin */
    if (dateFin && v.date > dateFin) return false;

    return true; /* La vaccination passe tous les filtres */
  });

  /* Tri appliqué après filtrage */
  sortData();

  /* Retour à la première page */
  State.currentPage = 1;

  /* Rendu du tableau et pagination */
  renderTable();
  renderPagination();

  /* Feedback si aucun résultat */
  if (State.filtered.length === 0) {
    showToast("Aucun résultat pour ces filtres.", "warning");
  }
}

/**
 * Réinitialise tous les filtres et réaffiche toutes les données
 */
function resetFilters() {
  /* Vider tous les champs de filtres */
  Dom.searchPatient.value = "";
  Dom.filterStatut.value  = "";
  Dom.filterType.value    = "";
  Dom.filterAge.value     = "";
  Dom.filterDateDeb.value = "";
  Dom.filterDateFin.value = "";

  /* Rétablir toutes les données */
  State.filtered = [...State.allData];
  State.currentPage = 1;

  /* Réafficher */
  renderTable();
  renderPagination();

  showToast("Filtres réinitialisés.", "info");
}

/* =============================================
   8. TRI DES DONNÉES
   ============================================= */

/**
 * Trie les données filtrées selon la colonne et direction courantes
 */
function sortData() {
  const { sortCol, sortDir } = State;

  State.filtered.sort((a, b) => {
    let va, vb;

    /* Extraction des valeurs selon la colonne */
    if (sortCol === "patient") { va = a.patient.nom; vb = b.patient.nom; }
    else if (sortCol === "vaccin")  { va = a.vaccin;  vb = b.vaccin;  }
    else if (sortCol === "date")    { va = a.date;    vb = b.date;    }
    else if (sortCol === "statut")  { va = a.statut;  vb = b.statut;  }
    else                            { va = a.date;    vb = b.date;    }

    /* Comparaison selon la direction */
    if (va < vb) return sortDir === "asc" ? -1 : 1;
    if (va > vb) return sortDir === "asc" ?  1 : -1;
    return 0;
  });
}

/**
 * Change la colonne et direction de tri, puis réaffiche
 * @param {string} col - Colonne cliquée
 */
function handleSort(col) {
  /* Si même colonne : inverse la direction */
  if (State.sortCol === col) {
    State.sortDir = State.sortDir === "asc" ? "desc" : "asc";
  } else {
    /* Nouvelle colonne : tri ascendant */
    State.sortCol = col;
    State.sortDir = "asc";
  }

  /* Mise à jour des icônes de tri dans les en-têtes */
  Dom.sortables.forEach(th => {
    const icon = th.querySelector("i");
    if (!icon) return;
    if (th.dataset.col === State.sortCol) {
      /* Icône selon direction */
      icon.className = `fa-solid ${State.sortDir === "asc" ? "fa-sort-up" : "fa-sort-down"}`;
    } else {
      /* Icône neutre pour les autres colonnes */
      icon.className = "fa-solid fa-sort";
    }
  });

  /* Appliquer le tri et réafficher */
  sortData();
  renderTable();
}

/* =============================================
   9. RENDU DU TABLEAU
   ============================================= */

/**
 * Génère et insère les lignes du tableau selon la page courante
 */
function renderTable() {
  const tbody = Dom.vaccinTableBody;
  tbody.innerHTML = ""; /* Vide le tableau */

  /* Calcul des indices de pagination */
  const start = (State.currentPage - 1) * State.perPage;  /* Index début */
  const end   = start + State.perPage;                      /* Index fin */
  const page  = State.filtered.slice(start, end);          /* Données de la page */

  /* Mise à jour du compteur résultats */
  Dom.resultCount.textContent =
    `${State.filtered.length} vaccination${State.filtered.length > 1 ? "s" : ""}`;

  /* Aucun résultat : afficher message vide */
  if (page.length === 0) {
    tbody.innerHTML = `
      <tr class="empty-row">
        <td colspan="8">
          <div class="empty-icon"><i class="fa-solid fa-syringe"></i></div>
          <p class="empty-text">Aucune vaccination trouvée</p>
          <p class="empty-sub">Modifiez vos filtres ou ajoutez une vaccination.</p>
        </td>
      </tr>`;
    return;
  }

  /* Rendu de chaque ligne */
  page.forEach((v, i) => {
    const statutInfo = getStatutInfo(v.statut);  /* Infos badge statut */
    const rappelTxt  = getRappelDisplay(v.rappel); /* Texte rappel */

    /* Création de la ligne */
    const tr = document.createElement("tr");
    tr.style.animationDelay = `${i * 0.04}s`; /* Délai animation entrée échelonnée */

    /* Contenu HTML de la ligne */
    tr.innerHTML = `
      <!-- Checkbox sélection -->
      <td class="col-check">
        <input type="checkbox" class="row-check" data-id="${v.id}" />
      </td>

      <!-- Informations patient -->
      <td class="col-patient">
        <div class="patient-cell">
          <img src="${v.patient.avatar}" alt="${v.patient.nom}" class="patient-mini-avatar" />
          <div>
            <p class="patient-cell-name">${v.patient.nom}</p>
            <p class="patient-cell-age">${v.patient.age} ans</p>
          </div>
        </div>
      </td>

      <!-- Nom du vaccin -->
      <td class="col-vaccin">
        <div class="vaccin-name">
          <span class="vaccin-icon"><i class="fa-solid fa-vials"></i></span>
          ${v.vaccin}
        </div>
      </td>

      <!-- Dose -->
      <td class="col-dose">
        <span class="dose-badge">${v.dose}</span>
      </td>

      <!-- Date d'administration -->
      <td class="col-date">
        <p class="date-cell">${formatDate(v.date)}</p>
        <p class="date-sub">${relativeDate(v.date)}</p>
      </td>

      <!-- Prochain rappel -->
      <td class="col-rappel">${rappelTxt}</td>

      <!-- Badge statut -->
      <td class="col-statut">
        <span class="badge-statut ${statutInfo.cls}">
          <i class="fa-solid ${statutInfo.icon}"></i>
          ${statutInfo.label}
        </span>
      </td>

      <!-- Boutons d'action -->
      <td class="col-actions">
        <div class="actions-cell">
          <!-- Voir le détail -->
          <button class="action-btn btn-view" title="Voir détail" onclick="openDetail(${v.id})">
            <i class="fa-solid fa-eye"></i>
          </button>
          <!-- Modifier -->
          <button class="action-btn btn-edit" title="Modifier" onclick="openEdit(${v.id})">
            <i class="fa-solid fa-pen"></i>
          </button>
          <!-- Supprimer -->
          <button class="action-btn btn-delete" title="Supprimer" onclick="deleteVaccin(${v.id})">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </td>
    `;

    tbody.appendChild(tr); /* Ajout de la ligne au tableau */
  });

  /* Mise à jour de la checkbox "tout sélectionner" */
  Dom.checkAll.checked = false;
}

/**
 * Génère le HTML d'affichage du rappel dans le tableau
 * @param {string} rappel - Date de rappel ISO
 * @returns {string} HTML du rappel
 */
function getRappelDisplay(rappel) {
  if (!rappel) {
    /* Pas de rappel prévu */
    return `<span class="rappel-cell rappel-none">—</span>`;
  }

  const jours = joursDepuis(rappel);

  if (jours < 0) {
    /* Rappel dépassé */
    return `<span class="rappel-cell rappel-none" style="color:var(--color-danger)">
      <i class="fa-solid fa-clock"></i> Dépassé
    </span>`;
  }

  if (jours <= 30) {
    /* Rappel bientôt (dans moins de 30 jours) */
    return `<span class="rappel-cell rappel-soon">
      <i class="fa-solid fa-bell"></i> ${formatDate(rappel)}
    </span>`;
  }

  /* Rappel normal */
  return `<span class="rappel-cell">${formatDate(rappel)}</span>`;
}

/* =============================================
   10. PAGINATION
   ============================================= */

/**
 * Génère et affiche les contrôles de pagination
 */
function renderPagination() {
  const total  = State.filtered.length;              /* Total des lignes filtrées */
  const pages  = Math.ceil(total / State.perPage);  /* Nombre total de pages */
  const cur    = State.currentPage;

  /* Info textuelle pagination */
  const start = (cur - 1) * State.perPage + 1;
  const end   = Math.min(cur * State.perPage, total);
  Dom.paginationInfo.textContent = total > 0
    ? `Affichage ${start}–${end} sur ${total}`
    : "Aucun résultat";

  /* Nettoyage des boutons */
  Dom.paginationBtns.innerHTML = "";

  if (pages <= 1) return; /* Pas de pagination si une seule page */

  /* Bouton précédent */
  const prevBtn = document.createElement("button");
  prevBtn.className = "page-btn";
  prevBtn.innerHTML = `<i class="fa-solid fa-chevron-left"></i>`;
  prevBtn.disabled = cur === 1; /* Désactivé sur la première page */
  prevBtn.addEventListener("click", () => goToPage(cur - 1));
  Dom.paginationBtns.appendChild(prevBtn);

  /* Boutons de numéros de pages */
  for (let p = 1; p <= pages; p++) {
    /* Affiche les pages proches + première + dernière */
    if (p === 1 || p === pages || (p >= cur - 1 && p <= cur + 1)) {
      const btn = document.createElement("button");
      btn.className = `page-btn ${p === cur ? "active" : ""}`;
      btn.textContent = p;
      btn.addEventListener("click", () => goToPage(p));
      Dom.paginationBtns.appendChild(btn);
    } else if (p === cur - 2 || p === cur + 2) {
      /* Points de suspension */
      const ellipsis = document.createElement("span");
      ellipsis.textContent = "…";
      ellipsis.style.cssText = "padding: 0 4px; color: var(--color-text-muted); font-size: 13px;";
      Dom.paginationBtns.appendChild(ellipsis);
    }
  }

  /* Bouton suivant */
  const nextBtn = document.createElement("button");
  nextBtn.className = "page-btn";
  nextBtn.innerHTML = `<i class="fa-solid fa-chevron-right"></i>`;
  nextBtn.disabled = cur === pages; /* Désactivé sur la dernière page */
  nextBtn.addEventListener("click", () => goToPage(cur + 1));
  Dom.paginationBtns.appendChild(nextBtn);
}

/**
 * Navigue vers une page donnée
 * @param {number} page - Numéro de la page cible
 */
function goToPage(page) {
  const pages = Math.ceil(State.filtered.length / State.perPage);
  if (page < 1 || page > pages) return; /* Vérification limites */
  State.currentPage = page;
  renderTable();
  renderPagination();

  /* Scroll vers le haut du tableau */
  Dom.vaccinTableBody.closest(".table-section").scrollIntoView({ behavior: "smooth", block: "start" });
}

/* =============================================
   11. ALERTES VACCINS EN RETARD
   ============================================= */

/**
 * Affiche les alertes pour les vaccins en retard
 */
function renderAlerts() {
  /* Filtre les vaccins en retard */
  const retards = State.allData.filter(v => v.statut === "retard");

  /* Met à jour le badge */
  Dom.alertBadge.textContent = retards.length;

  /* Vide la liste */
  Dom.alertsList.innerHTML = "";

  if (retards.length === 0) {
    /* Message si aucun retard */
    Dom.alertsList.innerHTML = `
      <div style="text-align:center; padding:20px; color:var(--color-text-muted); font-size:14px;">
        <i class="fa-solid fa-circle-check" style="font-size:28px; color:var(--color-success); display:block; margin-bottom:8px;"></i>
        Aucun vaccin en retard. Excellent suivi !
      </div>`;
    return;
  }

  /* Rendu de chaque alerte */
  retards.forEach((v, i) => {
    const jours    = joursDepuis(v.rappel);                    /* Jours de retard */
    const retardTxt = jours
      ? `Rappel prévu le ${formatDate(v.rappel)} — retard de ${Math.abs(jours)} jours`
      : `Date de rappel dépassée`;

    const div = document.createElement("div");
    div.className = "alert-item";
    div.style.animationDelay = `${i * 0.07}s`;

    div.innerHTML = `
      <!-- Icône alerte -->
      <div class="alert-icon">
        <i class="fa-solid fa-triangle-exclamation"></i>
      </div>
      <!-- Contenu texte -->
      <div class="alert-content">
        <p class="alert-text">${v.patient.nom} — ${v.vaccin} (${v.dose})</p>
        <p class="alert-sub">${retardTxt}</p>
      </div>
      <!-- Bouton action rapide -->
      <button class="alert-action" onclick="openEdit(${v.id})">
        <i class="fa-solid fa-pen"></i> Traiter
      </button>
    `;

    Dom.alertsList.appendChild(div);
  });
}

/* =============================================
   12. GESTION DE LA MODAL AJOUTER/MODIFIER
   ============================================= */

/**
 * Peuple la liste des patients dans le select de la modal
 */
function populatePatientSelect() {
  /* Récupère les patients uniques */
  const patients = [...new Map(State.allData.map(v => [v.patient.nom, v.patient])).values()];
  Dom.formPatient.innerHTML = `<option value="">— Sélectionner un patient —</option>`;
  patients.forEach(p => {
    const opt = document.createElement("option");
    opt.value = p.nom;
    opt.textContent = `${p.nom} (${p.age} ans)`;
    Dom.formPatient.appendChild(opt);
  });
}

/**
 * Ouvre la modal en mode ajout (formulaire vide)
 */
function openAdd() {
  State.editId = null;                                /* Pas d'édition */
  Dom.modalTitle.innerHTML = `<i class="fa-solid fa-syringe"></i> Ajouter un vaccin`;
  resetModalForm();                                   /* Réinitialise le formulaire */
  populatePatientSelect();
  /* Date par défaut = aujourd'hui */
  Dom.formDate.valueAsDate = new Date();
  openModal(Dom.modalVaccin);
}

/**
 * Ouvre la modal en mode édition avec les données existantes
 * @param {number} id - ID de la vaccination à modifier
 */
function openEdit(id) {
  /* Ferme la modal détail si ouverte */
  closeModal(Dom.modalDetail);

  const v = State.allData.find(v => v.id === id);   /* Cherche la vaccination */
  if (!v) return;

  State.editId = id;                                 /* Stocke l'ID en édition */
  Dom.modalTitle.innerHTML = `<i class="fa-solid fa-pen"></i> Modifier le vaccin`;
  populatePatientSelect();

  /* Remplit le formulaire avec les données existantes */
  Dom.vaccinId.value       = v.id;
  Dom.formPatient.value    = v.patient.nom;
  Dom.formVaccin.value     = v.vaccin;
  Dom.formDose.value       = v.dose;
  Dom.formDate.value       = v.date;
  Dom.formRappel.value     = v.rappel || "";
  Dom.formStatut.value     = v.statut;
  Dom.formPraticien.value  = v.praticien;
  Dom.formLot.value        = v.lot || "";
  Dom.formNotes.value      = v.notes || "";

  openModal(Dom.modalVaccin);
}

/**
 * Sauvegarde les données du formulaire (ajout ou modification)
 */
function saveVaccin() {
  /* Validation des champs requis */
  if (!Dom.formPatient.value || !Dom.formVaccin.value || !Dom.formDate.value || !Dom.formStatut.value) {
    showToast("Veuillez remplir tous les champs obligatoires (*)", "error");
    return;
  }

  if (State.editId !== null) {
    /* === MODE ÉDITION : mise à jour de la vaccination === */
    const idx = State.allData.findIndex(v => v.id === State.editId);
    if (idx !== -1) {
      /* Met à jour les champs modifiables */
      State.allData[idx].vaccin    = Dom.formVaccin.value;
      State.allData[idx].dose      = Dom.formDose.value;
      State.allData[idx].date      = Dom.formDate.value;
      State.allData[idx].rappel    = Dom.formRappel.value;
      State.allData[idx].statut    = Dom.formStatut.value;
      State.allData[idx].praticien = Dom.formPraticien.value;
      State.allData[idx].lot       = Dom.formLot.value;
      State.allData[idx].notes     = Dom.formNotes.value;
    }
    showToast("Vaccination mise à jour avec succès !", "success");

    /* Envoi réel : fetch('/api/vaccins/update', { method: 'POST', body: ... }) */

  } else {
    /* === MODE AJOUT : création d'une nouvelle vaccination === */
    const newId = Math.max(...State.allData.map(v => v.id)) + 1; /* Nouvel ID */

    /* Recherche l'avatar du patient si existant */
    const patientExist = State.allData.find(v => v.patient.nom === Dom.formPatient.value);
    const avatar = patientExist
      ? patientExist.patient.avatar
      : `https://ui-avatars.com/api/?name=${encodeURIComponent(Dom.formPatient.value)}&background=4A7FA7&color=fff&size=60`;

    /* Création de l'objet vaccination */
    const newV = {
      id: newId,
      patient:   { nom: Dom.formPatient.value, age: patientExist ? patientExist.patient.age : 0, avatar },
      vaccin:    Dom.formVaccin.value,
      dose:      Dom.formDose.value,
      date:      Dom.formDate.value,
      rappel:    Dom.formRappel.value,
      statut:    Dom.formStatut.value,
      praticien: Dom.formPraticien.value,
      lot:       Dom.formLot.value,
      notes:     Dom.formNotes.value
    };

    /* Ajout au tableau de données */
    State.allData.unshift(newV);

    showToast("Vaccination ajoutée avec succès !", "success");

    /* Envoi réel : fetch('/api/vaccins/add', { method: 'POST', body: ... }) */
  }

  /* Mise à jour de l'état filtré et rafraîchissement */
  State.filtered = [...State.allData];
  State.currentPage = 1;

  closeModal(Dom.modalVaccin);
  updateStats();
  renderTable();
  renderPagination();
  renderAlerts();
}

/**
 * Réinitialise tous les champs du formulaire de la modal
 */
function resetModalForm() {
  Dom.vaccinId.value       = "";
  Dom.formPatient.value    = "";
  Dom.formVaccin.value     = "";
  Dom.formDose.value       = "1";
  Dom.formDate.value       = "";
  Dom.formRappel.value     = "";
  Dom.formStatut.value     = "fait";
  Dom.formPraticien.value  = "Dr. Jean Martin";
  Dom.formLot.value        = "";
  Dom.formNotes.value      = "";
  Dom.toggleRappel.checked = false;
}

/* =============================================
   13. MODAL DÉTAIL
   ============================================= */

/**
 * Ouvre la modal de détail pour une vaccination donnée
 * @param {number} id - ID de la vaccination
 */
function openDetail(id) {
  const v = State.allData.find(v => v.id === id);
  if (!v) return;

  State.detailId = id;                              /* Stocke l'ID pour édition */

  const si = getStatutInfo(v.statut);

  /* Génère le contenu HTML du détail */
  Dom.modalDetailBody.innerHTML = `
    <!-- En-tête patient -->
    <div class="detail-header">
      <img src="${v.patient.avatar}" alt="${v.patient.nom}" class="detail-avatar" />
      <p class="detail-patient-name">${v.patient.nom}</p>
      <p class="detail-sub">${v.patient.age} ans &nbsp;·&nbsp;
        <span class="badge-statut ${si.cls}" style="font-size:12px; padding:3px 10px;">
          <i class="fa-solid ${si.icon}"></i> ${si.label}
        </span>
      </p>
    </div>

    <!-- Grille d'informations -->
    <div class="detail-grid">
      <div class="detail-item">
        <p class="detail-label"><i class="fa-solid fa-vials"></i> Vaccin</p>
        <p class="detail-value">${v.vaccin}</p>
      </div>
      <div class="detail-item">
        <p class="detail-label"><i class="fa-solid fa-hashtag"></i> Dose</p>
        <p class="detail-value">${v.dose}</p>
      </div>
      <div class="detail-item">
        <p class="detail-label"><i class="fa-solid fa-calendar"></i> Date admin.</p>
        <p class="detail-value">${formatDate(v.date)}</p>
      </div>
      <div class="detail-item">
        <p class="detail-label"><i class="fa-solid fa-clock-rotate-left"></i> Prochain rappel</p>
        <p class="detail-value">${v.rappel ? formatDate(v.rappel) : "Aucun"}</p>
      </div>
      <div class="detail-item">
        <p class="detail-label"><i class="fa-solid fa-user-doctor"></i> Praticien</p>
        <p class="detail-value">${v.praticien || "—"}</p>
      </div>
      <div class="detail-item">
        <p class="detail-label"><i class="fa-solid fa-barcode"></i> N° de lot</p>
        <p class="detail-value">${v.lot || "—"}</p>
      </div>
    </div>

    <!-- Notes si présentes -->
    ${v.notes ? `
    <div class="detail-notes">
      <p style="font-size:11.5px; font-weight:700; color:var(--color-info); margin-bottom:4px;">
        <i class="fa-solid fa-note-sticky"></i> Notes médicales
      </p>
      <p style="font-size:13px; color:var(--color-text-dark);">${v.notes}</p>
    </div>` : ""}
  `;

  openModal(Dom.modalDetail);
}

/* =============================================
   14. SUPPRESSION D'UNE VACCINATION
   ============================================= */

/**
 * Supprime une vaccination après confirmation
 * @param {number} id - ID à supprimer
 */
function deleteVaccin(id) {
  /* Confirmation utilisateur */
  if (!confirm("Voulez-vous vraiment supprimer cette vaccination ? Cette action est irréversible.")) return;

  /* Suppression dans le tableau */
  State.allData = State.allData.filter(v => v.id !== id);
  State.filtered = State.filtered.filter(v => v.id !== id);

  /* Retour à la première page si page vide */
  const pages = Math.ceil(State.filtered.length / State.perPage);
  if (State.currentPage > pages) State.currentPage = Math.max(1, pages);

  /* Rafraîchissement complet */
  updateStats();
  renderTable();
  renderPagination();
  renderAlerts();

  showToast("Vaccination supprimée.", "warning");

  /* Envoi réel : fetch('/api/vaccins/delete', { method: 'POST', body: JSON.stringify({id}) }) */
}

/* =============================================
   15. EXPORT CSV
   ============================================= */

/**
 * Exporte les données filtrées au format CSV
 */
function exportCSV() {
  /* En-têtes CSV */
  const headers = ["Patient", "Âge", "Vaccin", "Dose", "Date", "Rappel", "Statut", "Praticien", "Lot"];

  /* Lignes de données */
  const rows = State.filtered.map(v => [
    v.patient.nom,
    v.patient.age,
    v.vaccin,
    v.dose,
    formatDate(v.date),
    formatDate(v.rappel),
    getStatutInfo(v.statut).label,
    v.praticien,
    v.lot
  ]);

  /* Construction du contenu CSV */
  const csv = [headers, ...rows]
    .map(r => r.map(c => `"${c}"`).join(";"))  /* Séparateur point-virgule */
    .join("\n");

  /* Création et téléchargement du fichier */
  const blob = new Blob(["\uFEFF" + csv], { type: "text/csv;charset=utf-8;" }); /* BOM UTF-8 */
  const url  = URL.createObjectURL(blob);
  const link = document.createElement("a");
  link.href     = url;
  link.download = `vaccinations_${new Date().toISOString().slice(0,10)}.csv`;
  document.body.appendChild(link);
  link.click();                                 /* Déclenche le téléchargement */
  document.body.removeChild(link);
  URL.revokeObjectURL(url);

  showToast("Export CSV téléchargé !", "success");
}

/* =============================================
   16. SÉLECTION TOUT / DÉCOCHER
   ============================================= */

/**
 * Coche ou décoche toutes les checkboxes des lignes visibles
 */
function toggleSelectAll() {
  const checked = Dom.checkAll.checked;
  /* Sélectionne toutes les checkboxes de ligne */
  document.querySelectorAll(".row-check").forEach(cb => {
    cb.checked = checked;
  });
}

/* =============================================
   17. OUVERTURE / FERMETURE DES MODALS
   ============================================= */

/**
 * Ouvre une modal (ajoute la classe "open")
 * @param {HTMLElement} modal - Élément modal backdrop
 */
function openModal(modal) {
  modal.setAttribute("aria-hidden", "false");
  modal.classList.add("open");
  document.body.style.overflow = "hidden"; /* Bloque le scroll de la page */
}

/**
 * Ferme une modal (retire la classe "open")
 * @param {HTMLElement} modal - Élément modal backdrop
 */
function closeModal(modal) {
  modal.setAttribute("aria-hidden", "true");
  modal.classList.remove("open");
  document.body.style.overflow = ""; /* Rétablit le scroll */
}

/* =============================================
   18. SIDEBAR MOBILE
   ============================================= */

/**
 * Ouvre la sidebar sur mobile
 */
function openSidebar() {
  Dom.sidebar.classList.add("open");
  Dom.sidebarOverlay.classList.add("show");
}

/**
 * Ferme la sidebar sur mobile
 */
function closeSidebar() {
  Dom.sidebar.classList.remove("open");
  Dom.sidebarOverlay.classList.remove("show");
}

/* =============================================
   19. LIAISONS DES ÉVÉNEMENTS
   ============================================= */

/**
 * Attache tous les écouteurs d'événements
 */
function bindEvents() {

  /* --- Sidebar mobile --- */
  Dom.hamburger.addEventListener("click",      openSidebar);   /* Ouvrir */
  Dom.sidebarClose.addEventListener("click",   closeSidebar);  /* Fermer via bouton */
  Dom.sidebarOverlay.addEventListener("click", closeSidebar);  /* Fermer via overlay */

  /* --- Filtres --- */
  Dom.btnApplyFilters.addEventListener("click", applyFilters);  /* Appliquer filtres */
  Dom.btnResetFilters.addEventListener("click", resetFilters);  /* Réinitialiser */

  /* Recherche en temps réel (frappe par frappe) */
  Dom.searchPatient.addEventListener("input", () => {
    clearTimeout(Dom._searchTimer);                             /* Annule le timer précédent */
    Dom._searchTimer = setTimeout(applyFilters, 300);          /* Délai 300ms (debounce) */
  });

  /* Tri des colonnes */
  Dom.sortables.forEach(th => {
    th.addEventListener("click", () => handleSort(th.dataset.col));
  });

  /* --- Modal ajouter --- */
  Dom.btnAddVaccin.addEventListener("click",  openAdd);          /* Ouvrir modal ajout */
  Dom.modalClose.addEventListener("click",    () => closeModal(Dom.modalVaccin));
  Dom.btnCancelModal.addEventListener("click",() => closeModal(Dom.modalVaccin));
  Dom.btnSaveVaccin.addEventListener("click", saveVaccin);       /* Enregistrer */

  /* Fermer modal au clic sur le backdrop */
  Dom.modalVaccin.addEventListener("click", e => {
    if (e.target === Dom.modalVaccin) closeModal(Dom.modalVaccin);
  });

  /* --- Modal détail --- */
  Dom.detailClose.addEventListener("click",    () => closeModal(Dom.modalDetail));
  Dom.btnCloseDetail.addEventListener("click", () => closeModal(Dom.modalDetail));
  Dom.btnEditFromDetail.addEventListener("click", () => {
    if (State.detailId) openEdit(State.detailId); /* Éditer depuis le détail */
  });

  /* Fermer modal détail au clic backdrop */
  Dom.modalDetail.addEventListener("click", e => {
    if (e.target === Dom.modalDetail) closeModal(Dom.modalDetail);
  });

  /* --- Export & impression --- */
  Dom.btnExportCSV.addEventListener("click", exportCSV);
  Dom.btnPrint.addEventListener("click",     () => window.print());

  /* --- Checkbox tout sélectionner --- */
  Dom.checkAll.addEventListener("change", toggleSelectAll);

  /* --- Fermer modals avec touche Échap --- */
  document.addEventListener("keydown", e => {
    if (e.key === "Escape") {
      closeModal(Dom.modalVaccin);    /* Ferme modal ajout/edit */
      closeModal(Dom.modalDetail);   /* Ferme modal détail */
      closeSidebar();                /* Ferme sidebar mobile */
    }
  });
}

/* =============================================
   20. INITIALISATION
   ============================================= */

/**
 * Point d'entrée : initialise tous les composants de la page
 */
function init() {
  /* Affiche la date dans la topbar */
  updateTopbarDate();

  /* Tri initial par date décroissante */
  sortData();

  /* Calcule et affiche les statistiques */
  updateStats();

  /* Rendu initial du tableau */
  renderTable();

  /* Rendu de la pagination */
  renderPagination();

  /* Rendu des alertes vaccins en retard */
  renderAlerts();

  /* Attache tous les événements */
  bindEvents();
}

/* Lancement au chargement du DOM */
document.addEventListener("DOMContentLoaded", init);