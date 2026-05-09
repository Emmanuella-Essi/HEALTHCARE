/* ============================================================
   DASHBOARD MÉDECIN — JavaScript
   Fichier     : dashboard-medecin.js
   Description : Toute l'interactivité du tableau de bord médecin
                 Graphiques, données, événements, animations
   ============================================================ */

"use strict";  // Mode strict JavaScript pour éviter les erreurs silencieuses


/* ============================================================
   1. DONNÉES STATIQUES DE DÉMONSTRATION
   (En production, ces données viendraient du PHP/API)
   ============================================================ */

/**
 * Liste des 5 patients récents du médecin
 * En production → fetch('api/patients.php?recent=5')
 */
const PATIENTS_RECENTS = [
  { id: 1, initiales: "KA", nom: "Konan Adjoua",   age: "34 ans", detail: "Dernière visite : 02 Mai",  statut: "stable",  couleur: "#4A7FA7" },
  { id: 2, initiales: "DM", nom: "Diallo Moussa",  age: "52 ans", detail: "Dernière visite : 01 Mai",  statut: "suivi",   couleur: "#0A8C74" },
  { id: 3, initiales: "YK", nom: "Yao Kouadio",    age: "27 ans", detail: "Dernière visite : 29 Avr",  statut: "stable",  couleur: "#6366F1" },
  { id: 4, initiales: "NK", nom: "N'Goran Koffi",  age: "61 ans", detail: "Dernière visite : 28 Avr",  statut: "risque",  couleur: "#F59E0B" },
  { id: 5, initiales: "BA", nom: "Bamba Aïcha",    age: "19 ans", detail: "Nouvelle patiente",         statut: "stable",  couleur: "#EC4899" }
];

/**
 * Données du graphique d'activité (7 derniers jours)
 * labels = jours, consults = nb consultations, nouveaux = nouveaux patients
 */
const ACTIVITE_DATA = {
  labels:    ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
  consults:  [5, 8, 6, 9, 7, 4, 3],   // Consultations par jour
  nouveaux:  [1, 2, 1, 3, 2, 1, 0]    // Nouveaux patients par jour
};

/**
 * Consultations en attente de traitement
 */
const CONSULTATIONS = [
  { id: 1, initiales: "KA", nom: "Konan Adjoua",  couleur: "#4A7FA7", motif: "Douleurs abdominales",   heure: "09h00",  statut: "attente"  },
  { id: 2, initiales: "DM", nom: "Diallo Moussa", couleur: "#0A8C74", motif: "Suivi tension artérielle", heure: "11h30", statut: "attente"  },
  { id: 3, initiales: "TF", nom: "Touré Fatou",   couleur: "#6366F1", motif: "Résultats analyse",       heure: "14h00",  statut: "attente"  },
  { id: 4, initiales: "NK", nom: "N'Goran Koffi", couleur: "#F59E0B", motif: "Consultation urgente",    heure: "15h30",  statut: "acceptee" }
];

/**
 * Messages patients récents non traités
 */
const MESSAGES = [
  { id: 1, initiales: "KA", nom: "Konan Adjoua",  couleur: "#4A7FA7", preview: "Bonjour Dr, j'ai toujours la douleur...", heure: "09:24", nonLus: 2 },
  { id: 2, initiales: "YK", nom: "Yao Kouadio",   couleur: "#6366F1", preview: "Merci pour l'ordonnance docteur",         heure: "08:51", nonLus: 0 },
  { id: 3, initiales: "DM", nom: "Diallo Moussa", couleur: "#0A8C74", preview: "Mes résultats sont disponibles ?",        heure: "Hier",  nonLus: 1 },
  { id: 4, initiales: "TF", nom: "Touré Fatou",   couleur: "#6366F1", preview: "J'ai une question sur les médicaments",   heure: "Hier",  nonLus: 3 }
];

/**
 * Vaccins en retard à planifier en urgence
 */
const VACCINS_RETARD = [
  { id: 1, vaccin: "Rappel DTP",        patient: "N'Goran Koffi", retard: "+15 jours" },
  { id: 2, vaccin: "Vaccin Hépatite B", patient: "Bamba Aïcha",   retard: "+8 jours"  },
  { id: 3, vaccin: "Rappel ROR",        patient: "Soro Ibrahima", retard: "+22 jours" },
  { id: 4, vaccin: "Vaccin Fièvre J.",  patient: "Konan Adjoua",  retard: "+3 jours"  }
];

/**
 * Alertes médicales actives
 */
