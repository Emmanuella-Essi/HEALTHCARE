/**
 * agenda.js — Logique du calendrier et des rendez-vous médecin
 * ─────────────────────────────────────────────────────────────
 * Fonctionnalités :
 *  - Rendu calendrier mensuel / semaine / jour
 *  - Mini-calendrier de navigation rapide
 *  - CRUD rendez-vous via API PHP (agenda.php)
 *  - Sélection de jour → affichage des RDV
 *  - Modal d'ajout / modification / suppression
 *  - Légende avec compteurs par type
 *  - Toast de confirmation
 * ─────────────────────────────────────────────────────────────
 */

/* ============================================================
   DONNÉES SIMULÉES (en attendant le PHP)
   En production, ces données viennent de agenda.php via fetch()
   ============================================================ */

/** @type {Array} rendez-vous simulés */
const DEMO_RDV = [
  {
    id: 1,
    patientId: 1,
    patientNom: "Martin Dupont",
    type: "consultation",
    date: todayISO(),             /* Aujourd'hui */
    heure: "09:00",
    duree: 30,
    motif: "Bilan annuel de santé"
  },
  {
    id: 2,
    patientId: 2,
    patientNom: "Sophie Bernard",
    type: "suivi",
    date: todayISO(),
    heure: "10:30",
    duree: 30,
    motif: "Suivi diabète type 2"
  },
  {
    id: 3,
    patientId: 3,
    patientNom: "Jean Moreau",
    type: "urgence",
    date: todayISO(),
    heure: "14:00",
    duree: 45,
    motif: "Douleur thoracique"
  },
  {
    id: 4,
    patientId: 4,
    patientNom: "Claire Petit",
    type: "tele",
    date: offsetDate(2),          /* Dans 2 jours */
    heure: "11:00",
    duree: 30,
    motif: "Renouvellement ordonnance"
  },
  {
    id: 5,
    patientId: 5,
    patientNom: "Ahmed Kaddour",
    type: "consultation",
    date: offsetDate(2),
    heure: "15:00",
    duree: 30,
    motif: "Douleurs lombaires"
  },
  {
    id: 6,
    patientId: 1,
    patientNom: "Martin Dupont",
    type: "suivi",
    date: offsetDate(-3),         /* Il y a 3 jours */
    heure: "09:30",
    duree: 30,
    motif: "Contrôle tension"
  },
  {
    id: 7,
    patientId: 6,
    patientNom: "Lucie Fontaine",
    type: "consultation",
    date: offsetDate(7),
    heure: "16:00",
    duree: 30,
    motif: "Dermatite"
  }
];

/** Liste patients simulée pour le select du modal */
const DEMO_PATIENTS = [
  { id: 1, nom: "Martin Dupont" },
  { id: 2, nom: "Sophie Bernard" },
  { id: 3, nom: "Jean Moreau" },
  { id: 4, nom: "Claire Petit" },
  { id: 5, nom: "Ahmed Kaddour" },
  { id: 6, nom: "Lucie Fontaine" },
  { id: 7, nom: "Nicolas Leblanc" }
];


/* ============================================================
   ÉTAT GLOBAL DE L'APPLICATION
   ============================================================ */

/** Année et mois affichés dans le calendrier principal */
let currentYear  = new Date().getFullYear();   /* Année courante */
let currentMonth = new Date().getMonth();       /* Mois courant (0-11) */

/** Date sélectionnée (la cellule cliquée) */
let selectedDate = todayISO();                  /* Par défaut = aujourd'hui */

/** Tableau des rendez-vous (manipulé en mémoire) */
let allRdv = [...DEMO_RDV];                     /* Copie des données démo */

/** Mode actuel du modal : 'add' ou 'edit' */
let modalMode = 'add';

/** RDV en cours d'édition (objet complet) */
let editingRdv = null;

/** Vue active : 'month' | 'week' | 'day' */
let currentView = 'month';

