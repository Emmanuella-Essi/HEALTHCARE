// ============================================================
// PROFIL.JS — Gestion complète du profil patient
// Auteur : HealthCare App
// Description : Lecture, modification, affichage du profil
//               + changement de mot de passe + tabs modal
// ============================================================


// ============================================================
// 1. CONSTANTES — CLÉ DE STOCKAGE LOCAL
// ============================================================

// Clé utilisée pour sauvegarder le profil dans le navigateur (localStorage)
const STORAGE_KEY = 'healthcare_profil';


// ============================================================
// 2. PROFIL PAR DÉFAUT — Chargé si aucune donnée n'existe encore
// ============================================================

const DEFAULT_PROFIL = {
  prenom:      'Diane',               // Prénom du patient
  nom:         'Konan',              // Nom de famille
  email:       'diane.konan@email.ci', // Email de contact
  tel:         '+225 07 00 00 00 00', // Numéro de téléphone
  dob:         '2001-03-14',          // Date de naissance (format ISO YYYY-MM-DD)
  sexe:        'Féminin',             // Sexe du patient
  adresse:     'Cocody, Abidjan',     // Adresse postale

  blood:       'A+',                  // Groupe sanguin
  taille:      '165',                 // Taille en centimètres
  poids:       '58',                  // Poids en kilogrammes
  medecin:     'Dr. Yao Ama',         // Médecin traitant habituel
  allergies:   'Aucune connue',       // Allergies médicales connues
  antecedents: 'Aucun',              // Antécédents médicaux
  traitements: 'Aucun en cours',     // Traitements actuellement suivis

  urg_nom:     'Konan Paul',          // Nom du contact d'urgence
  urg_lien:    'Frère',              // Lien de parenté avec le contact
  urg_tel:     '+225 05 22 33 44 55', // Téléphone du contact d'urgence
  urg_email:   'paul.konan@email.ci'  // Email du contact d'urgence
};


// ============================================================
// 3. FONCTIONS UTILITAIRES
// ============================================================

/**
 * Récupère le profil depuis localStorage.
 * Si aucun profil n'existe, on sauvegarde et retourne le profil par défaut.
 */
function getProfil() {
  const s = localStorage.getItem(STORAGE_KEY); // On lit la valeur stockée dans le navigateur
  if (!s) {
    // Aucun profil trouvé : on initialise avec les données par défaut
    localStorage.setItem(STORAGE_KEY, JSON.stringify(DEFAULT_PROFIL));
    return DEFAULT_PROFIL; // On retourne directement l'objet par défaut
  }
  return JSON.parse(s); // On convertit le texte JSON en objet JavaScript utilisable
}

/**
 * Sauvegarde un objet profil dans le localStorage.
 * @param {Object} data - Les données du profil à sauvegarder
 */
function saveProfil(data) {
  // JSON.stringify convertit l'objet en texte pour pouvoir le stocker
  localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}

/**
 * Calcule l'âge du patient à partir de sa date de naissance.
 * @param {string} dobStr - Date de naissance au format "YYYY-MM-DD"
 * @returns {string} Âge en années, ex: "24 ans"
 */
function calcAge(dobStr) {
  if (!dobStr) return '—'; // Si pas de date fournie, on retourne un tiret
  const dob = new Date(dobStr); // Conversion de la chaîne en objet Date
  const now = new Date();        // Date d'aujourd'hui
  let age = now.getFullYear() - dob.getFullYear(); // Différence brute d'années
  const m = now.getMonth() - dob.getMonth();         // Différence de mois
  // Si l'anniversaire n'est pas encore passé cette année, on retire 1 an
  if (m < 0 || (m === 0 && now.getDate() < dob.getDate())) age--;
  return age + ' ans'; // On retourne l'âge sous forme de texte
}

/**
 * Formate une date ISO en format français lisible.
 * @param {string} str - Date au format "YYYY-MM-DD"
 * @returns {string} Ex: "14 mars 2001"
 */
function formatDateFR(str) {
  if (!str) return '—'; // Retourne un tiret si la date est vide
  // On ajoute T00:00:00 pour éviter les décalages de fuseau horaire
  const d = new Date(str + 'T00:00:00');
  return d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });
}

