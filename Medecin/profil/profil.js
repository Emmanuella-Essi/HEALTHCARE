/* ============================================================
   PROFIL MÉDECIN — JavaScript
   Fichier     : profil.js
   Description : Toute l'interactivité de la page profil :
                 onglets, formulaires, photo, mot de passe,
                 validation, force du mdp, toast
   ============================================================ */

"use strict";  /* Mode strict : détecte les erreurs silencieuses */


/* ============================================================
   1. RÉFÉRENCES DOM — Tous les éléments HTML utilisés
   ============================================================ */

const DOM = {
  /* ---- Navigation ---- */
  sidebar:        document.getElementById("sidebar"),          /* Sidebar latérale */
  menuToggle:     document.getElementById("menuToggle"),       /* Bouton hamburger */
  sidebarOverlay: document.getElementById("sidebarOverlay"),   /* Overlay mobile */

  /* ---- Onglets ---- */
  tabBtns:   document.querySelectorAll(".tab-btn"),    /* Tous les boutons d'onglet */
  tabPanels: document.querySelectorAll(".tab-panel"),  /* Tous les panneaux */

  /* ---- Photo de profil ---- */
  photoEditBtn: document.getElementById("photoEditBtn"),  /* Bouton crayon photo */
  photoInput:   document.getElementById("photoInput"),    /* Input file caché */
  photoAvatar:  document.getElementById("photoAvatar"),   /* Cercle avatar */
  photoInitials:document.getElementById("photoInitials"), /* Span initiales */
  photoImg:     document.getElementById("photoImg"),      /* Balise img de la photo */

  /* ---- Infos affichées dynamiquement ---- */
  displayName: document.getElementById("displayName"),  /* Nom dans la carte identité */
  displaySpec: document.getElementById("displaySpec"),  /* Spécialité dans la carte */
  qEmail:      document.getElementById("qEmail"),       /* Email dans infos rapides */
  qTel:        document.getElementById("qTel"),         /* Téléphone dans infos rapides */
  qHopital:    document.getElementById("qHopital"),     /* Hôpital dans infos rapides */
  qVille:      document.getElementById("qVille"),       /* Ville dans infos rapides */
  qOrdre:      document.getElementById("qOrdre"),       /* N° ordre dans infos rapides */

  /* ---- Formulaire Infos Personnelles ---- */
  formInfos:     document.getElementById("formInfos"),        /* Formulaire */
  btnEditInfos:  document.getElementById("btnEditInfos"),     /* Bouton Modifier */
  btnCancelInfos:document.getElementById("btnCancelInfos"),   /* Bouton Annuler */
  actionsInfos:  document.getElementById("actionsInfos"),     /* Div boutons action */
  infPrenom:     document.getElementById("infPrenom"),        /* Champ prénom */
  infNom:        document.getElementById("infNom"),           /* Champ nom */
  infEmail:      document.getElementById("infEmail"),         /* Champ email */
  infTel:        document.getElementById("infTel"),           /* Champ téléphone */
  infBio:        document.getElementById("infBio"),           /* Textarea biographie */
  bioCount:      document.getElementById("bioCount"),         /* Compteur caractères bio */

  /* ---- Formulaire Professionnel ---- */
  formPro:      document.getElementById("formPro"),          /* Formulaire pro */
  btnEditPro:   document.getElementById("btnEditPro"),       /* Bouton Modifier pro */
  btnCancelPro: document.getElementById("btnCancelPro"),     /* Bouton Annuler pro */
  actionsPro:   document.getElementById("actionsPro"),       /* Div boutons pro */
  proSpec:      document.getElementById("proSpec"),          /* Sélect spécialité */
  proHopital:   document.getElementById("proHopital"),       /* Champ hôpital */
  proVille:     document.getElementById("proVille"),         /* Champ ville */
  proLangues:   document.getElementById("proLangues"),       /* Champ langues */
  languesTags:  document.getElementById("languesTags"),      /* Zone tags langues */

  /* ---- Formulaire Mot de Passe ---- */
  formMdp:       document.getElementById("formMdp"),         /* Formulaire mdp */
  mdpActuel:     document.getElementById("mdpActuel"),        /* Champ mdp actuel */
  mdpNouveau:    document.getElementById("mdpNouveau"),       /* Champ nouveau mdp */
  mdpConfirm:    document.getElementById("mdpConfirm"),       /* Champ confirmation */
  btnResetMdp:   document.getElementById("btnResetMdp"),      /* Bouton réinitialiser */
  strengthBarWrap: document.getElementById("strengthBarWrap"),/* Wrap barre force */
  strengthBar:   document.getElementById("strengthBar"),      /* Barre de force */
  strengthText:  document.getElementById("strengthText"),     /* Texte force */
  strengthBadge: document.getElementById("strengthBadge"),    /* Badge en-tête */
  strengthLabel: document.getElementById("strengthLabel"),    /* Texte badge */
  matchIndicator:document.getElementById("matchIndicator"),   /* Indicateur match */
  matchText:     document.getElementById("matchText"),        /* Texte match */
  pwdCriteria:   document.querySelectorAll(".crit"),          /* Critères mdp */

  /* ---- Erreurs de validation ---- */
  errActuel:  document.getElementById("errActuel"),    /* Erreur mdp actuel */
  errNouveau: document.getElementById("errNouveau"),   /* Erreur nouveau mdp */
  errConfirm: document.getElementById("errConfirm"),   /* Erreur confirmation */

  /* ---- Toast ---- */
  toast:    document.getElementById("toast"),    /* Conteneur toast */
  toastMsg: document.getElementById("toastMsg"), /* Message du toast */
  toastIcon:document.getElementById("toastIcon"),/* Icône du toast */
};