const ALERTES = [
  { type: "danger",  icon: "🚨", texte: "N'Goran Koffi — Tension critique (180/110)",    temps: "il y a 12 min" },
  { type: "warning", icon: "⚠️", texte: "4 vaccins en retard nécessitent un rappel",      temps: "il y a 1h"     },
  { type: "info",    icon: "💬", texte: "7 messages patients non lus",                    temps: "il y a 2h"     },
  { type: "warning", icon: "📋", texte: "3 consultations en attente de confirmation",     temps: "il y a 3h"     },
  { type: "danger",  icon: "💊", texte: "Bamba Aïcha — Allergie pénicilline à noter",     temps: "Hier"          }
];

/**
 * Jours avec rendez-vous pour le calendrier (indices dans le mois)
 * En production → fetch depuis l'agenda en BDD
 */
const JOURS_RDV = [2, 5, 7, 12, 14, 18, 22, 26, 28];


/* ============================================================
   2. SÉLECTION DES ÉLÉMENTS DOM
   Toutes les références DOM au même endroit
   ============================================================ */

const DOM = {
  sidebar:        document.getElementById("sidebar"),          // Sidebar navigation
  menuToggle:     document.getElementById("menuToggle"),       // Bouton hamburger mobile
  sidebarOverlay: document.getElementById("sidebarOverlay"),   // Overlay sombre mobile
  currentDate:    document.getElementById("currentDate"),      // Affichage date topbar
  globalSearch:   document.getElementById("globalSearch"),     // Input recherche globale
  alertBtn:       document.getElementById("alertBtn"),         // Bouton alertes

  patientList:    document.getElementById("patientList"),      // UL patients récents
  consultBody:    document.getElementById("consultBody"),      // tbody consultations
  alertsList:     document.getElementById("alertsList"),       // UL alertes
  messagesList:   document.getElementById("messagesList"),     // UL messages
  vaccinesList:   document.getElementById("vaccinesList"),     // UL vaccins

  calendarGrid:   document.getElementById("calendarGrid"),     // Grille calendrier
  calMonthLabel:  document.getElementById("calMonthLabel"),    // Label mois calendrier
  prevMonth:      document.getElementById("prevMonth"),        // Bouton mois précédent
  nextMonth:      document.getElementById("nextMonth"),        // Bouton mois suivant

  activityChart:  document.getElementById("activityChart"),    // Canvas graphique activité
  donutChart:     document.getElementById("donutChart"),       // Canvas graphique donut

  toast:    document.getElementById("toast"),    // Toast notification
  toastMsg: document.getElementById("toastMsg"), // Texte du toast

  pendingCount: document.getElementById("pendingCount"), // Nb consultations en attente
  unreadCount:  document.getElementById("unreadCount"),  // Nb messages non lus
  alertsCount:  document.getElementById("alertsCount"),  // Nb alertes totales
};


/* ============================================================
   3. INITIALISATION PRINCIPALE
   Lancée au chargement complet du DOM
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {
  // Appliquer les valeurs injectees par le PHP quand la page est servie en .php
  appliquerDonneesPhp();

  // Afficher la date du jour dans la topbar
  afficherDateJour();

  // Animer les compteurs KPI (0 → valeur cible)
  animerCompteurs();

  // Rendre la liste des patients récents
  renderPatients();

  // Rendre le tableau des consultations
  renderConsultations();

  // Rendre les alertes
  renderAlertes();

  // Rendre les messages
  renderMessages();

  // Rendre les vaccins en retard
  renderVaccins();

  // Dessiner le calendrier mini
  initCalendrier();

  // Dessiner le graphique d'activité (barres)
  dessinerGraphiqueActivite();

  // Dessiner le graphique donut (santé)
  dessinerDonut();

  // Attacher les événements interactifs
  attacherEvenements();

  // Mettre à jour les compteurs dans la bannière
  mettreAJourCompteursBanniere();
});


/* ============================================================
   3.b DONNEES PHP OPTIONNELLES
   ============================================================ */

/**
 * Remplace les valeurs statiques par les donnees injectees par le controleur PHP.
 */
function appliquerDonneesPhp() {
  const stats = window.PHP_STATS;
  const medecin = window.PHP_MEDECIN;

  if (stats) {
    const kpiValues = document.querySelectorAll(".kpi-value[data-target]");
    const values = [
      stats.nb_patients,
      stats.consultations_jour,
      stats.messages_non_lus,
      stats.vaccins_en_retard
    ];

    kpiValues.forEach((el, index) => {
      if (values[index] !== undefined && values[index] !== null) {
        el.dataset.target = String(values[index]);
      }
    });

    if (DOM.pendingCount && stats.en_attente !== undefined) {
      DOM.pendingCount.textContent = stats.en_attente;
    }
    if (DOM.unreadCount && stats.messages_non_lus !== undefined) {
      DOM.unreadCount.textContent = stats.messages_non_lus;
    }
  }

  if (medecin) {
    const nomComplet = `${medecin.prenom || ""} ${medecin.nom || ""}`.trim();
    const specialite = medecin.specialite || "";

    document.querySelectorAll(".doctor-name").forEach((el) => {
      el.textContent = nomComplet ? `Dr. ${nomComplet}` : "Dr.";
    });
    document.querySelectorAll(".doctor-spec").forEach((el) => {
      el.textContent = specialite;
    });

    const welcomeTitle = document.querySelector(".welcome-title");
    if (welcomeTitle && nomComplet) {
      welcomeTitle.textContent = `Dr. ${nomComplet}`;
    }
  }
}