/**
 * Calcule l'IMC (Indice de Masse Corporelle).
 * Formule : poids (kg) / taille² (m)
 * @param {string|number} poids  - Poids en kg
 * @param {string|number} taille - Taille en cm
 * @returns {string} IMC formaté, ex: "21.3 kg/m²"
 */
function calcBMI(poids, taille) {
  if (!poids || !taille) return '—'; // Retourne tiret si données manquantes
  // parseFloat convertit la chaîne en nombre décimal
  const bmi = (parseFloat(poids) / Math.pow(parseFloat(taille) / 100, 2)).toFixed(1);
  return bmi + ' kg/m²'; // On ajoute l'unité
}

/**
 * Génère les initiales du patient pour l'avatar (ex: "DK" pour Diane Konan).
 * @param {string} prenom - Prénom
 * @param {string} nom    - Nom
 * @returns {string} Deux lettres majuscules
 */
function getInitials(prenom, nom) {
  // On prend la première lettre de chaque nom et on met en majuscule
  return ((prenom?.[0] || '') + (nom?.[0] || '')).toUpperCase() || 'KD';
}


// ============================================================
// 4. AFFICHAGE — Rendu de la page profil avec les données
// ============================================================

/**
 * Charge et affiche toutes les données du profil sur la page.
 * Appelée au chargement initial et après chaque modification.
 */
function renderPage() {
  const p        = getProfil();                          // Récupère le profil stocké
  const initials = getInitials(p.prenom, p.nom);        // Calcule les initiales
  const fullName = `${p.prenom} ${p.nom}`;              // Compose le nom complet
  const age      = calcAge(p.dob);                      // Calcule l'âge automatiquement

  // --- SECTION HERO (bannière en haut de la page) ---
  document.getElementById('heroAvatar').textContent = initials;        // Avatar principal (cercle)
  document.getElementById('topAvatar').textContent  = initials;        // Avatar dans la barre du haut
  document.getElementById('heroName').textContent   = fullName;        // Nom complet affiché
  document.getElementById('heroEmail').textContent  = p.email;         // Email affiché sous le nom
  document.getElementById('heroBlood').textContent  = `🩸 ${p.blood}`; // Tag groupe sanguin
  document.getElementById('heroAge').textContent    = `📅 ${age}`;     // Tag âge calculé
  document.getElementById('heroSex').textContent    = `👤 ${p.sexe}`;  // Tag sexe

  // --- EMAIL dans la section SÉCURITÉ (en bas de page) ---
  const secEl = document.getElementById('secEmail');
  if (secEl) secEl.textContent = p.email; // On affiche l'email dans la ligne sécurité

  // --- CARTE : Informations personnelles ---
  renderInfoList('infoPerso', [
    { label: 'Prénom',    val: p.prenom },
    { label: 'Nom',       val: p.nom },
    { label: 'Email',     val: p.email },
    { label: 'Téléphone', val: p.tel },
    { label: 'Naissance', val: formatDateFR(p.dob) }, // Formatée en français lisible
    { label: 'Sexe',      val: p.sexe },
    { label: 'Adresse',   val: p.adresse },
  ]);

  // --- CARTE : Informations médicales ---
  renderInfoList('infoMedical', [
    { label: 'Groupe sanguin',   val: p.blood },
    { label: 'Taille',           val: p.taille ? p.taille + ' cm' : '' }, // Ajoute l'unité cm
    { label: 'Poids',            val: p.poids  ? p.poids  + ' kg' : '' }, // Ajoute l'unité kg
    { label: 'IMC',              val: calcBMI(p.poids, p.taille) },        // Calculé automatiquement
    { label: 'Médecin traitant', val: p.medecin },
    { label: 'Allergies',        val: p.allergies },
    { label: 'Antécédents',      val: p.antecedents },
    { label: 'Traitements',      val: p.traitements },
  ]);

  // --- CARTE : Contact d'urgence ---
  renderInfoList('infoUrgence', [
    { label: 'Nom',       val: p.urg_nom },
    { label: 'Lien',      val: p.urg_lien },
    { label: 'Téléphone', val: p.urg_tel },
    { label: 'Email',     val: p.urg_email },
  ]);

  updateStats(); // Recharge les statistiques croisées depuis les autres modules
}