/* ============================================================
   2. INITIALISATION — Au chargement du DOM
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {
  /* Initialiser la gestion des onglets */
  initOnglets();

  /* Initialiser la gestion de la photo de profil */
  initPhoto();

  /* Initialiser le formulaire infos personnelles */
  initFormInfos();

  /* Initialiser le formulaire professionnel */
  initFormPro();

  /* Initialiser le formulaire mot de passe */
  initFormMdp();

  /* Initialiser le sidebar mobile */
  initSidebarMobile();

  /* Initialiser le compteur de caractères de la bio */
  initBioCounter();

  /* Initialiser les tags de langues */
  renderTagsLangues();
});


/* ============================================================
   3. GESTION DES ONGLETS
   ============================================================ */

/**
 * Initialise les onglets de navigation entre les 3 panneaux
 * Gère l'accessibilité avec aria-selected
 */
function initOnglets() {
  DOM.tabBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const cible = btn.dataset.tab;  /* Nom du panneau cible */

      /* Désactiver tous les boutons et panneaux */
      DOM.tabBtns.forEach((b) => {
        b.classList.remove("active");
        b.setAttribute("aria-selected", "false");
      });

      DOM.tabPanels.forEach((p) => {
        p.classList.remove("active");
        p.hidden = true;  /* Accessibilité : masquer pour les lecteurs d'écran */
      });

      /* Activer le bouton cliqué */
      btn.classList.add("active");
      btn.setAttribute("aria-selected", "true");

      /* Afficher le panneau correspondant */
      const panneau = document.getElementById(`panel-${cible}`);
      if (panneau) {
        panneau.classList.add("active");
        panneau.hidden = false;
        /* Petit scroll vers le haut du panneau */
        panneau.scrollIntoView({ behavior: "smooth", block: "nearest" });
      }
    });
  });
}


/* ============================================================
   4. PHOTO DE PROFIL — Upload et aperçu
   ============================================================ */

/**
 * Initialise le bouton de changement de photo
 * Permet d'uploader une image et d'afficher l'aperçu immédiatement
 */