/* ============================================================
   4. AFFICHAGE DATE DU JOUR
   ============================================================ */

/**
 * Affiche la date complète en français dans la topbar
 * Ex: "Jeudi 7 mai 2025"
 */
function afficherDateJour() {
  const maintenant = new Date();

  // Options de formatage date en français
  const options = {
    weekday: "long",
    day:     "numeric",
    month:   "long",
    year:    "numeric"
  };

  // Formatage avec l'API Intl.DateTimeFormat
  const dateFormatee = maintenant.toLocaleDateString("fr-FR", options);

  // Première lettre en majuscule
  DOM.currentDate.textContent = dateFormatee.charAt(0).toUpperCase() + dateFormatee.slice(1);
}


/* ============================================================
   5. ANIMATION DES COMPTEURS KPI
   ============================================================ */

/**
 * Anime chaque .kpi-value de 0 jusqu'à sa valeur cible (data-target)
 * Donne un effet "compteur qui monte"
 */
function animerCompteurs() {
  // Sélectionner tous les éléments avec data-target
  const elements = document.querySelectorAll(".kpi-value[data-target]");

  elements.forEach((el) => {
    const cible = parseInt(el.dataset.target, 10);  // Valeur finale
    const duree = 1200;  // Durée animation en ms
    const debut = performance.now();  // Timestamp de départ

    /**
     * Fonction récursive d'animation frame par frame
     * @param {number} maintenant - Timestamp actuel
     */
    function step(maintenant) {
      const elapsed = maintenant - debut;           // Temps écoulé
      const progress = Math.min(elapsed / duree, 1); // Progression 0→1

      // Fonction easing "easeOutQuart" pour ralentir en fin d'animation
      const eased = 1 - Math.pow(1 - progress, 4);

      // Mettre à jour la valeur affichée (arrondie)
      el.textContent = Math.round(cible * eased);

      // Continuer si pas encore à 100%
      if (progress < 1) requestAnimationFrame(step);
    }

    // Lancer l'animation après un délai (effet cascade)
    setTimeout(() => requestAnimationFrame(step), 300);
  });
}


/* ============================================================
   6. RENDU LISTE PATIENTS RÉCENTS
   ============================================================ */

/**
 * Génère le HTML des 5 patients récents et l'injecte dans le DOM
 * Chaque patient est un <li> cliquable
 */
function renderPatients() {
  if (!DOM.patientList) return;

  // Map des libellés de statut
  const statutLabels = {
    stable: "Stable",
    suivi:  "En suivi",
    risque: "À risque",
    urgent: "Urgent"
  };

  // Générer un li pour chaque patient
  DOM.patientList.innerHTML = PATIENTS_RECENTS.map((p) => `
    <li class="patient-item" onclick="naviguerPatient(${p.id})" role="button" tabindex="0">
      <!-- Avatar coloré avec initiales -->
      <div class="pat-avatar" style="background: ${p.couleur};">
        ${p.initiales}
      </div>
      <!-- Informations textuelles -->
      <div class="pat-info">
        <span class="pat-name">${p.nom}</span>
        <span class="pat-detail">${p.age} — ${p.detail}</span>
      </div>
      <!-- Badge statut de santé -->
      <span class="pat-status ${p.statut}">${statutLabels[p.statut]}</span>
    </li>
  `).join("");  // join("") pour éviter les virgules entre éléments
}

/**
 * Navigation vers la fiche d'un patient (simulé ici)
 * @param {number} id - Identifiant du patient
 */
function naviguerPatient(id) {
  // En production : window.location.href = `patient-fiche.php?id=${id}`;
  afficherToast(`Ouverture du dossier patient #${id}`);
}

// Exposer à l'espace global (utilisé dans onclick inline)
window.naviguerPatient = naviguerPatient;


/* ============================================================
   7. RENDU TABLEAU CONSULTATIONS
   ============================================================ */

/**
 * Génère les lignes du tableau des consultations en attente
 * Chaque ligne a des boutons Accepter / Refuser / Voir
 */
