/* ============================================================
   consultations.js — Dashboard Médecin : Logique Consultations
   Gestion complète : données, filtres, modals, actions
   ============================================================ */

/* ============================================================
   1. DONNÉES SIMULÉES (remplacer par appels PHP/API)
   ============================================================ */

/* Tableau de toutes les consultations */
const consultations = [
    {
        id: 1,                                      /* Identifiant unique */
        patientNom: "Marie Dupont",                 /* Nom complet patient */
        patientAge: 34,                             /* Age patient */
        patientGroupeSanguin: "A+",                 /* Groupe sanguin */
        date: "2025-01-15",                         /* Date consultation */
        heure: "09:00",                             /* Heure consultation */
        motif: "Douleurs abdominales récurrentes",  /* Motif de la visite */
        type: "presentiel",                         /* presentiel ou teleconsultation */
        statut: "en_attente",                       /* Statut actuel */
        duree: 30,                                  /* Durée en minutes */
        notes: "",                                  /* Notes du médecin */
        urgence: true                               /* Marqueur urgence */
    },
    {
        id: 2,
        patientNom: "Jean Bernard",
        patientAge: 52,
        patientGroupeSanguin: "O+",
        date: "2025-01-15",
        heure: "10:30",
        motif: "Suivi diabète type 2",
        type: "presentiel",
        statut: "confirme",
        duree: 45,
        notes: "Patient suivi depuis 3 ans. HbA1c à contrôler.",
        urgence: false
    },
    {
        id: 3,
        patientNom: "Sophie Leclerc",
        patientAge: 28,
        patientGroupeSanguin: "B+",
        date: "2025-01-15",
        heure: "11:15",
        motif: "Consultation générale - fatigue",
        type: "teleconsultation",
        statut: "en_attente",
        duree: 20,
        notes: "",
        urgence: false
    },
    {
        id: 4,
        patientNom: "Ahmed Karim",
        patientAge: 45,
        patientGroupeSanguin: "AB-",
        date: "2025-01-14",
        heure: "14:00",
        motif: "Renouvellement ordonnance",
        type: "teleconsultation",
        statut: "termine",
        duree: 15,
        notes: "Ordonnance renouvelée. Prochain RDV dans 3 mois.",
        urgence: false
    },
    {
        id: 5,
        patientNom: "Claire Fontaine",
        patientAge: 67,
        patientGroupeSanguin: "A-",
        date: "2025-01-14",
        heure: "15:30",
        motif: "Douleurs articulaires - arthrose",
        type: "presentiel",
        statut: "termine",
        duree: 30,
        notes: "Radiographie recommandée. Anti-inflammatoires prescrits.",
        urgence: false
    },
    {
        id: 6,
        patientNom: "Lucas Martin",
        patientAge: 19,
        patientGroupeSanguin: "O-",
        date: "2025-01-16",
        heure: "08:30",
        motif: "Certificat médical sport",
        type: "presentiel",
        statut: "confirme",
        duree: 20,
        notes: "",
        urgence: false
    },
    {
        id: 7,
        patientNom: "Fatima Ndiaye",
        patientAge: 38,
        patientGroupeSanguin: "B-",
        date: "2025-01-16",
        heure: "09:45",
        motif: "Grossesse - premier trimestre",
        type: "presentiel",
        statut: "en_attente",
        duree: 45,
        notes: "",
        urgence: false
    },
    {
        id: 8,
        patientNom: "Pierre Dubois",
        patientAge: 71,
        patientGroupeSanguin: "A+",
        date: "2025-01-13",
        heure: "11:00",
        motif: "Hypertension - suivi mensuel",
        type: "teleconsultation",
        statut: "annule",
        duree: 20,
        notes: "Patient a annulé. Recontacter pour reprogrammer.",
        urgence: false
    },
    {
        id: 9,
        patientNom: "Amina Traoré",
        patientAge: 42,
        patientGroupeSanguin: "O+",
        date: "2025-01-17",
        heure: "10:00",
        motif: "Migraine chronique - bilan",
        type: "presentiel",
        statut: "en_attente",
        duree: 30,
        notes: "",
        urgence: true
    },
    {
        id: 10,
        patientNom: "Marc Lefèvre",
        patientAge: 55,
        patientGroupeSanguin: "AB+",
        date: "2025-01-17",
        heure: "14:30",
        motif: "Bilan sanguin annuel",
        type: "presentiel",
        statut: "confirme",
        duree: 30,
        notes: "",
        urgence: false
    },
    {
        id: 11,
        patientNom: "Isabelle Moreau",
        patientAge: 31,
        patientGroupeSanguin: "A+",
        date: "2025-01-12",
        heure: "16:00",
        motif: "Infection ORL",
        type: "teleconsultation",
        statut: "termine",
        duree: 20,
        notes: "Amoxicilline 1g prescrite. Repos 3 jours.",
        urgence: false
    },
    {
        id: 12,
        patientNom: "Kevin Blanc",
        patientAge: 26,
        patientGroupeSanguin: "B+",
        date: "2025-01-18",
        heure: "09:00",
        motif: "Douleurs lombaires",
        type: "presentiel",
        statut: "en_attente",
        duree: 30,
        notes: "",
        urgence: false
    }
];