/**
 * Génère dynamiquement le HTML d'une liste d'informations dans une carte.
 * @param {string} containerId - L'ID de l'élément HTML cible
 * @param {Array}  items       - Tableau d'objets {label, val}
 */
function renderInfoList(containerId, items) {
  const el = document.getElementById(containerId); // Sélectionne le conteneur HTML
  if (!el) return; // Sécurité : si l'élément n'existe pas, on arrête sans erreur
  // On génère une ligne HTML par item et on les joint ensemble
  el.innerHTML = items.map(i => `
    <div class="info-item">
      <span class="info-label">${i.label}</span>
      <span class="info-val${!i.val || i.val === '—' ? ' empty' : ''}">${i.val || '—'}</span>
    </div>`).join('');
}


// ============================================================
// 5. STATISTIQUES CROISÉES — Lecture des autres modules
// ============================================================

/**
 * Lit les données de chaque module depuis le localStorage
 * et met à jour les 4 compteurs statistiques du profil.
 */
function updateStats() {
  // Lecture du module vaccins (retourne [] si vide ou non utilisé)
  const vaccins = JSON.parse(localStorage.getItem('healthcare_vaccins')     || '[]');
  // Lecture du module ordonnances
  const ordos   = JSON.parse(localStorage.getItem('healthcare_ordonnances') || '[]');
  // Lecture du module rendez-vous
  const rdv     = JSON.parse(localStorage.getItem('healthcare_rdv')         || '[]');
  // Lecture du module carnet de santé
  const carnet  = JSON.parse(localStorage.getItem('healthcare_carnet')      || '[]');

  // Affichage du nombre d'éléments dans chaque compteur stat
  document.getElementById('statVaccins').textContent = vaccins.length; // Nb vaccins
  document.getElementById('statOrdos').textContent   = ordos.length;   // Nb ordonnances
  document.getElementById('statRdv').textContent     = rdv.length;     // Nb rendez-vous
  document.getElementById('statCarnet').textContent  = carnet.length;  // Nb entrées carnet
}


// ============================================================
// 6. MODAL ÉDITION DU PROFIL
// ============================================================

/**
 * Ouvre la modal d'édition et pré-remplit tous les champs
 * avec les données actuelles du profil.
 */
function openEdit() {
  const p = getProfil(); // Récupère les données actuelles pour pré-remplir

  // --- Onglet PERSONNEL : champs texte et select ---
  document.getElementById('inPrenom').value  = p.prenom  || ''; // Champ prénom
  document.getElementById('inNom').value     = p.nom     || ''; // Champ nom
  document.getElementById('inEmail').value   = p.email   || ''; // Champ email
  document.getElementById('inTel').value     = p.tel     || ''; // Champ téléphone
  document.getElementById('inDob').value     = p.dob     || ''; // Champ date naissance
  document.getElementById('inSexe').value    = p.sexe    || 'Féminin'; // Select sexe
  document.getElementById('inAdresse').value = p.adresse || ''; // Champ adresse

  // --- Onglet MÉDICAL ---
  document.getElementById('inBlood').value       = p.blood       || 'A+'; // Select groupe sanguin
  document.getElementById('inTaille').value      = p.taille      || '';   // Champ taille
  document.getElementById('inPoids').value       = p.poids       || '';   // Champ poids
  document.getElementById('inMedecin').value     = p.medecin     || '';   // Champ médecin
  document.getElementById('inAllergies').value   = p.allergies   || '';   // Textarea allergies
  document.getElementById('inAntecedents').value = p.antecedents || '';   // Textarea antécédents
  document.getElementById('inTraitements').value = p.traitements || '';   // Textarea traitements

  // --- Onglet URGENCE ---
  document.getElementById('inUrgNom').value   = p.urg_nom   || ''; // Nom contact urgence
  document.getElementById('inUrgLien').value  = p.urg_lien  || ''; // Lien de parenté
  document.getElementById('inUrgTel').value   = p.urg_tel   || ''; // Téléphone urgence
  document.getElementById('inUrgEmail').value = p.urg_email || ''; // Email urgence

  setTab('perso'); // On remet toujours l'onglet "Personnel" en premier à l'ouverture
  document.getElementById('editOverlay').classList.add('open'); // Affiche la modal (retire display:none)
}

/**
 * Ferme la modal d'édition sans sauvegarder les modifications.
 */