/** ID auto-incrémenté pour les nouveaux RDV */
let nextId = 100;


/* ============================================================
   UTILITAIRES
   ============================================================ */

/**
 * Retourne la date d'aujourd'hui au format YYYY-MM-DD
 * @returns {string}
 */
function todayISO() {
  return new Date().toISOString().split('T')[0];  /* Extrait la partie date */
}

/**
 * Retourne une date décalée de N jours depuis aujourd'hui
 * @param {number} days - Nombre de jours (positif = futur, négatif = passé)
 * @returns {string} Format YYYY-MM-DD
 */
function offsetDate(days) {
  const d = new Date();
  d.setDate(d.getDate() + days);    /* Ajoute/soustrait les jours */
  return d.toISOString().split('T')[0];
}

/**
 * Formate une date YYYY-MM-DD en date lisible française
 * @param {string} iso - Date au format YYYY-MM-DD
 * @returns {string} Ex: "lundi 8 mai 2025"
 */
function formatDateFr(iso) {
  const d = new Date(iso + 'T00:00:00');  /* Force minuit local (évite décalage TZ) */
  return d.toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
}

/**
 * Capitalise la première lettre d'une chaîne
 * @param {string} s
 * @returns {string}
 */
function capitalize(s) {
  return s.charAt(0).toUpperCase() + s.slice(1);
}

/**
 * Retourne le libellé lisible d'un type de RDV
 * @param {string} type - 'consultation' | 'urgence' | 'suivi' | 'tele'
 * @returns {string}
 */
function typeLabel(type) {
  const labels = {
    consultation: '🩺 Consultation',
    urgence:      '🚨 Urgence',
    suivi:        '🔄 Suivi régulier',
    tele:         '💻 Téléconsultation'
  };
  return labels[type] || type;
}

/**
 * Retourne la couleur CSS d'un type de RDV
 * @param {string} type
 * @returns {string} Valeur CSS
 */
function typeColor(type) {
  const colors = {
    consultation: 'var(--color-consult)',
    urgence:      'var(--color-urgence)',
    suivi:        'var(--color-suivi)',
    tele:         'var(--color-tele)'
  };
  return colors[type] || 'var(--color-accent)';
}

/**
 * Génère les initiales d'un nom (2 lettres max)
 * @param {string} nom
 * @returns {string}
 */
function initiales(nom) {
  return nom
    .split(' ')
    .map(w => w[0])       /* Première lettre de chaque mot */
    .slice(0, 2)          /* Maximum 2 initiales */
    .join('')
    .toUpperCase();
}


/* ============================================================
   CALENDRIER MENSUEL
   ============================================================ */

/**
 * Rend la grille du calendrier mensuel dans #calGrid
 * Affiche les jours du mois avec leurs événements
 */
