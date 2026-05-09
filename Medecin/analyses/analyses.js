/* ============================================================
   analyses.js — Dashboard Médecin : Logique Analyses
   Graphiques Chart.js, animations KPI, filtres période,
   tableau récapitulatif, export CSV
   ============================================================ */


/* ============================================================
   1. CONFIGURATION GLOBALE CHART.JS
   ============================================================ */

/* Applique des valeurs par défaut globaux à tous les graphiques Chart.js */
Chart.defaults.font.family  = "'Outfit', sans-serif"; /* Police cohérente avec la charte */
Chart.defaults.font.size    = 13;                      /* Taille texte graphiques */
Chart.defaults.color        = '#9CA3AF';               /* Couleur texte (gris moyen) */
Chart.defaults.plugins.legend.display = false;         /* Cache la légende par défaut (remplacée) */
Chart.defaults.plugins.tooltip.backgroundColor = '#1A3D63'; /* Fond tooltip bleu médical */
Chart.defaults.plugins.tooltip.titleColor       = '#FFFFFF'; /* Titre tooltip blanc */
Chart.defaults.plugins.tooltip.bodyColor        = '#B3CFE5'; /* Corps tooltip bleu clair */
Chart.defaults.plugins.tooltip.padding          = 12;        /* Espacement interne tooltip */
Chart.defaults.plugins.tooltip.cornerRadius     = 8;         /* Arrondi tooltip */
Chart.defaults.plugins.tooltip.displayColors    = false;     /* Cache les carrés de couleur tooltip */


/* ============================================================
   2. PALETTE DE COULEURS
   ============================================================ */

/* Couleurs réutilisées dans tous les graphiques */
const COULEURS = {
    blue:        '#4A7FA7',              /* Bleu accent (patients) */
    blueLight:   'rgba(74,127,167,0.15)',/* Bleu transparent (fond aire) */
    teal:        '#047857',              /* Teal (consultations) */
    tealLight:   'rgba(4,120,87,0.12)', /* Teal transparent */
    green:       '#10B981',             /* Vert (vaccins effectués) */
    greenLight:  'rgba(16,185,129,0.12)',/* Vert transparent */
    orange:      '#F59E0B',             /* Orange (messagerie reçue) */
    orangeLight: 'rgba(245,158,11,0.12)',/* Orange transparent */
    red:         '#EF4444',             /* Rouge (vaccins en retard) */
    redLight:    'rgba(239,68,68,0.1)', /* Rouge transparent */
    border:      '#E5EDF5',             /* Couleur bordures grille */

    /* Palette camembert pathologies */
    donut: [
        '#1A3D63', /* Bleu primaire foncé */
        '#4A7FA7', /* Bleu accent */
        '#047857', /* Teal foncé */
        '#10B981', /* Vert */
        '#F59E0B', /* Orange */
        '#EF4444', /* Rouge */
    ]
};


/* ============================================================
   3. DONNÉES ANALYTIQUES
   ============================================================ */

/**
 * Génère les données pour N derniers mois
 * @param {number} nbMois - Nombre de mois à générer
 * @returns {Object} - { labels, patients, consultations, vaccinsOK, vaccinsRetard, messagesRecus, messagesEnvoyes }
 */