function initPhoto() {
  /* Clic sur le bouton crayon → déclenche le sélecteur de fichier */
  if (DOM.photoEditBtn) {
    DOM.photoEditBtn.addEventListener("click", () => {
      DOM.photoInput.click();  /* Simuler un clic sur l'input file caché */
    });
  }

  /* Quand un fichier est sélectionné */
  if (DOM.photoInput) {
    DOM.photoInput.addEventListener("change", (e) => {
      const fichier = e.target.files[0];  /* Premier fichier sélectionné */

      /* Vérifier qu'un fichier a bien été choisi */
      if (!fichier) return;

      /* Vérifier que c'est bien une image */
      if (!fichier.type.startsWith("image/")) {
        afficherToast("❌ Veuillez choisir un fichier image (JPG, PNG, etc.)", "error");
        return;
      }

      /* Vérifier la taille (max 5 Mo) */
      const maxSize = 5 * 1024 * 1024;  /* 5 Mo en octets */
      if (fichier.size > maxSize) {
        afficherToast("❌ La photo ne doit pas dépasser 5 Mo", "error");
        return;
      }

      /* Lire le fichier et l'afficher en prévisualisation */
      const reader = new FileReader();

      reader.onload = (event) => {
        /* Masquer les initiales */
        DOM.photoInitials.style.display = "none";

        /* Afficher l'image uploadée */
        DOM.photoImg.src   = event.target.result;
        DOM.photoImg.style.display = "block";

        afficherToast("✅ Photo mise à jour avec succès !");
        /* En production : envoyer l'image au serveur via FormData + fetch */
      };

      reader.readAsDataURL(fichier);  /* Lire comme URL base64 */
    });
  }
}


/* ============================================================
   5. FORMULAIRE INFOS PERSONNELLES — Édition et sauvegarde
   ============================================================ */

/**
 * État du formulaire infos personnelles
 * Garde les valeurs originales pour pouvoir annuler
 */
let originalInfos = {};

/**
 * Initialise le formulaire des informations personnelles
 */
function initFormInfos() {

  /* Mémoriser les valeurs initiales (pour l'annulation) */
  sauvegarderValeurs("infos");

  /* Clic sur le bouton "Modifier" */
  if (DOM.btnEditInfos) {
    DOM.btnEditInfos.addEventListener("click", () => {
      activerEdition("infos");
    });
  }

  /* Clic sur le bouton "Annuler" */
  if (DOM.btnCancelInfos) {
    DOM.btnCancelInfos.addEventListener("click", () => {
      restaurerValeurs("infos");
      desactiverEdition("infos");
    });
  }

  /* Soumission du formulaire infos */
  if (DOM.formInfos) {
    DOM.formInfos.addEventListener("submit", (e) => {
      e.preventDefault();  /* Empêcher le rechargement de page */
      sauvegarderFormInfos();
    });
  }
}

/**
 * Sauvegarde les valeurs actuelles des champs pour pouvoir annuler
 * @param {string} panneau - "infos" ou "pro"
 */
function sauvegarderValeurs(panneau) {
  const champs = document.querySelectorAll(`#form${capitaliser(panneau)} input, #form${capitaliser(panneau)} select, #form${capitaliser(panneau)} textarea`);
  const backup = {};

  champs.forEach((champ) => {
    /* Sauvegarder par nom ou id */
    const cle = champ.id || champ.name;
    if (cle) {
      /* Pour les checkboxes, sauvegarder checked ; pour les autres, value */
      backup[cle] = champ.type === "checkbox" ? champ.checked : champ.value;
    }
  });

  /* Stocker dans la variable globale correspondante */
  if (panneau === "infos")  originalInfos = backup;
  if (panneau === "pro")    originalPro   = backup;
}

/**
 * Restaure les valeurs sauvegardées (annulation)
 * @param {string} panneau - "infos" ou "pro"
 */