function closeEdit() {
  document.getElementById('editOverlay').classList.remove('open'); // Masque la modal
}

/**
 * Collecte les valeurs saisies, valide les champs obligatoires,
 * puis sauvegarde le profil mis à jour.
 */
function saveEdit() {
  const p = getProfil(); // On récupère d'abord le profil existant

  // On construit un nouvel objet en fusionnant l'existant avec les nouvelles valeurs
  const updated = {
    ...p, // Copie tous les champs existants (spread operator) pour ne rien perdre

    // --- Champs de l'onglet Personnel ---
    prenom:      document.getElementById('inPrenom').value.trim(),   // .trim() enlève les espaces
    nom:         document.getElementById('inNom').value.trim(),
    email:       document.getElementById('inEmail').value.trim(),
    tel:         document.getElementById('inTel').value.trim(),
    dob:         document.getElementById('inDob').value,              // Pas de trim sur les dates
    sexe:        document.getElementById('inSexe').value,             // Valeur du select
    adresse:     document.getElementById('inAdresse').value.trim(),

    // --- Champs de l'onglet Médical ---
    blood:       document.getElementById('inBlood').value,            // Valeur du select
    taille:      document.getElementById('inTaille').value,
    poids:       document.getElementById('inPoids').value,
    medecin:     document.getElementById('inMedecin').value.trim(),
    allergies:   document.getElementById('inAllergies').value.trim(),
    antecedents: document.getElementById('inAntecedents').value.trim(),
    traitements: document.getElementById('inTraitements').value.trim(),

    // --- Champs de l'onglet Urgence ---
    urg_nom:     document.getElementById('inUrgNom').value.trim(),
    urg_lien:    document.getElementById('inUrgLien').value.trim(),
    urg_tel:     document.getElementById('inUrgTel').value.trim(),
    urg_email:   document.getElementById('inUrgEmail').value.trim(),
  };

  // Validation : les 3 champs obligatoires ne peuvent pas être vides
  if (!updated.prenom || !updated.nom || !updated.email) {
    showToast('Prénom, nom et email sont obligatoires', 'error'); // Alerte utilisateur
    return; // On stoppe la fonction — rien n'est sauvegardé
  }

  saveProfil(updated); // Sauvegarde l'objet mis à jour dans le localStorage
  closeEdit();         // Ferme la modal d'édition
  renderPage();        // Recharge l'affichage avec les nouvelles données
  showToast('Profil mis à jour ✅', 'success'); // Confirmation verte en bas de page
}


// ============================================================
// 7. SYSTÈME D'ONGLETS (TABS) dans la modal d'édition
// ============================================================

/**
 * Active un onglet et affiche le panneau correspondant.
 * Désactive tous les autres onglets et panneaux.
 * @param {string} name - Nom de l'onglet : 'perso', 'medical' ou 'urgence'
 */
function setTab(name) {
  // Boucle sur tous les boutons .tab pour activer uniquement celui demandé
  document.querySelectorAll('.tab').forEach(function (t) {
    t.classList.toggle('active', t.dataset.tab === name); // true si correspond, false sinon
  });
  // Boucle sur tous les .tab-content pour afficher uniquement le bon panneau
  document.querySelectorAll('.tab-content').forEach(function (c) {
    c.classList.toggle('active', c.id === 'tab-' + name); // Ex: id="tab-perso"
  });
}


// ============================================================
// 8. MODAL CHANGEMENT DE MOT DE PASSE
// ============================================================

/**
 * Ouvre la modal de changement de mot de passe.
 */
function openPwd() {
  document.getElementById('pwdOverlay').classList.add('open'); // Affiche la modal MDP
}

/**
 * Ferme la modal mot de passe et vide tous les champs pour la prochaine ouverture.
 */
function closePwd() {
  document.getElementById('pwdOverlay').classList.remove('open'); // Masque la modal
  document.getElementById('inPwdOld').value     = ''; // Vide le champ ancien MDP
  document.getElementById('inPwdNew').value     = ''; // Vide le champ nouveau MDP
  document.getElementById('inPwdConfirm').value = ''; // Vide le champ confirmation
  document.getElementById('pwdStrength').textContent = ''; // Efface l'indicateur de force
}