function genererDonnees(nbMois) {
    const labels             = []; /* Étiquettes mois (ex: "Jan", "Fév") */
    const patients           = []; /* Nouveaux patients par mois */
    const consultations      = []; /* Consultations par mois */
    const vaccinsOK          = []; /* Vaccins effectués par mois */
    const vaccinsRetard      = []; /* Vaccins en retard par mois */
    const messagesRecus      = []; /* Messages reçus par mois */
    const messagesEnvoyes    = []; /* Messages envoyés par mois */

    /* Noms des mois en français court */
    const nomsMois = ['Jan','Fév','Mar','Avr','Mai','Jui','Jul','Aoû','Sep','Oct','Nov','Déc'];

    /* Date actuelle */
    const maintenant = new Date();

    /* Boucle sur nbMois en arrière depuis aujourd'hui */
    for (let i = nbMois - 1; i >= 0; i--) {
        /* Calcule la date du mois i mois en arrière */
        const date = new Date(maintenant.getFullYear(), maintenant.getMonth() - i, 1);

        /* Ajoute le label "Mois Année courte" (ex: "Jan 25") */
        labels.push(nomsMois[date.getMonth()] + (i === 0 ? ' ★' : '')); /* ★ = mois actuel */

        /* Génère des données simulées avec tendance croissante et légère variation */
        const base     = nbMois - i;                         /* Facteur croissant */
        const variance = () => Math.floor(Math.random() * 5 - 2); /* Variation ±2 */

        patients.push(Math.max(2, Math.floor(8 + base * 0.8 + variance())));    /* 8 à 28+ patients */
        consultations.push(Math.max(10, Math.floor(20 + base * 2 + variance()))); /* 20 à 70+ consultations */
        vaccinsOK.push(Math.max(5, Math.floor(12 + base * 1.2 + variance())));   /* 12 à 40+ vaccins */
        vaccinsRetard.push(Math.max(1, Math.floor(6 - base * 0.3 + variance()))); /* Tendance décroissante */
        messagesRecus.push(Math.max(5, Math.floor(15 + base * 1.5 + variance()))); /* Messages reçus */
        messagesEnvoyes.push(Math.max(5, Math.floor(12 + base * 1.3 + variance()))); /* Messages envoyés */
    }

    return { labels, patients, consultations, vaccinsOK, vaccinsRetard, messagesRecus, messagesEnvoyes };
}

/* Données initiales pour 6 mois */
let donneesCourantes = genererDonnees(6);

/* ---- Données pathologies (fixes, indépendantes de la période) ---- */
const dataPathologies = {
    labels: [
        'Maladies respiratoires',   /* Rhumes, bronchites, etc. */
        'Hypertension / Cardio',    /* Suivi tension, cœur */
        'Diabète / Métabolisme',    /* Diabète types 1 et 2 */
        'Traumatologie',            /* Fractures, entorses */
        'Dermatologie',             /* Infections cutanées */
        'Autres'                    /* Motifs divers */
    ],
    valeurs: [28, 22, 17, 14, 11, 8], /* Pourcentages estimés */
};


/* ============================================================
   4. ÉTAT DE L'APPLICATION
   ============================================================ */

const appState = {
    periodeMois: 6,         /* Période sélectionnée : 6, 12 ou 24 mois */
    chartsCrees: false,     /* Évite la double création des graphiques */
    chartRefs: {}           /* Stocke les références aux instances Chart.js */
};


/* ============================================================
   5. ANIMATION COMPTEURS KPI
   ============================================================ */

/**
 * Anime un compteur numérique de 0 vers la valeur cible
 * @param {HTMLElement} el    - Élément DOM contenant le chiffre
 * @param {number} cible      - Valeur finale à atteindre
 * @param {number} duree      - Durée animation en millisecondes
 */
function animerCompteur(el, cible, duree = 1200) {
    const debut = performance.now(); /* Timestamp de départ */
    const valeurInitiale = 0;        /* Toujours part de 0 */

    /* Fonction d'easing : décélère à la fin (ease-out cubique) */
    const easeOut = t => 1 - Math.pow(1 - t, 3);

    /* Fonction récursive appelée à chaque frame d'animation */
    function frame(timestamp) {
        const elapsed  = timestamp - debut;              /* Temps écoulé */
        const progress = Math.min(elapsed / duree, 1);  /* Progression 0→1 */
        const valeur   = Math.round(easeOut(progress) * cible); /* Valeur avec easing */

        el.textContent = valeur; /* Met à jour l'affichage */

        if (progress < 1) {
            requestAnimationFrame(frame); /* Continue l'animation si pas terminé */
        } else {
            el.textContent = cible; /* Force la valeur exacte en fin */
        }
    }

    requestAnimationFrame(frame); /* Lance la première frame */
}