function restaurerValeurs(panneau) {
  const backup = panneau === "infos" ? originalInfos : originalPro;
  const champs = document.querySelectorAll(`#form${capitaliser(panneau)} input, #form${capitaliser(panneau)} select, #form${capitaliser(panneau)} textarea`);

  champs.forEach((champ) => {
    const cle = champ.id || champ.name;
    if (cle && backup[cle] !== undefined) {
      if (champ.type === "checkbox") {
        champ.checked = backup[cle];
      } else {
        champ.value = backup[cle];
      }
    }
  });
}

/**
 * Active le mode édition d'un formulaire
 * @param {string} panneau - "infos" ou "pro"
 */
function activerEdition(panneau) {
  /* Sauvegarder les valeurs actuelles avant édition */
  sauvegarderValeurs(panneau);

  /* Activer tous les champs du formulaire */
  const champs = document.querySelectorAll(`#form${capitaliser(panneau)} input:not([type=hidden]), #form${capitaliser(panneau)} select, #form${capitaliser(panneau)} textarea`);
  champs.forEach((c) => { c.disabled = false; });

  /* Afficher les boutons Enregistrer/Annuler */
  const actionsEl = document.getElementById(`actions${capitaliser(panneau)}`);
  if (actionsEl) actionsEl.style.display = "flex";

  /* Mettre à jour le bouton Modifier */
  const btnEdit = document.getElementById(`btnEdit${capitaliser(panneau)}`);
  if (btnEdit) {
    btnEdit.classList.add("editing");
    btnEdit.innerHTML = `
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
      Annuler
    `;
    /* Remplacer le handler pour basculer en annulation */
    btnEdit.onclick = () => {
      restaurerValeurs(panneau);
      desactiverEdition(panneau);
    };
  }
}

/**
 * Désactive le mode édition (retour en lecture)
 * @param {string} panneau - "infos" ou "pro"
 */
function desactiverEdition(panneau) {
  /* Désactiver tous les champs */
  const champs = document.querySelectorAll(`#form${capitaliser(panneau)} input:not([type=hidden]), #form${capitaliser(panneau)} select, #form${capitaliser(panneau)} textarea`);
  champs.forEach((c) => { c.disabled = true; });

  /* Masquer les boutons action */
  const actionsEl = document.getElementById(`actions${capitaliser(panneau)}`);
  if (actionsEl) actionsEl.style.display = "none";

  /* Restaurer le bouton Modifier */
  const btnEdit = document.getElementById(`btnEdit${capitaliser(panneau)}`);
  if (btnEdit) {
    btnEdit.classList.remove("editing");
    btnEdit.innerHTML = `
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
      </svg>
      Modifier
    `;
    /* Réattacher le handler original */
    btnEdit.onclick = () => activerEdition(panneau);
  }
}

/**
 * Sauvegarde les infos personnelles et met à jour l'interface
 */
function sauvegarderFormInfos() {
  const prenom = DOM.infPrenom?.value.trim();
  const nom    = DOM.infNom?.value.trim();
  const email  = DOM.infEmail?.value.trim();

  /* Validation minimale */
  if (!prenom || !nom) {
    afficherToast("❌ Le prénom et le nom sont obligatoires", "error");
    return;
  }
  if (email && !validerEmail(email)) {
    afficherToast("❌ L'adresse email n'est pas valide", "error");
    return;
  }

  /* Mettre à jour l'affichage de la carte identité */
  if (DOM.displayName) DOM.displayName.textContent = `Dr. ${prenom} ${nom}`;
  if (DOM.qEmail && email) DOM.qEmail.textContent = email;
  if (DOM.qTel && DOM.infTel?.value) DOM.qTel.textContent = DOM.infTel.value;

  /* Désactiver le mode édition */
  desactiverEdition("infos");

  afficherToast("✅ Informations personnelles sauvegardées !");

  /* En production : envoyer via fetch/AJAX au serveur PHP */
  /* envoyerDonnees('api/profil.php', { prenom, nom, email, ... }); */
}


/* ============================================================
   6. FORMULAIRE PROFESSIONNEL
   ============================================================ */

let originalPro = {};  /* Backup des valeurs pro */