/**
 * Valide les champs du formulaire mot de passe et confirme le changement.
 * (Dans un vrai projet : envoyer une requête AJAX vers le serveur PHP)
 */
function savePwd() {
  const oldPwd  = document.getElementById('inPwdOld').value;     // Ancien mot de passe saisi
  const newPwd  = document.getElementById('inPwdNew').value;     // Nouveau mot de passe saisi
  const confirm = document.getElementById('inPwdConfirm').value; // Confirmation du nouveau MDP

  // Vérification 1 : tous les champs doivent être remplis
  if (!oldPwd || !newPwd || !confirm) {
    showToast('Remplissez tous les champs', 'error');
    return; // On stoppe si un champ est vide
  }

  // Vérification 2 : le nouveau MDP et la confirmation doivent être identiques
  if (newPwd !== confirm) {
    showToast('Les mots de passe ne correspondent pas', 'error');
    return; // On stoppe si les deux ne correspondent pas
  }

  // Vérification 3 : le nouveau MDP doit faire au moins 6 caractères
  if (newPwd.length < 6) {
    showToast('Minimum 6 caractères requis', 'error');
    return; // On stoppe si trop court
  }

  // Tout est valide — dans un vrai projet : requête AJAX vers PHP pour changer en BDD
  closePwd(); // Ferme la modal proprement
  showToast('Mot de passe mis à jour ✅', 'success'); // Confirmation verte
}


// ============================================================
// 9. INDICATEUR DE FORCE DU MOT DE PASSE (temps réel)
// ============================================================

/**
 * Configure l'écoute sur le champ "nouveau mot de passe"
 * pour calculer et afficher la force en temps réel.
 * Appelée depuis DOMContentLoaded pour s'assurer que le DOM est prêt.
 */
function setupPasswordStrength() {
  const input = document.getElementById('inPwdNew'); // Champ du nouveau mot de passe
  if (!input) return; // Sécurité : si le champ n'est pas trouvé, on arrête

  input.addEventListener('input', function () {
    const val = this.value; // Valeur saisie à chaque frappe
    const el  = document.getElementById('pwdStrength'); // Div d'affichage de la force

    if (!val) {
      el.textContent = ''; // Si le champ est vide, on efface l'indicateur
      return;
    }

    // Calcul du score : chaque critère rempli ajoute 1 point (max 4)
    let score = 0;
    if (val.length >= 8)          score++; // +1 point si au moins 8 caractères
    if (/[A-Z]/.test(val))        score++; // +1 point si une lettre majuscule
    if (/[0-9]/.test(val))        score++; // +1 point si un chiffre
    if (/[^A-Za-z0-9]/.test(val)) score++; // +1 point si un caractère spécial (!@#$...)

    // Correspondance score → niveau affiché
    const levels = [
      { label: '🔴 Très faible', color: '#EF4444' }, // score = 0
      { label: '🟠 Faible',      color: '#F97316' }, // score = 1
      { label: '🟡 Moyen',       color: '#F59E0B' }, // score = 2
      { label: '🟢 Fort',        color: '#10B981' }, // score = 3
      { label: '💪 Très fort',   color: '#047857' }, // score = 4
    ];

    const level = levels[Math.min(score, 4)]; // Math.min évite de dépasser l'index 4
    el.textContent = level.label; // On affiche le texte du niveau
    el.style.color = level.color; // On applique la couleur correspondante
  });
}


// ============================================================
// 10. TOGGLE VISIBILITÉ MOT DE PASSE (bouton œil)
// ============================================================

/**
 * Bascule un champ mot de passe entre masqué (●●●) et visible (abc).
 * Appelée directement depuis l'attribut onclick dans le HTML.
 * @param {string}      inputId - ID du champ input à basculer
 * @param {HTMLElement} btn     - Bouton cliqué (pour changer son emoji)
 */
function togglePwd(inputId, btn) {
  const input = document.getElementById(inputId); // On sélectionne le champ par son ID
  if (input.type === 'password') {
    input.type = 'text';      // On rend le texte visible
    btn.textContent = '🙈';   // On change l'icône en "cacher"
  } else {
    input.type = 'password';  // On remasque le texte
    btn.textContent = '👁️';  // On remet l'icône "afficher"
  }
}


// ============================================================
// 11. TOAST — Notification visuelle temporaire
// ============================================================