function renderConsultations() {
  if (!DOM.consultBody) return;

  // Map des libellés de statut
  const statutLabels = {
    attente:  "En attente",
    acceptee: "Acceptée",
    refusee:  "Refusée"
  };

  DOM.consultBody.innerHTML = CONSULTATIONS.map((c) => `
    <tr id="consult-row-${c.id}">
      <!-- Colonne patient : avatar + nom -->
      <td>
        <div class="tbl-patient">
          <div class="tbl-avatar" style="background: ${c.couleur};">${c.initiales}</div>
          <span class="tbl-name">${c.nom}</span>
        </div>
      </td>
      <!-- Colonne motif -->
      <td>${c.motif}</td>
      <!-- Colonne heure demandée -->
      <td>${c.heure}</td>
      <!-- Colonne statut avec badge coloré -->
      <td>
        <span class="tbl-badge ${c.statut}" id="statut-${c.id}">
          ${statutLabels[c.statut]}
        </span>
      </td>
      <!-- Colonne actions -->
      <td>
        <div class="tbl-actions">
          ${c.statut === "attente" ? `
            <button class="tbl-btn accept" onclick="accepterConsultation(${c.id})" title="Accepter">✓ Accepter</button>
            <button class="tbl-btn refuse" onclick="refuserConsultation(${c.id})" title="Refuser">✗ Refuser</button>
          ` : ""}
          <button class="tbl-btn view" onclick="voirConsultation(${c.id})" title="Voir détails">Voir</button>
        </div>
      </td>
    </tr>
  `).join("");
}

/**
 * Accepter une consultation (met à jour le statut visuellement)
 * @param {number} id - ID de la consultation
 */
function accepterConsultation(id) {
  const badgeEl = document.getElementById(`statut-${id}`);
  const row = document.getElementById(`consult-row-${id}`);

  if (badgeEl) {
    // Mettre à jour le badge visuellement
    badgeEl.className = "tbl-badge acceptee";
    badgeEl.textContent = "Acceptée";
  }

  if (row) {
    // Supprimer les boutons accepter/refuser (garder seulement "Voir")
    const actions = row.querySelector(".tbl-actions");
    if (actions) {
      const btnAccept = actions.querySelector(".accept");
      const btnRefuse = actions.querySelector(".refuse");
      if (btnAccept) btnAccept.remove();
      if (btnRefuse) btnRefuse.remove();
    }
  }

  afficherToast("✅ Consultation acceptée avec succès !");
}

/**
 * Refuser une consultation
 * @param {number} id - ID de la consultation
 */
function refuserConsultation(id) {
  const badgeEl = document.getElementById(`statut-${id}`);
  const row = document.getElementById(`consult-row-${id}`);

  if (badgeEl) {
    badgeEl.className = "tbl-badge refusee";
    badgeEl.textContent = "Refusée";
  }

  if (row) {
    const actions = row.querySelector(".tbl-actions");
    if (actions) {
      const btnAccept = actions.querySelector(".accept");
      const btnRefuse = actions.querySelector(".refuse");
      if (btnAccept) btnAccept.remove();
      if (btnRefuse) btnRefuse.remove();
    }
  }

  afficherToast("❌ Consultation refusée.");
}

/**
 * Voir les détails d'une consultation
 * @param {number} id - ID de la consultation
 */
function voirConsultation(id) {
  afficherToast(`📋 Ouverture consultation #${id}...`);
  // En production : window.location.href = `consultations.php?id=${id}`;
}

// Exposer ces fonctions globalement (utilisées dans onclick inline du HTML généré)
window.accepterConsultation = accepterConsultation;
window.refuserConsultation  = refuserConsultation;
window.voirConsultation     = voirConsultation;


/* ============================================================
   8. RENDU ALERTES
   ============================================================ */

/**
 * Génère la liste des alertes dans le panneau latéral
 */
function renderAlertes() {
  if (!DOM.alertsList) return;

  DOM.alertsList.innerHTML = ALERTES.map((a) => `
    <li class="alert-item ${a.type}">
      <span class="alert-icon">${a.icon}</span>
      <div>
        <span class="alert-text">${a.texte}</span>
        <span class="alert-time">${a.temps}</span>
      </div>
    </li>
  `).join("");

  // Mettre à jour le badge nombre d'alertes
  if (DOM.alertsCount) {
    DOM.alertsCount.textContent = ALERTES.length;
  }
}


/* ============================================================
   9. RENDU MESSAGES RÉCENTS
   ============================================================ */

/**
 * Génère la liste des derniers messages patients
 */
function renderMessages() {
  if (!DOM.messagesList) return;

  DOM.messagesList.innerHTML = MESSAGES.map((m) => `
    <li class="msg-item" onclick="ouvrirMessage(${m.id})" role="button" tabindex="0">
      <!-- Avatar patient expéditeur -->
      <div class="msg-avatar" style="background: ${m.couleur};">${m.initiales}</div>
      <!-- Corps du message -->
      <div class="msg-body">
        <span class="msg-name">${m.nom}</span>
        <span class="msg-preview">${m.preview}</span>
      </div>
      <!-- Heure + badge non lus -->
      <div class="msg-meta">
        <span class="msg-time">${m.heure}</span>
        ${m.nonLus > 0 ? `<span class="msg-unread">${m.nonLus}</span>` : ""}
      </div>
    </li>
  `).join("");
}