/**
 * Initialise le formulaire des informations professionnelles
 */
function initFormPro() {
  sauvegarderValeurs("pro");

  if (DOM.btnEditPro) {
    DOM.btnEditPro.addEventListener("click", () => activerEdition("pro"));
  }

  if (DOM.btnCancelPro) {
    DOM.btnCancelPro.addEventListener("click", () => {
      restaurerValeurs("pro");
      desactiverEdition("pro");
    });
  }

  if (DOM.formPro) {
    DOM.formPro.addEventListener("submit", (e) => {
      e.preventDefault();
      sauvegarderFormPro();
    });
  }

  /* Mise à jour des tags langues à chaque frappe */
  if (DOM.proLangues) {
    DOM.proLangues.addEventListener("input", renderTagsLangues);
  }
}

/**
 * Sauvegarde le formulaire professionnel
 */
function sauvegarderFormPro() {
  const spec    = DOM.proSpec?.value;
  const hopital = DOM.proHopital?.value.trim();
  const ville   = DOM.proVille?.value.trim();

  /* Mettre à jour la carte identité (spécialité) */
  const specTexts = {
    generaliste:  "Médecin Généraliste",
    cardiologue:  "Cardiologue",
    pediatre:     "Pédiatre",
    dermatologue: "Dermatologue",
    gynecologue:  "Gynécologue",
    neurologue:   "Neurologue",
    orl:          "ORL",
    ophtalmologue:"Ophtalmologue",
    psychiatre:   "Psychiatre",
    chirurgien:   "Chirurgien",
    urgentiste:   "Urgentiste",
  };
  if (DOM.displaySpec && spec) DOM.displaySpec.textContent = specTexts[spec] || spec;

  /* Mettre à jour les infos rapides */
  if (DOM.qHopital && hopital) DOM.qHopital.textContent = hopital;
  if (DOM.qVille   && ville)   DOM.qVille.textContent   = `${ville}, Côte d'Ivoire`;

  desactiverEdition("pro");
  renderTagsLangues();  /* Régénérer les tags langues */

  afficherToast("✅ Informations professionnelles sauvegardées !");
}

/**
 * Affiche les langues saisies sous forme de tags colorés
 */
function renderTagsLangues() {
  if (!DOM.languesTags || !DOM.proLangues) return;

  const valeur = DOM.proLangues.value;
  /* Séparer par virgule, nettoyer les espaces */
  const langues = valeur.split(",").map((l) => l.trim()).filter(Boolean);

  /* Générer un tag pour chaque langue */
  DOM.languesTags.innerHTML = langues
    .map((l) => `<span class="tag-pill">${l}</span>`)
    .join("");
}


/* ============================================================
   7. FORMULAIRE MOT DE PASSE — Validation + Force
   ============================================================ */

/**
 * Initialise tout le formulaire de changement de mot de passe :
 * - Afficher/masquer les mots de passe
 * - Indicateur de force en temps réel
 * - Vérification des critères
 * - Validation à la soumission
 */