/* ============================================================
   2. ÉTAT DE L'APPLICATION
   ============================================================ */

/* Objet central gérant tous les états de la page */
const appState = {
    filtreCourant: "tous",     /* Filtre statut actif */
    rechercheTexte: "",        /* Texte recherche en cours */
    dateFiltre: "",            /* Date filtre sélectionnée */
    consultationActive: null   /* Consultation affichée dans le modal */
};

/* ============================================================
   3. UTILITAIRES
   ============================================================ */

/**
 * Retourne les initiales d'un nom complet (ex: "Marie Dupont" → "MD")
 * @param {string} nom - Nom complet du patient
 * @returns {string} - 2 initiales en majuscule
 */
function getInitiales(nom) {
    /* Découpe le nom en mots, prend la 1ère lettre de chacun */
    return nom.split(' ')
              .map(n => n[0])          /* 1ère lettre de chaque mot */
              .join('')                /* Fusionne */
              .toUpperCase()          /* Majuscule */
              .substring(0, 2);       /* Max 2 caractères */
}

/**
 * Formate une date ISO (YYYY-MM-DD) en format lisible français (DD/MM/YYYY)
 * @param {string} dateISO - Date au format ISO
 * @returns {string} - Date formatée
 */
function formatDate(dateISO) {
    /* Décompose la date et réassemble en ordre français */
    const [annee, mois, jour] = dateISO.split('-');
    return `${jour}/${mois}/${annee}`;
}

/**
 * Traduit le code statut en texte français affiché
 * @param {string} statut - Code statut interne
 * @returns {string} - Libellé français
 */
function getLibelleStatut(statut) {
    /* Dictionnaire code → libellé */
    const labels = {
        en_attente: "En attente",  /* Consultation demandée, pas encore traitée */
        confirme:   "Confirmée",   /* Médecin a accepté */
        termine:    "Terminée",    /* Consultation effectuée */
        annule:     "Annulée"      /* Consultation annulée */
    };
    return labels[statut] || statut; /* Retourne le code si non trouvé */
}

/**
 * Traduit le code type en texte français
 * @param {string} type - Code type consultation
 * @returns {string} - Libellé type
 */
function getLibelleType(type) {
    /* Dictionnaire type → libellé avec icône */
    const types = {
        presentiel:       "Présentiel",       /* En cabinet */
        teleconsultation: "Téléconsultation"  /* À distance */
    };
    return types[type] || type;
}

/**
 * Retourne l'icône Font Awesome selon le type de consultation
 * @param {string} type - Code type
 * @returns {string} - Classe icône FA
 */
function getIconeType(type) {
    return type === 'teleconsultation'
        ? 'fa-solid fa-video'          /* Icône caméra pour téléconsultation */
        : 'fa-solid fa-hospital-user'; /* Icône hôpital pour présentiel */
}

/**
 * Affiche un toast de notification pendant 3 secondes
 * @param {string} message - Message à afficher
 * @param {string} type - Type : 'success', 'error', 'info'
 */