function renderCalendar() {
  const grid = document.getElementById('calGrid');
  if (!grid) return;

  grid.innerHTML = '';                /* Vide la grille précédente */

  /* Nom du mois + année dans le titre */
  const monthName = new Date(currentYear, currentMonth, 1)
    .toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
  document.getElementById('calPeriod').textContent = capitalize(monthName);

  /* Met à jour aussi le mini calendrier */
  renderMiniCal();

  /* Premier jour du mois (0=dim, 1=lun, ..., 6=sam) */
  const firstDay = new Date(currentYear, currentMonth, 1).getDay();

  /* Décalage pour commencer la semaine le LUNDI
     Si firstDay=0 (dimanche), on veut 6 cases vides
     Si firstDay=1 (lundi), 0 case vide, etc. */
  const startOffset = (firstDay === 0) ? 6 : firstDay - 1;

  /* Nombre de jours dans le mois */
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

  /* Nombre de jours dans le mois précédent */
  const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

  /* Total de cellules : 6 semaines × 7 jours = 42 */
  const totalCells = 42;

  /* Aujourd'hui pour comparaison */
  const todayStr = todayISO();

  /* Génération de chaque cellule */
  for (let i = 0; i < totalCells; i++) {
    const cell = document.createElement('div');
    cell.className = 'cal-cell';

    let cellDateStr;      /* Date de cette cellule au format YYYY-MM-DD */
    let dayNum;           /* Numéro du jour à afficher */
    let isCurrentMonth = true;

    if (i < startOffset) {
      /* ── Jours du MOIS PRÉCÉDENT ── */
      isCurrentMonth = false;
      dayNum = daysInPrevMonth - startOffset + i + 1;
      const prevMonth = currentMonth === 0 ? 11 : currentMonth - 1;
      const prevYear  = currentMonth === 0 ? currentYear - 1 : currentYear;
      cellDateStr = `${prevYear}-${String(prevMonth + 1).padStart(2,'0')}-${String(dayNum).padStart(2,'0')}`;
      cell.classList.add('other-month');

    } else if (i < startOffset + daysInMonth) {
      /* ── Jours du MOIS COURANT ── */
      dayNum = i - startOffset + 1;
      cellDateStr = `${currentYear}-${String(currentMonth + 1).padStart(2,'0')}-${String(dayNum).padStart(2,'0')}`;

    } else {
      /* ── Jours du MOIS SUIVANT ── */
      isCurrentMonth = false;
      dayNum = i - startOffset - daysInMonth + 1;
      const nextMonth = currentMonth === 11 ? 0 : currentMonth + 1;
      const nextYear  = currentMonth === 11 ? currentYear + 1 : currentYear;
      cellDateStr = `${nextYear}-${String(nextMonth + 1).padStart(2,'0')}-${String(dayNum).padStart(2,'0')}`;
      cell.classList.add('other-month');
    }

    /* Marque "aujourd'hui" */
    if (cellDateStr === todayStr) cell.classList.add('today');

    /* Marque le jour sélectionné */
    if (cellDateStr === selectedDate) cell.classList.add('selected');

    /* Numéro du jour */
    const numEl = document.createElement('div');
    numEl.className = 'cal-day-num';
    numEl.textContent = dayNum;
    cell.appendChild(numEl);

    /* RDV de cette journée */
    const dayRdvs = allRdv.filter(r => r.date === cellDateStr);

    /* Affiche jusqu'à 2 événements + mention "+N autres" */
    const maxVisible = 2;
    dayRdvs.slice(0, maxVisible).forEach(rdv => {
      const ev = document.createElement('div');
      ev.className = `cal-event event-${rdv.type}`;
      ev.innerHTML = `<span class="cal-event-dot"></span>${rdv.patientNom.split(' ')[0]} ${rdv.heure}`;
      ev.title = `${rdv.patientNom} — ${rdv.heure} (${typeLabel(rdv.type)})`;

      /* Clic sur l'événement : ouvre le modal d'édition */
      ev.addEventListener('click', (e) => {
        e.stopPropagation();            /* Empêche la propagation vers la cellule */
        openEditModal(rdv);
      });

      cell.appendChild(ev);
    });

    /* Mention "+ N autres" si plus de 2 RDV */
    if (dayRdvs.length > maxVisible) {
      const more = document.createElement('div');
      more.className = 'cal-more';
      more.textContent = `+${dayRdvs.length - maxVisible} autres`;
      cell.appendChild(more);
    }

    /* Clic sur la cellule : sélectionne le jour */
    const _date = cellDateStr;          /* Capture dans la closure */
    cell.addEventListener('click', () => {
      selectDay(_date);
    });

    grid.appendChild(cell);
  }
}

/**
 * Sélectionne un jour et met à jour l'UI
 * @param {string} dateStr - Format YYYY-MM-DD
 */
function selectDay(dateStr) {
  selectedDate = dateStr;              /* Met à jour l'état global */
  renderCalendar();                    /* Re-rend le calendrier (marque selected) */
  renderRdvDay(dateStr);               /* Affiche les RDV du jour dans le panneau */
}