function initFormMdp() {

  /* ---- Boutons afficher/masquer (yeux) ---- */
  const toggleBtns = document.querySelectorAll(".toggle-pass");
  toggleBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetId = btn.dataset.target;
      const input    = document.getElementById(targetId);
      if (!input) return;

      /* Basculer entre "password" et "text" */
      const estMasque = input.type === "password";
      input.type = estMasque ? "text" : "password";

      /* Basculer les icônes œil */
      const eyeShow = btn.querySelector(".eye-show");
      const eyeHide = btn.querySelector(".eye-hide");
      if (eyeShow) eyeShow.style.display = estMasque ? "none"  : "block";
      if (eyeHide) eyeHide.style.display = estMasque ? "block" : "none";
    });
  });

  /* ---- Analyse du nouveau mot de passe en temps réel ---- */
  if (DOM.mdpNouveau) {
    DOM.mdpNouveau.addEventListener("input", () => {
      const valeur = DOM.mdpNouveau.value;

      /* Afficher la barre de force si le champ a du contenu */
      if (DOM.strengthBarWrap) {
        DOM.strengthBarWrap.style.display = valeur.length > 0 ? "flex" : "none";
      }
      if (DOM.strengthBadge) {
        DOM.strengthBadge.style.display = valeur.length > 0 ? "block" : "none";
      }

      /* Calculer et afficher la force */
      analyserForceMdp(valeur);

      /* Vérifier les critères */
      verifierCriteres(valeur);

      /* Vérifier la correspondance si confirmation déjà remplie */
      if (DOM.mdpConfirm && DOM.mdpConfirm.value) {
        verifierCorrespondance();
      }

      /* Effacer les messages d'erreur */
      if (DOM.errNouveau) DOM.errNouveau.textContent = "";
    });
  }

  /* ---- Vérification de la correspondance à la frappe ---- */
  if (DOM.mdpConfirm) {
    DOM.mdpConfirm.addEventListener("input", () => {
      verifierCorrespondance();
      if (DOM.errConfirm) DOM.errConfirm.textContent = "";
    });
  }

  /* ---- Bouton Réinitialiser ---- */
  if (DOM.btnResetMdp) {
    DOM.btnResetMdp.addEventListener("click", reinitialiserFormMdp);
  }

  /* ---- Soumission du formulaire ---- */
  if (DOM.formMdp) {
    DOM.formMdp.addEventListener("submit", (e) => {
      e.preventDefault();
      validerEtSauvegarderMdp();
    });
  }
}

/**
 * Analyse la force du mot de passe et met à jour l'interface
 * @param {string} mdp - Le mot de passe à analyser
 * @returns {number}   - Score de 0 à 4
 */
function analyserForceMdp(mdp) {
  let score = 0;

  /* Critère 1 : longueur ≥ 8 */
  if (mdp.length >= 8) score++;

  /* Critère 2 : au moins 1 majuscule */
  if (/[A-Z]/.test(mdp)) score++;

  /* Critère 3 : au moins 1 chiffre */
  if (/\d/.test(mdp)) score++;

  /* Critère 4 : au moins 1 caractère spécial */
  if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(mdp)) score++;

  /* Niveaux et labels de force */
  const niveaux = [
    { classe: "weak",   label: "Faible",    couleur: "#EF4444" },
    { classe: "fair",   label: "Moyen",     couleur: "#F59E0B" },
    { classe: "good",   label: "Fort",      couleur: "#0A8C74" },
    { classe: "strong", label: "Très fort", couleur: "#10B981" },
  ];

  /* Calculer l'index (score peut être 0 à 4, donc max index = 3) */
  const idx    = Math.max(0, Math.min(score - 1, 3));
  const niveau = niveaux[idx] || niveaux[0];

  /* Mettre à jour la barre de force */
  if (DOM.strengthBar) {
    DOM.strengthBar.className = `strength-bar ${niveau.classe}`;
  }

  /* Mettre à jour le texte de force */
  if (DOM.strengthText) {
    DOM.strengthText.textContent = niveau.label;
    DOM.strengthText.style.color = niveau.couleur;
  }

  /* Mettre à jour le badge dans l'en-tête */
  if (DOM.strengthLabel) {
    DOM.strengthLabel.textContent = `Force : ${niveau.label}`;
  }

  return score;
}

/**
 * Coche/décoche les critères du mot de passe en temps réel
 * @param {string} mdp - Le mot de passe à vérifier
 */
function verifierCriteres(mdp) {
  /* Règles : map nom_règle → expression régulière ou condition */
  const regles = {
    length:  mdp.length >= 8,
    upper:   /[A-Z]/.test(mdp),
    number:  /\d/.test(mdp),
    special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(mdp),
  };

  /* Mettre à jour chaque critère dans la liste */
  DOM.pwdCriteria.forEach((el) => {
    const regle = el.dataset.rule;
    if (regles[regle]) {
      el.classList.add("valid");    /* Coché = vert */
    } else {
      el.classList.remove("valid"); /* Non coché = gris */
    }
  });
}