function afficherToast(message, type = 'success') {
    const toast = document.getElementById('toast');       /* Récupère l'élément toast */
    const toastMsg = document.getElementById('toastMessage'); /* Récupère le span message */
    const toastIcon = toast.querySelector('.toast-icon'); /* Récupère l'icône */

    /* Définit l'icône selon le type */
    const icones = {
        success: 'fa-solid fa-circle-check',   /* Coche verte */
        error:   'fa-solid fa-circle-xmark',   /* Croix rouge */
        info:    'fa-solid fa-circle-info'     /* Info bleue */
    };

    /* Couleurs icône selon type */
    const couleurs = {
        success: '#10B981',  /* Vert */
        error:   '#EF4444',  /* Rouge */
        info:    '#4A7FA7'   /* Bleu */
    };

    toastMsg.textContent = message;             /* Injecte le message */
    toastIcon.className = `toast-icon ${icones[type] || icones.success}`; /* Icône */
    toastIcon.style.color = couleurs[type] || couleurs.success;           /* Couleur */

    toast.classList.add('show');               /* Affiche le toast (animation CSS) */

    /* Cache le toast après 3 secondes */
    setTimeout(() => toast.classList.remove('show'), 3000);
}

/* ============================================================
   4. RENDU DU TABLEAU
   ============================================================ */

/**
 * Filtre les consultations selon l'état courant (statut, recherche, date)
 * @returns {Array} - Liste filtrée des consultations
 */
function filtrerConsultations() {
    return consultations.filter(c => {

        /* Filtre par statut */
        const matchStatut = appState.filtreCourant === 'tous'
                          || c.statut === appState.filtreCourant;

        /* Filtre par texte (nom patient ou motif, insensible à la casse) */
        const texte = appState.rechercheTexte.toLowerCase();
        const matchRecherche = texte === ''
            || c.patientNom.toLowerCase().includes(texte) /* Cherche dans le nom */
            || c.motif.toLowerCase().includes(texte);     /* Cherche dans le motif */

        /* Filtre par date exacte */
        const matchDate = appState.dateFiltre === ''
                        || c.date === appState.dateFiltre;

        /* Retourne vrai seulement si TOUS les filtres correspondent */
        return matchStatut && matchRecherche && matchDate;
    });
}

/**
 * Génère et injecte les lignes du tableau selon les consultations filtrées
 */