/**
 * Lance l'animation de tous les compteurs KPI
 */
function lancerAnimationsKPI() {
    /* Sélectionne tous les éléments avec data-target */
    document.querySelectorAll('.kpi-value[data-target]').forEach(el => {
        const cible = parseInt(el.dataset.target); /* Lit la valeur cible */
        animerCompteur(el, cible, 1400);           /* Lance l'animation 1.4s */
    });
}


/* ============================================================
   6. GRAPHIQUES SPARKLINES (mini-graphes sur les cartes KPI)
   ============================================================ */

/**
 * Crée un mini-graphique sparkline pour une carte KPI
 * @param {string} id      - ID du canvas HTML
 * @param {Array}  data    - Tableau de valeurs
 * @param {string} color   - Couleur de la ligne
 */
function creerSparkline(id, data, color) {
    const canvas = document.getElementById(id); /* Récupère le canvas */
    if (!canvas) return;                         /* Sécurité : sort si absent */

    new Chart(canvas, {
        type: 'line',          /* Graphique en ligne */
        data: {
            labels: data.map((_, i) => i), /* Labels numériques (pas affichés) */
            datasets: [{
                data,                           /* Données de la sparkline */
                borderColor: color,             /* Couleur de la ligne */
                borderWidth: 2,                 /* Épaisseur ligne */
                fill: true,                     /* Rempli sous la courbe */
                backgroundColor: color + '20', /* Couleur fond semi-transparent */
                tension: 0.4,                  /* Courbe lissée */
                pointRadius: 0,                /* Pas de points visibles */
            }]
        },
        options: {
            responsive: true,          /* S'adapte au conteneur */
            maintainAspectRatio: false,/* Utilise la hauteur du canvas */
            plugins: { tooltip: { enabled: false } }, /* Désactive le tooltip */
            scales: {
                x: { display: false }, /* Cache l'axe X */
                y: { display: false }  /* Cache l'axe Y */
            },
            animation: { duration: 1500 } /* Animation 1.5s */
        }
    });
}

/**
 * Initialise les 4 sparklines des cartes KPI
 */
function initSparklines() {
    const d = donneesCourantes; /* Raccourci données */

    creerSparkline('sparkline1', d.patients,      COULEURS.blue);   /* Patients */
    creerSparkline('sparkline2', d.consultations, COULEURS.teal);   /* Consultations */
    creerSparkline('sparkline3', d.vaccinsOK,     COULEURS.green);  /* Vaccins */
    creerSparkline('sparkline4', d.messagesRecus, COULEURS.orange); /* Messages */
}


/* ============================================================
   7. GRAPHIQUE 1 : PATIENTS PAR MOIS (Courbe/Ligne)
   ============================================================ */

/**
 * Crée ou met à jour le graphique courbe des nouveaux patients par mois
 * @param {boolean} forceRecreate - Force la recréation complète
 */