/* ============================================================
   MINI CALENDRIER (panneau latéral)
   ============================================================ */

/**
 * Rend le mini calendrier de navigation dans #miniCalGrid
 * Synchronisé avec le calendrier principal
 */
function renderMiniCal() {
  const grid = document.getElementById('miniCalGrid');
  if (!grid) return;

  /* Conserve les 7 en-têtes de jours (L M M J V S D) */
  const headers = grid.querySelectorAll('.mini-day-name');

  /* Vide et réinsère les en-têtes */
  grid.innerHTML = '';
  headers.forEach(h => grid.appendChild(h.cloneNode(true)));

  /* Titre du mini cal */
  const title = new Date(currentYear, currentMonth, 1)
    .toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
  document.getElementById('miniCalTitle').textContent = capitalize(title);

  /* Calculs identiques au calendrier principal */
  const firstDay     = new Date(currentYear, currentMonth, 1).getDay();
  const startOffset  = (firstDay === 0) ? 6 : firstDay - 1;
  const daysInMonth  = new Date(currentYear, currentMonth + 1, 0).getDate();
  const todayStr     = todayISO();

  /* Dates avec événements (pour afficher le point vert) */
  const datesWithEvents = new Set(allRdv.map(r => r.date));

  /* Génération des 35 cellules */
  for (let i = 0; i < 35; i++) {
    const div = document.createElement('div');
    div.className = 'mini-day';

    let dayNum, cellDate, inMonth = true;

    if (i < startOffset) {
      /* Mois précédent */
      inMonth = false;
      const prevM = currentMonth === 0 ? 11 : currentMonth - 1;
      const prevY = currentMonth === 0 ? currentYear - 1 : currentYear;
      const prevDays = new Date(prevY, prevM + 1, 0).getDate();
      dayNum = prevDays - startOffset + i + 1;
      cellDate = `${prevY}-${String(prevM + 1).padStart(2,'0')}-${String(dayNum).padStart(2,'0')}`;
      div.classList.add('other');
    } else if (i < startOffset + daysInMonth) {
      dayNum = i - startOffset + 1;
      cellDate = `${currentYear}-${String(currentMonth + 1).padStart(2,'0')}-${String(dayNum).padStart(2,'0')}`;
    } else {
      /* Mois suivant */
      inMonth = false;
      dayNum = i - startOffset - daysInMonth + 1;
      const nextM = currentMonth === 11 ? 0 : currentMonth + 1;
      const nextY = currentMonth === 11 ? currentYear + 1 : currentYear;
      cellDate = `${nextY}-${String(nextM + 1).padStart(2,'0')}-${String(dayNum).padStart(2,'0')}`;
      div.classList.add('other');
    }

    div.textContent = dayNum;

    /* Marque aujourd'hui */
    if (cellDate === todayStr) div.classList.add('today');

    /* Marque sélectionné */
    if (cellDate === selectedDate) div.classList.add('selected');

    /* Marque les jours avec événements */
    if (datesWithEvents.has(cellDate) && inMonth) div.classList.add('has-event');

    /* Clic : sélectionne le jour */
    if (inMonth) {
      const _date = cellDate;
      div.addEventListener('click', () => selectDay(_date));
    }

    grid.appendChild(div);
  }
}


/* ============================================================
   PANNEAU RDV DU JOUR SÉLECTIONNÉ
   ============================================================ */

/**
 * Affiche les rendez-vous d'un jour donné dans le panneau latéral
 * @param {string} dateStr - Format YYYY-MM-DD
 */