function renderTableau() {
    const tbody = document.getElementById('consultationsBody');  /* Corps tableau */
    const emptyState = document.getElementById('emptyState');    /* Message vide */
    const resultsCount = document.getElementById('resultsCount'); /* Compteur */

    /* Récupère les données filtrées */
    const donnees = filtrerConsultations();

    /* Met à jour le compteur de résultats */
    resultsCount.textContent = `${donnees.length} consultation${donnees.length > 1 ? 's' : ''}`;

    /* Vide le tbody avant de le reconstruire */
    tbody.innerHTML = '';

    /* Si aucun résultat : affiche l'état vide */
    if (donnees.length === 0) {
        emptyState.style.display = 'block'; /* Montre le message vide */
        return;                              /* Sort de la fonction */
    }

    /* Cache l'état vide si données présentes */
    emptyState.style.display = 'none';

    /* Trie par date décroissante puis heure */
    donnees.sort((a, b) => {
        const dateA = new Date(`${a.date}T${a.heure}`); /* Construit objet Date A */
        const dateB = new Date(`${b.date}T${b.heure}`); /* Construit objet Date B */
        return dateB - dateA; /* Tri décroissant (plus récent en premier) */
    });

    /* Génère une ligne HTML pour chaque consultation */
    donnees.forEach(c => {
        const tr = document.createElement('tr'); /* Crée une ligne */
        tr.dataset.id = c.id;                    /* Stocke l'ID en data-attribute */

        /* Construit le HTML de la ligne */
        tr.innerHTML = `
            <!-- Colonne Patient : avatar + nom + âge -->
            <td>
                <div class="patient-cell">
                    <div class="patient-avatar">${getInitiales(c.patientNom)}</div>
                    <div>
                        <div class="patient-name">
                            ${c.patientNom}
                            ${c.urgence ? '<span style="color:#EF4444;font-size:10px;margin-left:5px">● URGENT</span>' : ''}
                        </div>
                        <div class="patient-age">${c.patientAge} ans · ${c.patientGroupeSanguin}</div>
                    </div>
                </div>
            </td>

            <!-- Colonne Date & Heure -->
            <td>
                <div class="date-cell">
                    <span class="date-main">${formatDate(c.date)}</span>
                    <span class="date-time"><i class="fa-solid fa-clock"></i> ${c.heure}</span>
                </div>
            </td>

            <!-- Colonne Motif -->
            <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                ${c.motif}
            </td>

            <!-- Colonne Type -->
            <td>
                <span class="type-badge ${c.type}">
                    <i class="${getIconeType(c.type)}"></i>
                    ${getLibelleType(c.type)}
                </span>
            </td>

            <!-- Colonne Statut -->
            <td>
                <span class="status-badge ${c.statut}">
                    ${getLibelleStatut(c.statut)}
                </span>
            </td>

            <!-- Colonne Actions -->
            <td>
                <div class="actions-cell">
                    <!-- Bouton Voir détail -->
                    <button class="action-btn view" title="Voir détail"
                            onclick="ouvrirDetail(${c.id})">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    <!-- Bouton Accepter : visible seulement si en attente -->
                    ${c.statut === 'en_attente' ? `
                    <button class="action-btn accept" title="Accepter"
                            onclick="changerStatut(${c.id}, 'confirme', event)">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    ` : ''}

                    <!-- Bouton Refuser : visible si en attente ou confirmé -->
                    ${(c.statut === 'en_attente' || c.statut === 'confirme') ? `
                    <button class="action-btn refuse" title="Refuser"
                            onclick="changerStatut(${c.id}, 'annule', event)">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    ` : ''}

                    <!-- Bouton Notes : visible si confirmé ou terminé -->
                    ${(c.statut === 'confirme' || c.statut === 'termine') ? `
                    <button class="action-btn notes" title="Compte rendu"
                            onclick="ouvrirNotes(${c.id}, event)">
                        <i class="fa-solid fa-notes-medical"></i>
                    </button>
                    ` : ''}
                </div>
            </td>
        `;

        tbody.appendChild(tr); /* Ajoute la ligne au tableau */
    });
}

/* ============================================================
   5. MODAL DÉTAIL CONSULTATION
   ============================================================ */

/**
 * Ouvre le modal de détail pour une consultation donnée
 * @param {number} id - ID de la consultation à afficher
 */