/**
 * Ouvrir un message (navigation vers messagerie)
 * @param {number} id - ID du message/conversation
 */
function ouvrirMessage(id) {
  afficherToast(`💬 Ouverture conversation #${id}`);
  // En production : window.location.href = `messagerie.php?conv=${id}`;
}
window.ouvrirMessage = ouvrirMessage;


/* ============================================================
   10. RENDU VACCINS EN RETARD
   ============================================================ */

/**
 * Génère la liste des vaccins en retard
 */
function renderVaccins() {
  if (!DOM.vaccinesList) return;

  DOM.vaccinesList.innerHTML = VACCINS_RETARD.map((v) => `
    <li class="vacc-item">
      <span class="vacc-icon">💉</span>
      <div class="vacc-info">
        <span class="vacc-name">${v.vaccin}</span>
        <span class="vacc-patient">${v.patient}</span>
      </div>
      <!-- Badge retard en rouge -->
      <span class="vacc-retard">${v.retard}</span>
    </li>
  `).join("");
}


/* ============================================================
   11. CALENDRIER MINI
   ============================================================ */

// État du calendrier (mois/année actuellement affichés)
let calEtat = {
  annee: new Date().getFullYear(),
  mois:  new Date().getMonth()   // 0 = Janvier, 11 = Décembre
};

/**
 * Noms des mois en français
 */
const MOIS_FR = [
  "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
  "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
];

/**
 * Abréviations des jours de la semaine
 */
const JOURS_FR = ["Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di"];

/**
 * Initialise le calendrier avec le mois courant
 */
function initCalendrier() {
  renderCalendrier();

  // Bouton mois précédent
  if (DOM.prevMonth) {
    DOM.prevMonth.addEventListener("click", () => {
      // Reculer d'un mois
      calEtat.mois--;
      if (calEtat.mois < 0) {
        calEtat.mois = 11;   // Revenir à décembre
        calEtat.annee--;
      }
      renderCalendrier();
    });
  }

  // Bouton mois suivant
  if (DOM.nextMonth) {
    DOM.nextMonth.addEventListener("click", () => {
      // Avancer d'un mois
      calEtat.mois++;
      if (calEtat.mois > 11) {
        calEtat.mois = 0;    // Revenir à janvier
        calEtat.annee++;
      }
      renderCalendrier();
    });
  }
}

/**
 * Dessine la grille du calendrier pour le mois courant
 */
function renderCalendrier() {
  if (!DOM.calendarGrid || !DOM.calMonthLabel) return;

  // Mettre à jour le label mois/année
  DOM.calMonthLabel.textContent = `${MOIS_FR[calEtat.mois]} ${calEtat.annee}`;

  // Vider la grille
  DOM.calendarGrid.innerHTML = "";

  // --- En-têtes des jours de semaine ---
  JOURS_FR.forEach((j) => {
    const header = document.createElement("div");
    header.className = "cal-day header";
    header.textContent = j;
    DOM.calendarGrid.appendChild(header);
  });

  // --- Calcul des jours du mois ---
  const premierJour = new Date(calEtat.annee, calEtat.mois, 1).getDay();
  // getDay() : 0=Dimanche, 1=Lundi... → adapter pour semaine commençant lundi
  const offset = (premierJour === 0) ? 6 : premierJour - 1;

  const nbJours = new Date(calEtat.annee, calEtat.mois + 1, 0).getDate();
  const today = new Date();
  const estMoisActuel = (calEtat.annee === today.getFullYear() && calEtat.mois === today.getMonth());

  // --- Cellules vides avant le 1er (jours du mois précédent) ---
  for (let i = 0; i < offset; i++) {
    const vide = document.createElement("div");
    vide.className = "cal-day other-month";
    vide.textContent = ""; // Vide ou numéro du mois précédent
    DOM.calendarGrid.appendChild(vide);
  }

  // --- Cellules des jours du mois ---
  for (let d = 1; d <= nbJours; d++) {
    const cellule = document.createElement("div");
    cellule.className = "cal-day";
    cellule.textContent = d;

    // Marquer aujourd'hui
    if (estMoisActuel && d === today.getDate()) {
      cellule.classList.add("today");
    }

    // Marquer les jours avec RDV
    if (JOURS_RDV.includes(d)) {
      cellule.classList.add("has-rdv");
    }

    // Clic sur un jour = afficher les RDV de ce jour
    cellule.addEventListener("click", () => selectionnerJour(d, calEtat.mois, calEtat.annee));

    DOM.calendarGrid.appendChild(cellule);
  }
}