function initChartPatientsMois(forceRecreate = false) {
    const canvas = document.getElementById('chartPatientsMois');
    if (!canvas) return;

    /* Détruit l'instance précédente si elle existe */
    if (appState.chartRefs.patientsMois) {
        appState.chartRefs.patientsMois.destroy();
    }

    const d = donneesCourantes; /* Données de la période courante */

    /* Création du dégradé de remplissage sous la courbe */
    const ctx    = canvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300); /* Dégradé vertical */
    gradient.addColorStop(0, 'rgba(74,127,167,0.25)');  /* Haut : bleu semi-opaque */
    gradient.addColorStop(1, 'rgba(74,127,167,0.02)');  /* Bas : quasi transparent */

    /* Instancie le graphique Chart.js */
    appState.chartRefs.patientsMois = new Chart(ctx, {
        type: 'line',     /* Type : ligne */
        data: {
            labels: d.labels, /* Mois sur l'axe X */
            datasets: [
                {
                    label: 'Nouveaux patients', /* Libellé dataset */
                    data: d.patients,            /* Valeurs */
                    borderColor: COULEURS.blue,  /* Couleur de la ligne */
                    borderWidth: 3,              /* Épaisseur ligne */
                    backgroundColor: gradient,   /* Fond dégradé */
                    fill: true,                  /* Remplit sous la courbe */
                    tension: 0.4,               /* Lissage courbe de Bézier */
                    pointRadius: 5,             /* Taille des points */
                    pointBackgroundColor: COULEURS.blue,  /* Couleur fond point */
                    pointBorderColor: '#FFFFFF',           /* Contour blanc */
                    pointBorderWidth: 2,                   /* Épaisseur contour point */
                    pointHoverRadius: 7,         /* Point plus grand au survol */
                },
                {
                    label: 'Consultations',      /* Second dataset */
                    data: d.consultations,
                    borderColor: COULEURS.teal,
                    borderWidth: 2,
                    backgroundColor: 'transparent',
                    fill: false,                 /* Pas de remplissage */
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: COULEURS.teal,
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    borderDash: [5, 4],          /* Ligne en tirets */
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,   /* Respecte la hauteur du canvas */
            interaction: {
                intersect: false,         /* Tooltip sur toute la colonne */
                mode: 'index'             /* Affiche tous les datasets au hover */
            },
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        /* Personnalise le titre du tooltip */
                        title: (items) => `📅 ${items[0].label}`,
                        /* Personnalise chaque ligne du tooltip */
                        label: (item) => ` ${item.dataset.label} : ${item.raw}`
                    }
                },
                /* Légende personnalisée (on la gère nous-mêmes dans le HTML) */
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: COULEURS.border, drawBorder: false }, /* Grille discrète */
                    ticks: { font: { size: 12 } }                         /* Taille étiquettes X */
                },
                y: {
                    grid: { color: COULEURS.border, drawBorder: false },
                    ticks: {
                        font: { size: 12 },
                        /* Ajoute un zéro au minimum pour ne pas couper la courbe */
                        beginAtZero: false,
                        /* Arrondit les valeurs de l'axe */
                        callback: val => Math.round(val)
                    },
                    min: 0 /* Commence à 0 sur l'axe Y */
                }
            },
            animation: { duration: 1000, easing: 'easeInOutQuart' } /* Animation fluide */
        }
    });
}


/* ============================================================
   8. GRAPHIQUE 2 : PATHOLOGIES (Camembert / Donut)
   ============================================================ */

/**
 * Crée le graphique donut de répartition par pathologie
 * et génère la légende personnalisée en HTML
 */
function initChartPathologies() {
    const canvas = document.getElementById('chartPathologies');
    if (!canvas) return;

    if (appState.chartRefs.pathologies) {
        appState.chartRefs.pathologies.destroy(); /* Détruit si existant */
    }

    /* Total pour calculer les pourcentages */
    const total = dataPathologies.valeurs.reduce((a, b) => a + b, 0);

    appState.chartRefs.pathologies = new Chart(canvas, {
        type: 'doughnut',         /* Type donut (camembert avec trou) */
        data: {
            labels: dataPathologies.labels,   /* Labels des pathologies */
            datasets: [{
                data: dataPathologies.valeurs, /* Valeurs */
                backgroundColor: COULEURS.donut, /* Couleurs palette */
                borderColor: '#FFFFFF',           /* Séparateurs blancs */
                borderWidth: 3,                   /* Épaisseur séparateurs */
                hoverOffset: 8                    /* Décalage au survol */
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,  /* Garde le ratio carré du donut */
            cutout: '65%',              /* Taille du trou central (donut) */
            plugins: {
                legend: { display: false }, /* Cache la légende par défaut */
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: (item) => {
                            /* Calcule le pourcentage et l'affiche dans le tooltip */
                            const pct = Math.round(item.raw / total * 100);
                            return ` ${item.label} : ${item.raw} cas (${pct}%)`;
                        }
                    }
                }
            },
            animation: { animateRotate: true, duration: 1200 } /* Animation rotation */
        }
    });

    /* Génère la légende personnalisée HTML sous le donut */
    genererLegendeDonut(total);
}