/**
 * Affiche un message de notification en bas de l'écran pendant 3 secondes.
 * @param {string} msg  - Texte du message à afficher
 * @param {string} type - 'success' (vert) | 'error' (rouge) | '' (bleu foncé)
 */
function showToast(msg, type) {
  type = type || ''; // Valeur par défaut si non fourni
  const t = document.getElementById('toast'); // Sélectionne l'élément toast
  t.textContent = msg;                         // Définit le texte du message
  t.className = 'toast show ' + type;          // Ajoute les classes CSS (affichage + couleur)
  setTimeout(function () {
    t.className = 'toast'; // Après 3 secondes, on retire la classe 'show' → disparaît
  }, 3000);
}


// ============================================================
// 12. INITIALISATION GÉNÉRALE
// ============================================================

/**
 * Point d'entrée principal de tout le script.
 *
 * CORRECTION IMPORTANTE : tout le code qui accède au DOM
 * (getElementById, addEventListener, querySelectorAll...)
 * DOIT être placé ici, dans DOMContentLoaded.
 * Sinon les éléments HTML ne sont pas encore disponibles
 * quand le script s'exécute → erreurs "null" et boutons qui ne réagissent pas.
 */
document.addEventListener('DOMContentLoaded', function () {

  // === AFFICHAGE INITIAL ===
  renderPage(); // Charge et affiche toutes les données du profil au chargement

  // === INDICATEUR DE FORCE MDP ===
  setupPasswordStrength(); // Active l'écouteur sur le champ nouveau mot de passe

  // === SIDEBAR MOBILE (bouton hamburger ☰) ===
  var menuToggle = document.getElementById('menuToggle');
  if (menuToggle) {
    // Au clic sur le bouton ☰, on ajoute/retire la classe 'open' de la sidebar
    menuToggle.addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('open');
    });
  }

  // ==================================================
  // MODAL ÉDITION DU PROFIL
  // ==================================================

  // Bouton "✏️ Modifier le profil" dans le hero → ouvre la modal
  document.getElementById('openEditBtn').addEventListener('click', openEdit);

  // Croix "✕" en haut à droite de la modal → ferme sans sauvegarder
  document.getElementById('editClose').addEventListener('click', closeEdit);

  // Bouton "Annuler" en bas de la modal → ferme sans sauvegarder
  document.getElementById('editCancel').addEventListener('click', closeEdit);

  // Bouton "💾 Enregistrer" → collecte les données et sauvegarde
  document.getElementById('editSave').addEventListener('click', saveEdit);

  // Clic sur le fond sombre (overlay) derrière la modal → ferme la modal
  document.getElementById('editOverlay').addEventListener('click', function (e) {
    // e.target est l'élément cliqué ; "this" est l'overlay lui-même
    // On ferme SEULEMENT si on clique sur l'overlay, pas sur la modal
    if (e.target === this) closeEdit();
  });

  // ==================================================
  // ONGLETS (TABS) dans la modal d'édition
  // ==================================================

  // On parcourt tous les boutons avec la classe .tab
  document.querySelectorAll('.tab').forEach(function (tab) {
    tab.addEventListener('click', function () {
      setTab(this.dataset.tab); // data-tab="perso" / "medical" / "urgence"
    });
  });

  // ==================================================
  // MODAL CHANGEMENT DE MOT DE PASSE
  // ==================================================

  // Bouton "Changer" dans la section sécurité → ouvre la modal MDP
  document.getElementById('openPwdBtn').addEventListener('click', openPwd);

  // Croix "✕" de la modal MDP → ferme et vide les champs
  document.getElementById('pwdClose').addEventListener('click', closePwd);

  // Bouton "Annuler" de la modal MDP → ferme et vide les champs
  document.getElementById('pwdCancel').addEventListener('click', closePwd);

  // Bouton "Enregistrer" de la modal MDP → valide et confirme
  document.getElementById('pwdSave').addEventListener('click', savePwd);

  // Clic sur le fond sombre de la modal MDP → ferme la modal
  document.getElementById('pwdOverlay').addEventListener('click', function (e) {
    if (e.target === this) closePwd(); // Ferme uniquement si on clique sur l'overlay
  });

}); // ← Fin de DOMContentLoaded — tout le code DOM est protégé ici