/**
 * Action lors du clic sur un jour du calendrier
 * @param {number} jour  - Numéro du jour
 * @param {number} mois  - Index du mois (0-11)
 * @param {number} annee - Année
 */
function selectionnerJour(jour, mois, annee) {
  const dateStr = `${jour} ${MOIS_FR[mois]} ${annee}`;
  afficherToast(`📅 Rendez-vous du ${dateStr}`);
  // En production : charger les RDV de ce jour via API
}


/* ============================================================
   12. GRAPHIQUE D'ACTIVITÉ (BARRES) — Canvas natif
   ============================================================ */

/**
 * Dessine le graphique à barres de l'activité patients
 * Utilise l'API Canvas HTML5 (sans bibliothèque externe)
 */
function dessinerGraphiqueActivite() {
  const canvas = DOM.activityChart;
  if (!canvas) return;

  const ctx = canvas.getContext("2d");
  if (!ctx) return;

  // Dimensions réelles du canvas
  const W = canvas.offsetWidth || 400;
  const H = 180;
  canvas.width  = W;
  canvas.height = H;

  // Configuration visuelle
  const couleurConsult = "#4A7FA7";     // Bleu accent
  const couleurNouv    = "#0A8C74";     // Teal
  const couleurTexte   = "#9CA3AF";     // Gris clair
  const couleurGrille  = "#E4EEF7";     // Bordure légère

  // Marges internes
  const margeGauche  = 30;
  const margeDroite  = 15;
  const margeHaut    = 15;
  const margeBas     = 30;

  // Zone de dessin
  const zoneW = W - margeGauche - margeDroite;
  const zoneH = H - margeHaut  - margeBas;

  const nbJours = ACTIVITE_DATA.labels.length;
  const largeurGroupe = zoneW / nbJours;  // Largeur par groupe (2 barres)
  const largeurBarre  = largeurGroupe * 0.28;  // Largeur d'une barre
  const espacement    = largeurBarre * 0.3;    // Espace entre les 2 barres

  // Valeur maximum pour normaliser les hauteurs
  const maxVal = Math.max(...ACTIVITE_DATA.consults, ...ACTIVITE_DATA.nouveaux);

  // Effacer le canvas
  ctx.clearRect(0, 0, W, H);

  // --- Lignes de grille horizontales ---
  ctx.strokeStyle = couleurGrille;
  ctx.lineWidth   = 1;

  const nbLignes = 4;
  for (let i = 0; i <= nbLignes; i++) {
    const y = margeHaut + (zoneH / nbLignes) * i;
    ctx.beginPath();
    ctx.moveTo(margeGauche, y);
    ctx.lineTo(W - margeDroite, y);
    ctx.stroke();

    // Valeur de l'axe Y
    const valeurY = Math.round(maxVal - (maxVal / nbLignes) * i);
    ctx.fillStyle = couleurTexte;
    ctx.font      = "10px DM Sans, sans-serif";
    ctx.textAlign = "right";
    ctx.fillText(valeurY, margeGauche - 4, y + 4);
  }

  // --- Barres et labels ---
  ACTIVITE_DATA.labels.forEach((label, i) => {
    const x = margeGauche + i * largeurGroupe + largeurGroupe / 2;

    // ----- Barre 1 : Consultations -----
    const valConsult = ACTIVITE_DATA.consults[i];
    const hautConsult = (valConsult / maxVal) * zoneH;
    const xConsult = x - largeurBarre - espacement / 2;
    const yConsult = margeHaut + zoneH - hautConsult;

    // Couleur avec dégradé vertical
    const gradConsult = ctx.createLinearGradient(0, yConsult, 0, margeHaut + zoneH);
    gradConsult.addColorStop(0, couleurConsult);
    gradConsult.addColorStop(1, "rgba(74,127,167,0.25)");

    ctx.fillStyle   = gradConsult;
    ctx.beginPath();
    // Rectangle avec coins arrondis en haut
    arrondirHaut(ctx, xConsult, yConsult, largeurBarre, hautConsult, 4);
    ctx.fill();

    // ----- Barre 2 : Nouveaux patients -----
    const valNouv = ACTIVITE_DATA.nouveaux[i];
    const hautNouv = (valNouv / maxVal) * zoneH;
    const xNouv = x + espacement / 2;
    const yNouv = margeHaut + zoneH - hautNouv;

    const gradNouv = ctx.createLinearGradient(0, yNouv, 0, margeHaut + zoneH);
    gradNouv.addColorStop(0, couleurNouv);
    gradNouv.addColorStop(1, "rgba(10,140,116,0.2)");

    ctx.fillStyle = gradNouv;
    ctx.beginPath();
    arrondirHaut(ctx, xNouv, yNouv, largeurBarre, hautNouv, 4);
    ctx.fill();

    // ----- Label jour en bas -----
    ctx.fillStyle = couleurTexte;
    ctx.font      = "10px DM Sans, sans-serif";
    ctx.textAlign = "center";
    ctx.fillText(label, x, H - 8);
  });
}