function ouvrirDetail(id) {
    /* Recherche la consultation par ID */
    const c = consultations.find(x => x.id === id);
    if (!c) return; /* Sécurité : sort si non trouvée */

    /* Stocke la consultation active dans l'état */
    appState.consultationActive = c;

    /* Récupère les éléments du modal */
    const body = document.getElementById('modalDetailBody');
    const footer = document.getElementById('modalDetailFooter');

    /* Injecte le contenu détaillé */
    body.innerHTML = `
        <!-- Info patient en en-tête modal -->
        <div class="detail-patient">
            <div class="patient-avatar" style="width:52px;height:52px;font-size:18px">
                ${getInitiales(c.patientNom)}
            </div>
            <div>
                <div class="detail-patient-name">${c.patientNom}</div>
                <div class="detail-patient-info">
                    ${c.patientAge} ans · Groupe sanguin : ${c.patientGroupeSanguin}
                    ${c.urgence ? ' · <span style="color:#EF4444;font-weight:600">⚠ URGENT</span>' : ''}
                </div>
            </div>
            <!-- Badge statut en haut à droite -->
            <span class="status-badge ${c.statut}" style="margin-left:auto">
                ${getLibelleStatut(c.statut)}
            </span>
        </div>

        <!-- Grille 2 colonnes avec les infos -->
        <div class="detail-grid">
            <!-- Date -->
            <div class="detail-field">
                <span class="detail-label"><i class="fa-solid fa-calendar"></i> Date</span>
                <span class="detail-value">${formatDate(c.date)}</span>
            </div>
            <!-- Heure -->
            <div class="detail-field">
                <span class="detail-label"><i class="fa-solid fa-clock"></i> Heure</span>
                <span class="detail-value">${c.heure}</span>
            </div>
            <!-- Type -->
            <div class="detail-field">
                <span class="detail-label"><i class="fa-solid fa-tag"></i> Type</span>
                <span class="detail-value">
                    <span class="type-badge ${c.type}">
                        <i class="${getIconeType(c.type)}"></i>
                        ${getLibelleType(c.type)}
                    </span>
                </span>
            </div>
            <!-- Durée -->
            <div class="detail-field">
                <span class="detail-label"><i class="fa-solid fa-hourglass"></i> Durée</span>
                <span class="detail-value">${c.duree} minutes</span>
            </div>
            <!-- Motif : toute la largeur -->
            <div class="detail-field full">
                <span class="detail-label"><i class="fa-solid fa-stethoscope"></i> Motif de consultation</span>
                <span class="detail-value">${c.motif}</span>
            </div>
            <!-- Notes : visible seulement si non vide -->
            ${c.notes ? `
            <div class="detail-field full">
                <span class="detail-label"><i class="fa-solid fa-notes-medical"></i> Notes du médecin</span>
                <span class="detail-value" style="white-space:pre-wrap;background:var(--color-background-light);padding:12px;border-radius:8px;display:block;font-size:13px;line-height:1.6">${c.notes}</span>
            </div>
            ` : ''}
        </div>
    `;

    /* Génère les boutons d'action selon le statut actuel */
    let boutonsHTML = `
        <!-- Bouton fermer toujours présent -->
        <button class="btn-secondary" onclick="fermerModal('modalDetail')">Fermer</button>
    `;

    /* Boutons spécifiques selon le statut */
    if (c.statut === 'en_attente') {
        /* En attente : peut accepter ou refuser */
        boutonsHTML += `
            <button class="btn-primary" style="background:var(--color-red)"
                    onclick="changerStatutDepuisModal(${c.id}, 'annule')">
                <i class="fa-solid fa-xmark"></i> Refuser
            </button>
            <button class="btn-primary"
                    onclick="changerStatutDepuisModal(${c.id}, 'confirme')">
                <i class="fa-solid fa-check"></i> Accepter
            </button>
        `;
    } else if (c.statut === 'confirme') {
        /* Confirmé : peut marquer comme terminé ou ajouter notes */
        boutonsHTML += `
            <button class="btn-primary" style="background:var(--color-orange)"
                    onclick="ouvrirNotes(${c.id})">
                <i class="fa-solid fa-notes-medical"></i> Compte rendu
            </button>
            <button class="btn-primary"
                    onclick="changerStatutDepuisModal(${c.id}, 'termine')">
                <i class="fa-solid fa-flag-checkered"></i> Terminer
            </button>
        `;
    } else if (c.statut === 'termine') {
        /* Terminé : peut voir/modifier les notes */
        boutonsHTML += `
            <button class="btn-primary"
                    onclick="ouvrirNotes(${c.id})">
                <i class="fa-solid fa-notes-medical"></i> Voir compte rendu
            </button>
        `;
    }

    footer.innerHTML = boutonsHTML; /* Injecte les boutons dans le pied du modal */

    /* Affiche le modal */
    document.getElementById('modalDetail').style.display = 'flex';
    document.body.style.overflow = 'hidden'; /* Bloque le scroll arrière-plan */
}

/* ============================================================
   6. MODAL NOTES / COMPTE RENDU
   ============================================================ */

/**
 * Ouvre le modal de notes/compte rendu pour une consultation
 * @param {number} id - ID de la consultation
 * @param {Event} event - Événement click (optionnel, pour stopper la propagation)
 */