function renderRdvDay(dateStr) {
  const list    = document.getElementById('rdvDayList');
  const title   = document.getElementById('rdvDayTitle');
  const badge   = document.getElementById('rdvCountBadge');

  /* Filtre les RDV de ce jour */
  const rdvs = allRdv
    .filter(r => r.date === dateStr)
    .sort((a, b) => a.heure.localeCompare(b.heure));  /* Trie par heure */

  /* Met à jour le titre avec la date formatée */
  const dateLabel = formatDateFr(dateStr);
  title.textContent = capitalize(dateLabel);

  /* Met à jour le badge */
  badge.textContent = rdvs.length;

  /* Vide la liste */
  list.innerHTML = '';

  if (rdvs.length === 0) {
    /* État vide */
    list.innerHTML = '<div class="rdv-empty">Aucun rendez-vous pour ce jour</div>';
    return;
  }

  /* Génère chaque item RDV */
  rdvs.forEach(rdv => {
    const item = document.createElement('div');
    item.className = 'rdv-item';

    /* Calcule l'heure de fin */
    const [h, m] = rdv.heure.split(':').map(Number);   /* Décompose l'heure */
    const endMin  = h * 60 + m + rdv.duree;            /* Total en minutes */
    const endStr  = `${String(Math.floor(endMin / 60)).padStart(2,'0')}:${String(endMin % 60).padStart(2,'0')}`;

    item.innerHTML = `
      <!-- Heure de début et de fin -->
      <div class="rdv-time">
        ${rdv.heure}
        <span>→ ${endStr}</span>
      </div>

      <!-- Barre colorée selon le type -->
      <div class="rdv-bar" style="background:${typeColor(rdv.type)}"></div>

      <!-- Informations patient et type -->
      <div class="rdv-info">
        <div class="rdv-patient-name">${rdv.patientNom}</div>
        <div class="rdv-type-label">${typeLabel(rdv.type)}</div>
      </div>

      <!-- Initiales patient -->
      <div class="rdv-avatar-ph">${initiales(rdv.patientNom)}</div>
    `;

    /* Clic : ouvre le modal d'édition */
    item.addEventListener('click', () => openEditModal(rdv));

    list.appendChild(item);
  });
}


/* ============================================================
   LÉGENDE — Compteurs par type
   ============================================================ */

/**
 * Met à jour les compteurs de la légende (ce mois)
 * Compte les RDV du mois affiché par type
 */
function updateLegendCounts() {
  /* Construit le préfixe YYYY-MM du mois courant */
  const prefix = `${currentYear}-${String(currentMonth + 1).padStart(2,'0')}`;

  /* Filtre les RDV du mois courant */
  const monthRdvs = allRdv.filter(r => r.date.startsWith(prefix));

  /* Compte par type */
  const counts = { consultation: 0, urgence: 0, suivi: 0, tele: 0 };
  monthRdvs.forEach(r => {
    if (counts[r.type] !== undefined) counts[r.type]++;
  });

  /* Met à jour les éléments du DOM */
  document.getElementById('countConsult').textContent = counts.consultation;
  document.getElementById('countUrgence').textContent = counts.urgence;
  document.getElementById('countSuivi').textContent   = counts.suivi;
  document.getElementById('countTele').textContent    = counts.tele;
}


/* ============================================================
   MODAL — Ajouter / Modifier un rendez-vous
   ============================================================ */

/** Ouvre le modal en mode "ajout" */
function openAddModal(dateStr) {
  modalMode  = 'add';
  editingRdv = null;

  /* Titre du modal */
  document.getElementById('modalRdvTitle').textContent = 'Nouveau rendez-vous';

  /* Pré-remplit la date avec le jour sélectionné */
  document.getElementById('rdvDate').value    = dateStr || selectedDate;
  document.getElementById('rdvHeure').value   = '09:00';
  document.getElementById('rdvDuree').value   = '30';
  document.getElementById('rdvMotif').value   = '';
  document.getElementById('rdvPatient').value = '';
  document.getElementById('rdvId').value      = '';

  /* Cache le bouton "Supprimer" */
  document.getElementById('btnDeleteRdv').style.display = 'none';

  /* Désélectionne tous les types */
  document.querySelectorAll('.type-option').forEach(o => {
    o.className = 'type-option';        /* Retire les classes de sélection */
  });

  /* Ouvre le modal */
  document.getElementById('modalRdv').classList.add('open');
}