/**
 * Génère la légende personnalisée du donut en HTML
 * @param {number} total - Somme totale des cas
 */
function genererLegendeDonut(total) {
    const legendeEl = document.getElementById('donutLegend');
    if (!legendeEl) return;

    legendeEl.innerHTML = ''; /* Vide la légende précédente */

    /* Crée une ligne par pathologie */
    dataPathologies.labels.forEach((label, i) => {
        const valeur = dataPathologies.valeurs[i];               /* Valeur brute */
        const pct    = Math.round(valeur / total * 100);         /* Pourcentage */
        const couleur = COULEURS.donut[i];                       /* Couleur correspondante */

        /* Crée l'élément div de ligne */
        const item = document.createElement('div');
        item.className = 'donut-legend-item';

        item.innerHTML = `
            <div class="donut-legend-left">
                <!-- Carré coloré indicateur -->
                <span class="donut-color-square" style="background:${couleur}"></span>
                <!-- Nom de la pathologie (raccourci si trop long) -->
                <span>${label.length > 22 ? label.substring(0, 22) + '…' : label}</span>
            </div>
            <!-- Pourcentage à droite -->
            <span class="donut-legend-pct" style="color:${couleur}">${pct}%</span>
        `;

        legendeEl.appendChild(item); /* Ajoute au conteneur légende */
    });
}


/* ============================================================
   9. GRAPHIQUE 3 : VACCINS (Barres groupées)
   ============================================================ */

/**
 * Crée le graphique en barres groupées Vaccins effectués vs en retard
 */