function ouvrirNotes(id, event) {
    if (event) event.stopPropagation(); /* Empêche l'ouverture du modal détail */

    /* Recherche la consultation */
    const c = consultations.find(x => x.id === id);
    if (!c) return;

    appState.consultationActive = c; /* Stocke la consultation active */

    /* Injecte le nom patient dans le label */
    document.getElementById('notesPatientLabel').textContent =
        `📋 Compte rendu — ${c.patientNom} · ${formatDate(c.date)} à ${c.heure}`;

    /* Pré-remplit les champs si des notes existent */
    /* On tente de parser les notes comme JSON pour extraire les sections */
    try {
        const notesObj = JSON.parse(c.notes); /* Tente de parser en JSON */
        document.getElementById('notesSymptomes').value  = notesObj.symptomes  || '';
        document.getElementById('notesDiagnostic').value = notesObj.diagnostic || '';
        document.getElementById('notesTraitement').value = notesObj.traitement || '';
        document.getElementById('notesSuivi').value      = notesObj.suivi      || '';
    } catch {
        /* Si pas JSON (texte simple) : met tout dans symptômes */
        document.getElementById('notesSymptomes').value  = c.notes || '';
        document.getElementById('notesDiagnostic').value = '';
        document.getElementById('notesTraitement').value = '';
        document.getElementById('notesSuivi').value      = '';
    }

    /* Ferme d'abord le modal détail si ouvert */
    document.getElementById('modalDetail').style.display = 'none';

    /* Affiche le modal notes */
    document.getElementById('modalNotes').style.display = 'flex';
    document.body.style.overflow = 'hidden'; /* Bloque le scroll */
}

/* ============================================================
   7. CHANGEMENT DE STATUT
   ============================================================ */

/**
 * Change le statut d'une consultation (depuis le tableau)
 * @param {number} id - ID consultation
 * @param {string} nouveauStatut - Nouveau statut à appliquer
 * @param {Event} event - Événement click (pour stopper propagation)
 */
function changerStatut(id, nouveauStatut, event) {
    if (event) event.stopPropagation(); /* Empêche d'ouvrir le modal détail */

    /* Trouve la consultation dans le tableau */
    const index = consultations.findIndex(x => x.id === id);
    if (index === -1) return; /* Sécurité */

    const ancienStatut = consultations[index].statut; /* Sauvegarde ancien statut */
    consultations[index].statut = nouveauStatut;       /* Applique le nouveau statut */

    /* Messages de confirmation selon l'action */
    const messages = {
        confirme: `✅ Consultation de ${consultations[index].patientNom} acceptée`,
        annule:   `❌ Consultation de ${consultations[index].patientNom} refusée`,
        termine:  `🏁 Consultation de ${consultations[index].patientNom} marquée terminée`
    };

    afficherToast(messages[nouveauStatut] || 'Statut mis à jour', 'success');

    updateStats();   /* Recalcule les statistiques */
    renderTableau(); /* Rafraîchit le tableau */
}

/**
 * Change le statut depuis le modal détail (ferme le modal après)
 * @param {number} id - ID consultation
 * @param {string} nouveauStatut - Nouveau statut
 */
function changerStatutDepuisModal(id, nouveauStatut) {
    changerStatut(id, nouveauStatut, null); /* Applique le changement */
    fermerModal('modalDetail');             /* Ferme le modal */
}

/* ============================================================
   8. FERMETURE DES MODALS
   ============================================================ */

/**
 * Ferme un modal par son ID
 * @param {string} modalId - ID de l'élément modal overlay
 */
function fermerModal(modalId) {
    document.getElementById(modalId).style.display = 'none'; /* Cache le modal */
    document.body.style.overflow = '';                        /* Restaure le scroll */
    appState.consultationActive = null;                        /* Réinitialise */
}

/* ============================================================
   9. MISE À JOUR DES STATISTIQUES
   ============================================================ */

/**
 * Calcule et met à jour les 4 compteurs de statistiques
 */
function updateStats() {
    /* Date d'aujourd'hui au format ISO (YYYY-MM-DD) */
    const today = new Date().toISOString().split('T')[0];

    /* Comptage consultations du jour */
    const aujourdHui = consultations.filter(c => c.date === today).length;

    /* Comptage consultations en attente */
    const enAttente = consultations.filter(c => c.statut === 'en_attente').length;

    /* Comptage consultations confirmées */
    const confirmees = consultations.filter(c => c.statut === 'confirme').length;

    /* Comptage consultations du mois en cours */
    const moisActuel = new Date().toISOString().substring(0, 7); /* Format YYYY-MM */
    const ceMois = consultations.filter(c => c.date.startsWith(moisActuel)).length;

    /* Injecte les valeurs dans les éléments HTML */
    document.getElementById('statAujourdhui').textContent = aujourdHui;
    document.getElementById('statAttente').textContent    = enAttente;
    document.getElementById('statAcceptees').textContent  = confirmees;
    document.getElementById('statMois').textContent       = ceMois;

    /* Met aussi à jour le badge de la sidebar */
    const badgeNav = document.querySelector('.nav-item.active .nav-badge');
    if (badgeNav) badgeNav.textContent = enAttente; /* Nombre de consultations en attente */
}