/**
 * Ouvre le modal en mode "édition" avec les données d'un RDV existant
 * @param {Object} rdv - L'objet rendez-vous à modifier
 */
function openEditModal(rdv) {
  modalMode  = 'edit';
  editingRdv = rdv;

  /* Titre du modal */
  document.getElementById('modalRdvTitle').textContent = 'Modifier le rendez-vous';

  /* Pré-remplit les champs avec les données existantes */
  document.getElementById('rdvDate').value    = rdv.date;
  document.getElementById('rdvHeure').value   = rdv.heure;
  document.getElementById('rdvDuree').value   = String(rdv.duree);
  document.getElementById('rdvMotif').value   = rdv.motif || '';
  document.getElementById('rdvId').value      = rdv.id;

  /* Sélectionne le patient dans le select */
  const selectPat = document.getElementById('rdvPatient');
  for (let opt of selectPat.options) {
    if (parseInt(opt.value) === rdv.patientId) {
      opt.selected = true;
      break;
    }
  }

  /* Sélectionne le type */
  document.querySelectorAll('.type-option').forEach(o => {
    o.className = 'type-option';        /* Reset */
    if (o.dataset.type === rdv.type) {
      o.classList.add(`selected-${rdv.type}`);  /* Active le bon type */
    }
  });

  /* Affiche le bouton "Supprimer" */
  document.getElementById('btnDeleteRdv').style.display = 'block';

  /* Ouvre le modal */
  document.getElementById('modalRdv').classList.add('open');
}

/** Ferme le modal rendez-vous */
function closeModal() {
  document.getElementById('modalRdv').classList.remove('open');
}

/**
 * Sauvegarde un rendez-vous (ajout ou modification)
 * En production : envoie à agenda.php via fetch()
 */
function saveRdv() {
  /* Récupère les valeurs du formulaire */
  const patientSelect = document.getElementById('rdvPatient');
  const patientId     = parseInt(patientSelect.value);
  const patientNom    = patientSelect.options[patientSelect.selectedIndex]?.text || '';
  const date          = document.getElementById('rdvDate').value;
  const heure         = document.getElementById('rdvHeure').value;
  const duree         = parseInt(document.getElementById('rdvDuree').value);
  const motif         = document.getElementById('rdvMotif').value.trim();
  const id            = document.getElementById('rdvId').value;

  /* Récupère le type sélectionné */
  const selectedType = document.querySelector('.type-option[class*="selected-"]');
  const type         = selectedType ? selectedType.dataset.type : null;

  /* ── Validation ── */
  if (!patientId)  { showToast('Veuillez sélectionner un patient', 'error'); return; }
  if (!type)        { showToast('Veuillez choisir un type de rendez-vous', 'error'); return; }
  if (!date)        { showToast('Veuillez choisir une date', 'error'); return; }
  if (!heure)       { showToast('Veuillez choisir une heure', 'error'); return; }

  if (modalMode === 'add') {
    /* ── MODE AJOUT ── */
    const newRdv = {
      id:          nextId++,            /* ID auto-incrémenté */
      patientId,
      patientNom,
      type,
      date,
      heure,
      duree,
      motif
    };
    allRdv.push(newRdv);               /* Ajoute au tableau local */
    showToast('Rendez-vous ajouté avec succès', 'success');

    /*
     * En production, remplacer par :
     * fetch('api/agenda.php', {
     *   method: 'POST',
     *   headers: {'Content-Type': 'application/json'},
     *   body: JSON.stringify(newRdv)
     * }).then(r => r.json()).then(data => { ... });
     */

  } else {
    /* ── MODE ÉDITION ── */
    const index = allRdv.findIndex(r => r.id === parseInt(id));
    if (index !== -1) {
      /* Met à jour les champs */
      allRdv[index] = {
        ...allRdv[index],              /* Conserve les propriétés existantes */
        patientId, patientNom, type, date, heure, duree, motif
      };
    }
    showToast('Rendez-vous modifié avec succès', 'success');
  }

  /* Ferme le modal et re-rend le calendrier */
  closeModal();
  renderCalendar();
  updateLegendCounts();
  renderRdvDay(selectedDate);          /* Rafraîchit le panneau du jour */
}