function initChartVaccins() {
    const canvas = document.getElementById('chartVaccins');
    if (!canvas) return;

    if (appState.chartRefs.vaccins) {
        appState.chartRefs.vaccins.destroy();
    }

    const d = donneesCourantes;

    appState.chartRefs.vaccins = new Chart(canvas, {
        type: 'bar',           /* Type : barres */
        data: {
            labels: d.labels,  /* Mois sur l'axe X */
            datasets: [
                {
                    label: 'Effectués',                   /* Dataset 1 : vaccins faits */
                    data: d.vaccinsOK,
                    backgroundColor: COULEURS.greenLight, /* Fond vert transparent */
                    borderColor: COULEURS.green,           /* Bordure verte */
                    borderWidth: 2,
                    borderRadius: 6,              /* Arrondi en haut des barres */
                    borderSkipped: false,         /* Arrondi sur tous les côtés */
                    hoverBackgroundColor: COULEURS.green, /* Vert plein au hover */
                },
                {
                    label: 'En retard',                   /* Dataset 2 : vaccins manqués */
                    data: d.vaccinsRetard,
                    backgroundColor: COULEURS.redLight,
                    borderColor: COULEURS.red,
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: COULEURS.red,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'               /* Tooltip pour les 2 datasets simultanément */
            },
            plugins: {
                legend: { display: false }, /* Légende gérée en HTML */
                tooltip: {
                    callbacks: {
                        title: (items) => `💉 ${items[0].label}`,
                        label: (item) => ` ${item.dataset.label} : ${item.raw} vaccins`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },              /* Cache la grille verticale */
                    ticks: { font: { size: 11 } }
                },
                y: {
                    grid: { color: COULEURS.border, drawBorder: false },
                    ticks: { font: { size: 11 }, beginAtZero: true },
                    min: 0
                }
            },
            animation: { duration: 1000, delay: (ctx) => ctx.dataIndex * 50 } /* Décalage animation */
        }
    });
}


/* ============================================================
   10. GRAPHIQUE 4 : MESSAGERIE (Courbe à double aire)
   ============================================================ */

/**
 * Crée le graphique messagerie (messages reçus et envoyés)
 */
function initChartMessagerie() {
    const canvas = document.getElementById('chartMessagerie');
    if (!canvas) return;

    if (appState.chartRefs.messagerie) {
        appState.chartRefs.messagerie.destroy();
    }

    const d = donneesCourantes;
    const ctx = canvas.getContext('2d');

    /* Dégradé pour messages reçus (orange) */
    const gradientOrange = ctx.createLinearGradient(0, 0, 0, 280);
    gradientOrange.addColorStop(0, 'rgba(245,158,11,0.3)');
    gradientOrange.addColorStop(1, 'rgba(245,158,11,0.01)');

    /* Dégradé pour messages envoyés (bleu) */
    const gradientBlue = ctx.createLinearGradient(0, 0, 0, 280);
    gradientBlue.addColorStop(0, 'rgba(74,127,167,0.25)');
    gradientBlue.addColorStop(1, 'rgba(74,127,167,0.01)');

    appState.chartRefs.messagerie = new Chart(ctx, {
        type: 'line',
        data: {
            labels: d.labels,
            datasets: [
                {
                    label: 'Messages reçus',        /* Courbe messages reçus */
                    data: d.messagesRecus,
                    borderColor: COULEURS.orange,
                    borderWidth: 2.5,
                    backgroundColor: gradientOrange, /* Fond dégradé orange */
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: COULEURS.orange,
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    order: 1                         /* Dessiné en dessous */
                },
                {
                    label: 'Messages envoyés',      /* Courbe messages envoyés */
                    data: d.messagesEnvoyes,
                    borderColor: COULEURS.blue,
                    borderWidth: 2.5,
                    backgroundColor: gradientBlue,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: COULEURS.blue,
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    order: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (items) => `💬 ${items[0].label}`,
                        label: (item) => ` ${item.dataset.label} : ${item.raw}`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: COULEURS.border, drawBorder: false },
                    ticks: { font: { size: 11 } }
                },
                y: {
                    grid: { color: COULEURS.border, drawBorder: false },
                    ticks: { font: { size: 11 }, beginAtZero: true },
                    min: 0
                }
            },
            animation: { duration: 1000 }
        }
    });
}


/* ============================================================
   11. TABLEAU RÉCAPITULATIF MENSUEL
   ============================================================ */

/**
 * Génère et injecte les lignes du tableau récapitulatif
 */
function genererTableauRecap() {
    const tbody = document.getElementById('recapTableBody');
    if (!tbody) return;

    tbody.innerHTML = ''; /* Vide le tableau avant reconstruction */

    const d = donneesCourantes; /* Données courantes */

    /* Pour chaque mois de la période */
    d.labels.forEach((label, i) => {
        const tr = document.createElement('tr'); /* Crée une ligne */

        /* Calcule le taux de complétion (exemple : consultations / (patients*2) * 100) */
        const taux = Math.min(100, Math.round(
            (d.vaccinsOK[i] / (d.vaccinsOK[i] + d.vaccinsRetard[i])) * 100
        ));

        /* Détermine la classe CSS selon le taux */
        const classTaux = taux >= 70 ? 'high' : taux >= 50 ? 'medium' : 'low';

        /* Couleur du taux */
        const couleurTaux = taux >= 70
            ? 'var(--color-teal)'     /* Vert : bon */
            : taux >= 50
                ? 'var(--color-orange)' /* Orange : moyen */
                : 'var(--color-red)';   /* Rouge : mauvais */

        tr.innerHTML = `
            <!-- Mois -->
            <td>${label.replace(' ★', ' <span style="color:var(--color-orange);font-size:11px">Actuel</span>')}</td>
            <!-- Nouveaux patients -->
            <td>${d.patients[i]}</td>
            <!-- Consultations -->
            <td>${d.consultations[i]}</td>
            <!-- Vaccins effectués (vert) -->
            <td style="color:var(--color-teal);font-weight:600">${d.vaccinsOK[i]}</td>
            <!-- Vaccins en retard (rouge) -->
            <td style="color:var(--color-red);font-weight:600">${d.vaccinsRetard[i]}</td>
            <!-- Messages reçus + envoyés -->
            <td>${d.messagesRecus[i] + d.messagesEnvoyes[i]}</td>
            <!-- Taux de complétion avec barre visuelle -->
            <td>
                <div class="completion-bar">
                    <div class="completion-bar-track">
                        <div class="completion-bar-fill ${classTaux}"
                             style="width:${taux}%"></div>
                    </div>
                    <span class="completion-pct" style="color:${couleurTaux}">${taux}%</span>
                </div>
            </td>
        `;

        tbody.appendChild(tr); /* Ajoute la ligne */
    });
}