/**
 * Dessine un rectangle avec seulement les coins supérieurs arrondis
 * @param {CanvasRenderingContext2D} ctx
 * @param {number} x      - Position X
 * @param {number} y      - Position Y (haut)
 * @param {number} w      - Largeur
 * @param {number} h      - Hauteur
 * @param {number} rayon  - Rayon des coins
 */
function arrondirHaut(ctx, x, y, w, h, rayon) {
  const r = Math.min(rayon, h / 2, w / 2);
  ctx.moveTo(x + r, y);
  ctx.lineTo(x + w - r, y);
  ctx.quadraticCurveTo(x + w, y, x + w, y + r);  // Coin haut-droit arrondi
  ctx.lineTo(x + w, y + h);
  ctx.lineTo(x, y + h);
  ctx.lineTo(x, y + r);
  ctx.quadraticCurveTo(x, y, x + r, y);           // Coin haut-gauche arrondi
  ctx.closePath();
}


/* ============================================================
   13. GRAPHIQUE DONUT — Canvas natif
   ============================================================ */

/**
 * Dessine le graphique en anneau (donut) de répartition santé
 */
function dessinerDonut() {
  const canvas = DOM.donutChart;
  if (!canvas) return;

  const ctx = canvas.getContext("2d");
  if (!ctx) return;

  const taille = 160;
  canvas.width  = taille;
  canvas.height = taille;

  const cx = taille / 2;  // Centre X
  const cy = taille / 2;  // Centre Y
  const rayonExt  = 68;   // Rayon extérieur
  const rayonInt  = 45;   // Rayon intérieur (le trou)
  const epaisseur = rayonExt - rayonInt;

  // Données du donut : [valeur, couleur, label]
  const segments = [
    { valeur: 78, couleur: "#4A7FA7", label: "Stables" },   // 78% stables
    { valeur: 14, couleur: "#0A8C74", label: "En suivi" },  // 14% en suivi
    { valeur: 8,  couleur: "#F59E0B", label: "À risque" }   // 8% à risque
  ];

  const total = segments.reduce((s, x) => s + x.valeur, 0);  // Toujours 100
  let angleDebut = -Math.PI / 2;  // Commencer en haut (12h)

  // --- Dessin de chaque segment ---
  segments.forEach((seg) => {
    const angleFin = angleDebut + (seg.valeur / total) * 2 * Math.PI;

    // Arc extérieur
    ctx.beginPath();
    ctx.arc(cx, cy, rayonExt, angleDebut, angleFin);
    ctx.arc(cx, cy, rayonInt, angleFin, angleDebut, true); // Arc intérieur inversé
    ctx.closePath();

    ctx.fillStyle = seg.couleur;
    ctx.fill();

    // Séparateur blanc entre segments
    ctx.strokeStyle = "#FFFFFF";
    ctx.lineWidth   = 2;
    ctx.stroke();

    angleDebut = angleFin;  // L'angle de fin devient le prochain angle de début
  });

  // --- Cercle central blanc (crée l'effet "donut") ---
  ctx.beginPath();
  ctx.arc(cx, cy, rayonInt - 2, 0, 2 * Math.PI);
  ctx.fillStyle = "#FFFFFF";
  ctx.fill();
}


/* ============================================================
   14. MISE À JOUR COMPTEURS BANNIÈRE
   ============================================================ */

/**
 * Met à jour les chiffres affichés dans la bannière de bienvenue
 */
function mettreAJourCompteursBanniere() {
  if (window.PHP_STATS) {
    if (DOM.pendingCount && window.PHP_STATS.en_attente !== undefined) {
      DOM.pendingCount.textContent = window.PHP_STATS.en_attente;
    }
    if (DOM.unreadCount && window.PHP_STATS.messages_non_lus !== undefined) {
      DOM.unreadCount.textContent = window.PHP_STATS.messages_non_lus;
    }
    return;
  }

  // Compter consultations en attente
  const nbAttente = CONSULTATIONS.filter(c => c.statut === "attente").length;
  if (DOM.pendingCount) DOM.pendingCount.textContent = nbAttente;

  // Compter messages non lus
  const nbNonLus = MESSAGES.reduce((acc, m) => acc + m.nonLus, 0);
  if (DOM.unreadCount) DOM.unreadCount.textContent = nbNonLus;
}