/**
 * Supprime un rendez-vous après confirmation
 */
function deleteRdv() {
  if (!editingRdv) return;

  /* Demande confirmation */
  if (!confirm(`Supprimer le rendez-vous de ${editingRdv.patientNom} le ${editingRdv.date} à ${editingRdv.heure} ?`)) {
    return;
  }

  /* Retire le RDV du tableau */
  allRdv = allRdv.filter(r => r.id !== editingRdv.id);

  showToast('Rendez-vous supprimé', 'error');
  closeModal();
  renderCalendar();
  updateLegendCounts();
  renderRdvDay(selectedDate);
}


/* ============================================================
   SELECT PATIENTS — Remplit le <select> du modal
   ============================================================ */

/**
 * Remplit le select des patients dans le formulaire modal
 * En production : fetch('api/patients.php?list=1')
 */
function populatePatientSelect() {
  const sel = document.getElementById('rdvPatient');
  sel.innerHTML = '<option value="">— Sélectionner un patient —</option>';  /* Option vide */

  DEMO_PATIENTS.forEach(p => {
    const opt = document.createElement('option');
    opt.value       = p.id;
    opt.textContent = p.nom;
    sel.appendChild(opt);
  });
}


/* ============================================================
   SÉLECTEUR DE TYPE DE RDV (boutons visuels)
   ============================================================ */

/**
 * Initialise les boutons de type de rendez-vous dans le modal
 */
function initTypeSelector() {
  document.querySelectorAll('.type-option').forEach(btn => {
    btn.addEventListener('click', () => {
      /* Désélectionne tous */
      document.querySelectorAll('.type-option').forEach(o => {
        o.className = 'type-option';
      });
      /* Sélectionne celui cliqué */
      btn.classList.add(`selected-${btn.dataset.type}`);
    });
  });
}


/* ============================================================
   SÉLECTEUR DE VUE (Mois / Semaine / Jour)
   ============================================================ */

/**
 * Initialise les boutons de vue du calendrier
 */
function initViewSwitcher() {
  document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      /* Désactive tous */
      document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
      /* Active celui cliqué */
      btn.classList.add('active');
      currentView = btn.dataset.view;

      /* En production : switcher entre les vues month/week/day */
      /* Pour l'instant on reste sur la vue mois et on montre un toast */
      if (currentView !== 'month') {
        showToast(`Vue "${btn.textContent}" — implémentation complète via API`, 'success');
      }
    });
  });
}


/* ============================================================
   TOAST DE NOTIFICATION
   ============================================================ */

/** Timer du toast (pour annuler si nouveau toast avant expiration) */
let toastTimer = null;

/**
 * Affiche un toast de notification
 * @param {string} message - Texte à afficher
 * @param {string} [type='success'] - 'success' | 'error' | ''
 */
function showToast(message, type = 'success') {
  const toast = document.getElementById('toast');

  /* Icône selon le type */
  const icon = type === 'success'
    ? '<svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>'
    : '<svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

  toast.innerHTML  = icon + message;
  toast.className  = `toast ${type} show`;   /* Ajoute 'show' pour l'animation */

  /* Annule le timer précédent si actif */
  if (toastTimer) clearTimeout(toastTimer);

  /* Cache le toast après 3 secondes */
  toastTimer = setTimeout(() => {
    toast.className = 'toast';               /* Retire 'show' → disparaît */
  }, 3000);
}


/* ============================================================
   NAVIGATION MOIS (Précédent / Suivant)
   ============================================================ */