/* ============================================================
   10. INITIALISATION DES ÉCOUTEURS D'ÉVÉNEMENTS
   ============================================================ */

/**
 * Initialise tous les écouteurs quand le DOM est prêt
 */
document.addEventListener('DOMContentLoaded', () => {

    /* ---- Rendu initial ---- */
    updateStats();   /* Calcule les stats */
    renderTableau(); /* Affiche le tableau */

    /* ---- Date par défaut dans le formulaire nouvelle consultation ---- */
    const today = new Date().toISOString().split('T')[0]; /* Date aujourd'hui */
    const formDate = document.getElementById('formDate');
    if (formDate) formDate.value = today; /* Pré-remplit la date */

    /* ---- Recherche en temps réel ---- */
    document.getElementById('searchInput').addEventListener('input', function() {
        appState.rechercheTexte = this.value.trim(); /* Met à jour l'état recherche */
        renderTableau(); /* Rafraîchit le tableau */
    });

    /* ---- Filtres par statut (onglets) ---- */
    document.getElementById('filterTabs').addEventListener('click', function(e) {
        /* Vérifie que le clic est sur un onglet */
        if (!e.target.classList.contains('filter-tab')) return;

        /* Retire la classe active de tous les onglets */
        document.querySelectorAll('.filter-tab').forEach(btn => {
            btn.classList.remove('active');
        });

        e.target.classList.add('active');                    /* Active l'onglet cliqué */
        appState.filtreCourant = e.target.dataset.filter;   /* Met à jour l'état */
        renderTableau(); /* Rafraîchit le tableau */
    });

    /* ---- Filtre par date ---- */
    document.getElementById('dateFilter').addEventListener('change', function() {
        appState.dateFiltre = this.value; /* Met à jour la date filtre */
        renderTableau(); /* Rafraîchit */
    });

    /* ---- Reset filtre date ---- */
    document.getElementById('resetDate').addEventListener('click', function() {
        document.getElementById('dateFilter').value = ''; /* Vide l'input date */
        appState.dateFiltre = '';  /* Réinitialise l'état */
        renderTableau(); /* Rafraîchit */
    });

    /* ---- Bouton Nouvelle Consultation ---- */
    document.getElementById('btnNouvelleConsult').addEventListener('click', function() {
        document.getElementById('modalNouvelle').style.display = 'flex'; /* Ouvre le modal */
        document.body.style.overflow = 'hidden'; /* Bloque le scroll */
    });

    /* ---- Fermeture modal Nouvelle Consultation ---- */
    document.getElementById('closeModalNouvelle').addEventListener('click', () => {
        fermerModal('modalNouvelle'); /* Ferme le modal */
    });

    document.getElementById('cancelNouvelle').addEventListener('click', () => {
        fermerModal('modalNouvelle'); /* Bouton Annuler */
    });

    /* ---- Sauvegarde Nouvelle Consultation ---- */
    document.getElementById('saveNouvelle').addEventListener('click', () => {
        /* Récupère les valeurs du formulaire */
        const patientId = document.getElementById('formPatient').value;
        const date      = document.getElementById('formDate').value;
        const heure     = document.getElementById('formHeure').value;
        const motif     = document.getElementById('formMotif').value.trim();
        const type      = document.getElementById('formType').value;
        const duree     = parseInt(document.getElementById('formDuree').value);
        const notes     = document.getElementById('formNotes').value.trim();

        /* Validation des champs obligatoires */
        if (!patientId || !date || !heure || !motif) {
            afficherToast('⚠ Veuillez remplir tous les champs obligatoires', 'error');
            return; /* Arrête si champs manquants */
        }

        /* Récupère le nom du patient depuis l'option sélectionnée */
        const selectPatient = document.getElementById('formPatient');
        const patientNom = selectPatient.options[selectPatient.selectedIndex].text;

        /* Crée l'objet nouvelle consultation */
        const nouvConst = {
            id:               Date.now(),        /* ID unique basé sur timestamp */
            patientNom:       patientNom,
            patientAge:       Math.floor(Math.random() * 50) + 20, /* Age fictif */
            patientGroupeSanguin: "A+",          /* Valeur par défaut */
            date,
            heure,
            motif,
            type,
            statut:           "confirme",        /* Statut initial : confirmée */
            duree,
            notes,
            urgence:          false
        };

        consultations.unshift(nouvConst); /* Ajoute en tête du tableau */

        /* Réinitialise le formulaire */
        document.getElementById('formPatient').value = '';
        document.getElementById('formMotif').value   = '';
        document.getElementById('formNotes').value   = '';

        fermerModal('modalNouvelle');                              /* Ferme le modal */
        afficherToast(`✅ Consultation créée pour ${patientNom}`, 'success');
        updateStats();   /* Recalcule les stats */
        renderTableau(); /* Rafraîchit le tableau */
    });

    /* ---- Fermeture modal Détail ---- */
    document.getElementById('closeModalDetail').addEventListener('click', () => {
        fermerModal('modalDetail');
    });

    /* ---- Fermeture modal Notes ---- */
    document.getElementById('closeModalNotes').addEventListener('click', () => {
        fermerModal('modalNotes');
    });

    document.getElementById('cancelNotes').addEventListener('click', () => {
        fermerModal('modalNotes');
    });

    /* ---- Sauvegarde Notes ---- */
    document.getElementById('saveNotes').addEventListener('click', () => {
        const c = appState.consultationActive; /* Récupère la consultation active */
        if (!c) return;

        /* Récupère les valeurs des textareas */
        const symptomes  = document.getElementById('notesSymptomes').value.trim();
        const diagnostic = document.getElementById('notesDiagnostic').value.trim();
        const traitement = document.getElementById('notesTraitement').value.trim();
        const suivi      = document.getElementById('notesSuivi').value.trim();

        /* Construit l'objet notes structuré */
        const notesObj = { symptomes, diagnostic, traitement, suivi };

        /* Trouve et met à jour la consultation dans le tableau */
        const index = consultations.findIndex(x => x.id === c.id);
        if (index !== -1) {
            consultations[index].notes  = JSON.stringify(notesObj); /* Sérialise en JSON */
            consultations[index].statut = 'termine';                /* Marque comme terminée */
        }

        fermerModal('modalNotes'); /* Ferme le modal */
        afficherToast(`✅ Compte rendu sauvegardé pour ${c.patientNom}`, 'success');
        updateStats();   /* Recalcule les stats */
        renderTableau(); /* Rafraîchit le tableau */
    });

    /* ---- Fermeture modals en cliquant sur l'overlay ---- */
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            /* Ferme uniquement si clic direct sur l'overlay (pas sur le modal-box) */
            if (e.target === this) {
                fermerModal(this.id);
            }
        });
    });

    /* ---- Toggle sidebar mobile ---- */
    document.getElementById('sidebarToggle').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open'); /* Bascule la classe */
    });

    /* ---- Fermeture sidebar mobile en cliquant ailleurs ---- */
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.getElementById('sidebarToggle');

        /* Ferme sidebar si clic en dehors et sur mobile */
        if (window.innerWidth <= 768
            && sidebar.classList.contains('open')
            && !sidebar.contains(e.target)     /* Clic hors sidebar */
            && !toggle.contains(e.target)) {   /* Clic hors bouton toggle */
            sidebar.classList.remove('open');
        }
    });

    /* ---- Cloche notifications (feedback visuel) ---- */
    document.getElementById('notifBtn').addEventListener('click', function() {
        this.querySelector('.notif-dot').style.display = 'none'; /* Cache le point rouge */
        afficherToast('📬 3 notifications lues', 'info');
    });

    /* ---- Touche Échap pour fermer les modals ---- */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            /* Ferme tous les modals ouverts */
            document.querySelectorAll('.modal-overlay').forEach(m => {
                if (m.style.display !== 'none') {
                    fermerModal(m.id);
                }
            });
        }
    });
});