/**
 * Vérifie que les deux mots de passe correspondent
 * Affiche un indicateur coloré
 */
function verifierCorrespondance() {
  if (!DOM.mdpNouveau || !DOM.mdpConfirm || !DOM.matchIndicator) return;

  const nouveau  = DOM.mdpNouveau.value;
  const confirme = DOM.mdpConfirm.value;

  /* Ne pas afficher si l'un des champs est vide */
  if (!nouveau || !confirme) {
    DOM.matchIndicator.style.display = "none";
    return;
  }

  DOM.matchIndicator.style.display = "flex";

  if (nouveau === confirme) {
    /* Correspondent : afficher en vert */
    DOM.matchIndicator.classList.add("match");
    DOM.matchIndicator.classList.remove("no-match");
    if (DOM.matchText) DOM.matchText.textContent = "Les mots de passe correspondent ✓";
  } else {
    /* Ne correspondent pas : afficher en rouge */
    DOM.matchIndicator.classList.add("no-match");
    DOM.matchIndicator.classList.remove("match");
    if (DOM.matchText) DOM.matchText.textContent = "Les mots de passe ne correspondent pas";
  }
}

/**
 * Valide tous les champs du formulaire mot de passe
 * puis sauvegarde si tout est correct
 */
function validerEtSauvegarderMdp() {
  let estValide = true;

  /* Effacer toutes les erreurs précédentes */
  [DOM.errActuel, DOM.errNouveau, DOM.errConfirm].forEach((el) => {
    if (el) el.textContent = "";
  });

  /* Vérification 1 : mot de passe actuel renseigné */
  if (!DOM.mdpActuel?.value) {
    if (DOM.errActuel) DOM.errActuel.textContent = "Veuillez entrer votre mot de passe actuel.";
    DOM.mdpActuel?.focus();
    estValide = false;
  }

  /* Vérification 2 : nouveau mot de passe ≥ 8 caractères */
  const nouveau = DOM.mdpNouveau?.value || "";
  if (nouveau.length < 8) {
    if (DOM.errNouveau) DOM.errNouveau.textContent = "Le mot de passe doit contenir au moins 8 caractères.";
    if (estValide) DOM.mdpNouveau?.focus();
    estValide = false;
  }

  /* Vérification 3 : confirmation correspond */
  if (nouveau !== (DOM.mdpConfirm?.value || "")) {
    if (DOM.errConfirm) DOM.errConfirm.textContent = "Les mots de passe ne correspondent pas.";
    if (estValide) DOM.mdpConfirm?.focus();
    estValide = false;
  }

  /* Si toutes les vérifications passent → sauvegarder */
  if (estValide) {
    /* En production : envoi AJAX vers profil.php avec action=changer_mdp */
    /* await fetch('api/profil.php', { method: 'POST', body: formData }); */

    reinitialiserFormMdp();  /* Vider le formulaire après succès */
    afficherToast("🔒 Mot de passe modifié avec succès !");
  }
}

/**
 * Réinitialise le formulaire mot de passe (vide les champs + réinitialise UI)
 */
function reinitialiserFormMdp() {
  /* Vider les champs */
  if (DOM.mdpActuel)  DOM.mdpActuel.value  = "";
  if (DOM.mdpNouveau) DOM.mdpNouveau.value = "";
  if (DOM.mdpConfirm) DOM.mdpConfirm.value = "";

  /* Masquer les indicateurs */
  if (DOM.strengthBarWrap) DOM.strengthBarWrap.style.display = "none";
  if (DOM.strengthBadge)   DOM.strengthBadge.style.display   = "none";
  if (DOM.matchIndicator)  DOM.matchIndicator.style.display   = "none";

  /* Réinitialiser les critères (tous gris) */
  DOM.pwdCriteria.forEach((el) => el.classList.remove("valid"));

  /* Effacer les erreurs */
  [DOM.errActuel, DOM.errNouveau, DOM.errConfirm].forEach((el) => {
    if (el) el.textContent = "";
  });

  /* Remettre la barre de force à zéro */
  if (DOM.strengthBar) DOM.strengthBar.className = "strength-bar";
}