/* ============================================================
   15. ÉVÉNEMENTS INTERACTIFS
   ============================================================ */

/**
 * Attache tous les écouteurs d'événements
 */
function attacherEvenements() {
  // ---- Sidebar mobile : ouverture/fermeture ----
  if (DOM.menuToggle && DOM.sidebar && DOM.sidebar.dataset.sharedSidebar !== "true") {
    DOM.menuToggle.addEventListener("click", () => {
      DOM.sidebar.classList.toggle("open");
      DOM.sidebarOverlay.classList.toggle("show");
    });
  }

  // Fermer la sidebar en cliquant sur l'overlay
  if (DOM.sidebarOverlay && DOM.sidebar && DOM.sidebar.dataset.sharedSidebar !== "true") {
    DOM.sidebarOverlay.addEventListener("click", () => {
      DOM.sidebar.classList.remove("open");
      DOM.sidebarOverlay.classList.remove("show");
    });
  }

  // ---- Recherche globale ----
  if (DOM.globalSearch) {
    let delaiRecherche;  // Debounce pour éviter trop de requêtes

    DOM.globalSearch.addEventListener("input", (e) => {
      clearTimeout(delaiRecherche);

      delaiRecherche = setTimeout(() => {
        const terme = e.target.value.trim();
        if (terme.length >= 2) {
          rechercherPatient(terme);  // Lancer la recherche
        }
      }, 300);  // Attendre 300ms après la dernière frappe
    });
  }

  // ---- Bouton alertes ----
  if (DOM.alertBtn) {
    DOM.alertBtn.addEventListener("click", () => {
      afficherToast("🔔 Affichage des alertes...");
      // En production : ouvrir un panneau ou naviguer vers alertes.html
    });
  }

  // ---- Sélecteur période du graphique ----
  const periodSelect = document.getElementById("periodSelect");
  if (periodSelect) {
    periodSelect.addEventListener("change", (e) => {
      const periode = e.target.value;
      afficherToast(`📊 Données sur ${periode} jours chargées`);
      // En production : recharger les données via fetch et redessiner
      dessinerGraphiqueActivite();  // Redessiner (simulation)
    });
  }

  // ---- Redimensionnement fenêtre : recalculer les graphiques ----
  window.addEventListener("resize", () => {
    dessinerGraphiqueActivite();  // Les graphiques canvas doivent être redessinés
  });
}


/* ============================================================
   16. RECHERCHE PATIENTS
   ============================================================ */

/**
 * Filtre la liste des patients selon le terme de recherche
 * @param {string} terme - Texte saisi dans la barre de recherche
 */
function rechercherPatient(terme) {
  const termeMaj = terme.toLowerCase();

  // Filtrer sur le nom
  const resultats = PATIENTS_RECENTS.filter(p =>
    p.nom.toLowerCase().includes(termeMaj)
  );

  if (resultats.length > 0) {
    afficherToast(`🔍 ${resultats.length} patient(s) trouvé(s) pour "${terme}"`);
  } else {
    afficherToast(`❌ Aucun patient trouvé pour "${terme}"`);
  }

  // En production : naviguer vers patients.php?q=terme
}


/* ============================================================
   17. TOAST NOTIFICATION
   ============================================================ */

// Timer pour fermer le toast automatiquement
let toastTimer = null;

/**
 * Affiche un toast de notification temporaire
 * @param {string} message - Texte à afficher dans le toast
 * @param {number} duree   - Durée d'affichage en millisecondes (défaut: 3000)
 */
function afficherToast(message, duree = 3000) {
  if (!DOM.toast || !DOM.toastMsg) return;

  // Mettre à jour le message
  DOM.toastMsg.textContent = message;

  // Afficher le toast
  DOM.toast.classList.add("show");

  // Annuler le timer précédent si un toast était déjà affiché
  if (toastTimer) clearTimeout(toastTimer);

  // Fermer automatiquement après la durée
  toastTimer = setTimeout(() => {
    DOM.toast.classList.remove("show");
  }, duree);
}

// Exposer globalement pour usage depuis les onclick inline
window.afficherToast = afficherToast;


/* ============================================================
   18. UTILITAIRES
   ============================================================ */

/**
 * Formate une date en français
 * @param {Date} date - Objet Date à formater
 * @returns {string} - Chaîne formatée "JJ mois AAAA"
 */
function formaterDate(date) {
  return date.toLocaleDateString("fr-FR", {
    day:   "2-digit",
    month: "long",
    year:  "numeric"
  });
}

/**
 * Capitalise la première lettre d'une chaîne
 * @param {string} str
 * @returns {string}
 */
function capitaliser(str) {
  if (!str) return "";
  return str.charAt(0).toUpperCase() + str.slice(1);
}

// ---- FIN DU FICHIER dashboard-medecin.js ----