/* ============================================================
   12. MISE À JOUR DE LA PÉRIODE
   ============================================================ */

/**
 * Met à jour tous les graphiques quand la période change
 * @param {number} mois - Nombre de mois (6, 12 ou 24)
 */
function changerPeriode(mois) {
    appState.periodeMois = mois;                /* Sauvegarde la période */
    donneesCourantes = genererDonnees(mois);    /* Régénère les données */

    /* Recrée tous les graphiques dépendants de la période */
    initChartPatientsMois();    /* Courbe patients */
    initChartVaccins();         /* Barres vaccins */
    initChartMessagerie();      /* Courbe messagerie */
    initSparklines();           /* Sparklines KPI */
    genererTableauRecap();      /* Tableau récap */

    afficherToast(`📊 Période mise à jour : ${mois} derniers mois`, 'success');
}


/* ============================================================
   13. EXPORT CSV
   ============================================================ */

/**
 * Génère et télécharge un fichier CSV des données analytiques
 */
function exporterCSV() {
    const d = donneesCourantes;

    /* En-tête du CSV */
    const entetes = [
        'Mois',
        'Nouveaux patients',
        'Consultations',
        'Vaccins effectués',
        'Vaccins en retard',
        'Messages reçus',
        'Messages envoyés'
    ].join(';'); /* Séparateur point-virgule (format Excel France) */

    /* Lignes de données */
    const lignes = d.labels.map((label, i) =>
        [
            label.replace(' ★', ''),  /* Nettoie le symbole ★ du mois actuel */
            d.patients[i],
            d.consultations[i],
            d.vaccinsOK[i],
            d.vaccinsRetard[i],
            d.messagesRecus[i],
            d.messagesEnvoyes[i]
        ].join(';')
    );

    /* Assemble le CSV complet */
    const csvContenu = [entetes, ...lignes].join('\n');

    /* Ajoute le BOM UTF-8 pour Excel (évite problèmes d'accents) */
    const blob = new Blob(['\uFEFF' + csvContenu], { type: 'text/csv;charset=utf-8;' });

    /* Crée un lien temporaire pour déclencher le téléchargement */
    const url    = URL.createObjectURL(blob);
    const lien   = document.createElement('a');
    lien.href     = url;
    lien.download = `analyses_medicare_${new Date().toISOString().slice(0,10)}.csv`; /* Nom avec date */
    lien.click();                       /* Simule le clic */
    URL.revokeObjectURL(url);           /* Libère la mémoire */

    afficherToast('✅ Export CSV téléchargé avec succès', 'success');
}


/* ============================================================
   14. TOAST NOTIFICATION
   ============================================================ */

/**
 * Affiche un message toast pendant 3 secondes
 * @param {string} message - Texte à afficher
 * @param {string} type    - 'success', 'error', 'info'
 */