/** Mois précédent */
function prevMonth() {
  if (currentMonth === 0) {
    currentMonth = 11;                /* Décembre */
    currentYear--;                    /* Année précédente */
  } else {
    currentMonth--;
  }
  renderCalendar();
  updateLegendCounts();
}

/** Mois suivant */
function nextMonth() {
  if (currentMonth === 11) {
    currentMonth = 0;                 /* Janvier */
    currentYear++;                    /* Année suivante */
  } else {
    currentMonth++;
  }
  renderCalendar();
  updateLegendCounts();
}

/** Retour à aujourd'hui */
function goToday() {
  const now     = new Date();
  currentYear   = now.getFullYear();
  currentMonth  = now.getMonth();
  selectedDate  = todayISO();
  renderCalendar();
  updateLegendCounts();
  renderRdvDay(selectedDate);
}


/* ============================================================
   API PHP — Fonctions de communication (à activer en production)
   ============================================================ */

/**
 * Charge les rendez-vous depuis le backend PHP
 * @param {number} year - Année
 * @param {number} month - Mois (1-12)
 */
async function fetchRdvFromApi(year, month) {
  /*
   * En production, décommenter :
   * try {
   *   const res  = await fetch(`api/agenda.php?year=${year}&month=${month}`);
   *   const data = await res.json();
   *   if (data.success) {
   *     allRdv = data.rdv;   // Remplace les données locales
   *     renderCalendar();
   *     updateLegendCounts();
   *     renderRdvDay(selectedDate);
   *   }
   * } catch (err) {
   *   showToast('Erreur de chargement des RDV', 'error');
   * }
   */
  console.log('[Agenda] fetchRdvFromApi — mode démo actif');  /* Log développement */
}


/* ============================================================
   INITIALISATION AU CHARGEMENT DE LA PAGE
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ── Remplit le select des patients ── */
  populatePatientSelect();

  /* ── Initialise les types de RDV ── */
  initTypeSelector();

  /* ── Initialise le sélecteur de vue ── */
  initViewSwitcher();

  /* ── Rend le calendrier initial ── */
  renderCalendar();
  updateLegendCounts();

  /* ── Affiche les RDV d'aujourd'hui par défaut ── */
  renderRdvDay(selectedDate);

  /* ── Boutons navigation mois ── */
  document.getElementById('prevMonth').addEventListener('click', prevMonth);
  document.getElementById('nextMonth').addEventListener('click', nextMonth);
  document.getElementById('btnToday').addEventListener('click', goToday);

  /* ── Boutons mini calendrier ── */
  document.getElementById('miniPrev').addEventListener('click', prevMonth);
  document.getElementById('miniNext').addEventListener('click', nextMonth);

  /* ── Bouton "Nouveau rendez-vous" ── */
  document.getElementById('btnAddRdv').addEventListener('click', () => {
    openAddModal(selectedDate);         /* Ouvre modal avec la date sélectionnée */
  });

  /* ── Fermeture du modal ── */
  document.getElementById('closeModalRdv').addEventListener('click', closeModal);
  document.getElementById('cancelModalRdv').addEventListener('click', closeModal);

  /* Ferme le modal en cliquant sur l'overlay (fond sombre) */
  document.getElementById('modalRdv').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) closeModal();   /* Seulement si clic direct sur l'overlay */
  });

  /* ── Bouton Enregistrer ── */
  document.getElementById('saveRdv').addEventListener('click', saveRdv);

  /* ── Bouton Supprimer ── */
  document.getElementById('btnDeleteRdv').addEventListener('click', deleteRdv);

  /* ── Raccourci clavier : Escape ferme le modal ── */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  /* ── (Production) Charge les données depuis PHP ── */
  /* fetchRdvFromApi(currentYear, currentMonth + 1); */

  console.log('[Agenda] Initialisé avec', allRdv.length, 'rendez-vous démo');
});