/* ============================================================
   8. COMPTEUR DE CARACTÈRES (Biographie)
   ============================================================ */

/**
 * Met à jour le compteur de caractères de la biographie en temps réel
 * Change de couleur selon la proximité de la limite
 */
function initBioCounter() {
  if (!DOM.infBio || !DOM.bioCount) return;

  /* Mettre à jour au chargement avec la valeur initiale */
  mettreAJourCompteur();

  /* Mettre à jour à chaque frappe */
  DOM.infBio.addEventListener("input", mettreAJourCompteur);

  function mettreAJourCompteur() {
    const actuel = DOM.infBio.value.length;  /* Nb caractères actuels */
    const max    = parseInt(DOM.infBio.maxLength) || 300;  /* Limite */

    /* Afficher le compteur */
    DOM.bioCount.textContent = `${actuel} / ${max}`;

    /* Changer la couleur selon la proximité */
    DOM.bioCount.className = "char-count";
    if (actuel >= max)         DOM.bioCount.classList.add("at-limit");    /* Rouge */
    else if (actuel >= max * 0.9) DOM.bioCount.classList.add("near-limit"); /* Orange */
  }
}


/* ============================================================
   9. SIDEBAR MOBILE
   ============================================================ */

/**
 * Gère l'ouverture/fermeture de la sidebar sur mobile
 */
function initSidebarMobile() {
  /* Bouton hamburger → ouvrir/fermer */
  if (DOM.menuToggle) {
    DOM.menuToggle.addEventListener("click", () => {
      DOM.sidebar?.classList.toggle("open");
      DOM.sidebarOverlay?.classList.toggle("show");
    });
  }

  /* Clic sur l'overlay → fermer */
  if (DOM.sidebarOverlay) {
    DOM.sidebarOverlay.addEventListener("click", () => {
      DOM.sidebar?.classList.remove("open");
      DOM.sidebarOverlay.classList.remove("show");
    });
  }
}


/* ============================================================
   10. TOAST — Notification temporaire
   ============================================================ */

let toastTimer = null;  /* Timer pour fermeture automatique */

/**
 * Affiche un toast de notification en bas à droite
 * @param {string} message - Texte à afficher
 * @param {string} type    - "success" | "error" | "info" (défaut: "success")
 * @param {number} duree   - Durée d'affichage en ms (défaut: 3500)
 */
function afficherToast(message, type = "success", duree = 3500) {
  if (!DOM.toast) return;

  /* Icônes selon le type */
  const icones = {
    success: "✅",
    error:   "❌",
    info:    "ℹ️",
    warning: "⚠️",
  };

  /* Mettre à jour l'icône et le texte */
  if (DOM.toastIcon) DOM.toastIcon.textContent = icones[type] || "✅";
  if (DOM.toastMsg)  DOM.toastMsg.textContent  = message;

  /* Afficher le toast */
  DOM.toast.classList.add("show");

  /* Annuler le timer précédent (si toast déjà visible) */
  if (toastTimer) clearTimeout(toastTimer);

  /* Fermer automatiquement après la durée */
  toastTimer = setTimeout(() => {
    DOM.toast.classList.remove("show");
  }, duree);
}


/* ============================================================
   11. UTILITAIRES
   ============================================================ */

/**
 * Valide le format d'une adresse email
 * @param {string} email - Email à valider
 * @returns {boolean}    - true si valide
 */
function validerEmail(email) {
  /* Expression régulière simple pour validation email */
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/**
 * Met la première lettre d'une chaîne en majuscule
 * @param {string} str
 * @returns {string}
 */
function capitaliser(str) {
  if (!str) return "";
  return str.charAt(0).toUpperCase() + str.slice(1);
}

/* ---- FIN DE profil.js ---- */