function afficherToast(message, type = 'success') {
    const toast    = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMessage');
    const icone    = toast.querySelector('.toast-icon');

    /* Icônes selon le type */
    const icones   = { success: 'fa-solid fa-circle-check', error: 'fa-solid fa-circle-xmark', info: 'fa-solid fa-circle-info' };
    const couleurs = { success: '#10B981', error: '#EF4444', info: '#4A7FA7' };

    toastMsg.textContent = message;                           /* Injecte le message */
    icone.className = `toast-icon ${icones[type] || icones.success}`; /* Met l'icône */
    icone.style.color = couleurs[type] || couleurs.success;           /* Met la couleur */

    toast.classList.add('show');                              /* Affiche le toast */
    setTimeout(() => toast.classList.remove('show'), 3000);  /* Cache après 3s */
}


/* ============================================================
   15. INITIALISATION AU CHARGEMENT DU DOM
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ---- Animations KPI avec délai pour effet visuel ---- */
    setTimeout(lancerAnimationsKPI, 300); /* Démarre après 300ms */

    /* ---- Création des graphiques ---- */
    setTimeout(() => {
        initSparklines();          /* Sparklines cartes KPI */
        initChartPatientsMois();   /* Courbe patients */
        initChartPathologies();    /* Donut pathologies */
        initChartVaccins();        /* Barres vaccins */
        initChartMessagerie();     /* Courbe messagerie */
        genererTableauRecap();     /* Tableau récapitulatif */
    }, 200); /* Léger délai pour que les canvas soient dimensionnés */

    /* ---- Sélecteur de période ---- */
    document.querySelectorAll('.periode-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            /* Retire la classe active de tous les boutons */
            document.querySelectorAll('.periode-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');                    /* Active le bouton cliqué */
            changerPeriode(parseInt(this.dataset.periode)); /* Met à jour la période */
        });
    });

    /* ---- Bouton export principal ---- */
    document.getElementById('btnExport')?.addEventListener('click', exporterCSV);

    /* ---- Bouton export CSV tableau ---- */
    document.getElementById('btnExportCSV')?.addEventListener('click', exporterCSV);

    /* ---- Toggle type graphique patients (aire/ligne) ---- */
    let courbeType = 'line'; /* Type initial */
    document.getElementById('toggleCourbe')?.addEventListener('click', function() {
        const chart = appState.chartRefs.patientsMois;
        if (!chart) return;

        /* Bascule entre remplissage et pas de remplissage */
        const dataset = chart.data.datasets[0];
        dataset.fill = !dataset.fill;              /* Inverse le fill */
        chart.update();                            /* Rafraîchit le graphique */

        /* Change l'icône du bouton selon l'état */
        this.querySelector('i').className = dataset.fill
            ? 'fa-solid fa-chart-area'
            : 'fa-solid fa-chart-line';

        afficherToast(`Vue : ${dataset.fill ? 'Aire' : 'Ligne'}`, 'info');
    });

    /* ---- Cloche notifications ---- */
    document.getElementById('notifBtn')?.addEventListener('click', function() {
        this.querySelector('.notif-dot').style.display = 'none'; /* Cache le point rouge */
        afficherToast('📬 Toutes les notifications lues', 'info');
    });

    /* ---- Toggle sidebar mobile ---- */
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });

    /* ---- Fermeture sidebar mobile clic extérieur ---- */
    document.addEventListener('click', (e) => {
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.getElementById('sidebarToggle');
        if (window.innerWidth <= 768
            && sidebar.classList.contains('open')
            && !sidebar.contains(e.target)
            && !toggle.contains(e.target)) {
            sidebar.classList.remove('open'); /* Ferme la sidebar */
        }
    });

    /* ---- Redimensionnement : recalcule les graphiques ---- */
    let resizeTimeout; /* Débounce pour éviter trop d'appels */
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            /* Chart.js se redimensionne automatiquement, on force juste la mise à jour */
            Object.values(appState.chartRefs).forEach(chart => {
                if (chart && typeof chart.resize === 'function') {
                    chart.resize(); /* Force le recalcul de taille */
                }
            });
        }, 200);
    });
});