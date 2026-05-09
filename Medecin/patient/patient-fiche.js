// ============================================================
// patient-fiche.js — Logique de la fiche détaillée patient
// Gestion onglets, modals, messagerie, vaccins, ordonnances
// ============================================================

// ============================================================
// INITIALISATION AU CHARGEMENT DE LA PAGE
// ============================================================
document.addEventListener("DOMContentLoaded", function () {
    // Initialise la date par défaut des formulaires à aujourd'hui
    const today = new Date().toISOString().split("T")[0]; // Format YYYY-MM-DD
    const entDate = document.getElementById("ent-date");
    const vacDate = document.getElementById("vac-date");
    if (entDate) entDate.value = today; // Pré-remplit la date d'entrée médicale
    if (vacDate) vacDate.value = today; // Pré-remplit la date du vaccin

    // Fait défiler la zone de messages vers le bas (message le plus récent)
    scrollMessagesToBottom();

    // Récupère l'ID du patient depuis l'URL (ex: ?id=3)
    const urlParams   = new URLSearchParams(window.location.search);
    const patientId   = urlParams.get("id"); // Peut être null si pas de paramètre

    // En production, ici on ferait un fetch vers l'API PHP
    // pour charger les vraies données du patient selon son ID
    console.log("Fiche patient chargée pour l'ID:", patientId || "1 (défaut)");
});

// ============================================================
// GESTION DES ONGLETS
// Affiche le panneau correspondant et met à jour le bouton actif
// ============================================================
function switchTab(tabName) {
    // --- Désactive tous les boutons d'onglets ---
    document.querySelectorAll(".tab-btn").forEach(function (btn) {
        btn.classList.remove("active"); // Supprime la classe active de chaque bouton
    });

    // --- Cache tous les panneaux de contenu ---
    document.querySelectorAll(".tab-panel").forEach(function (panel) {
        panel.classList.remove("active"); // Masque chaque panneau
    });

    // --- Active le bouton de l'onglet sélectionné ---
    const activeBtn = document.getElementById("tab-" + tabName); // Ex: "tab-vaccins"
    if (activeBtn) activeBtn.classList.add("active"); // Rend le bouton actif

    // --- Affiche le panneau correspondant ---
    const activePanel = document.getElementById("panel-" + tabName); // Ex: "panel-vaccins"
    if (activePanel) activePanel.classList.add("active"); // Rend le panneau visible

    // Si on ouvre l'onglet messages, fait défiler vers le bas
    if (tabName === "messages") {
        setTimeout(scrollMessagesToBottom, 100); // Léger délai pour que le DOM se mette à jour
    }
}

// ============================================================
// MODAL — ORDONNANCE
// ============================================================

// Ouvre le modal de création d'ordonnance
function openModalOrdonnance() {
    document.getElementById("modal-ordonnance").classList.add("open");
}

// Ferme le modal d'ordonnance
function closeModalOrdonnance() {
    document.getElementById("modal-ordonnance").classList.remove("open");
    // Vide les champs du formulaire
    ["ord-medic", "ord-posologie", "ord-duree", "ord-notes"].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.value = ""; // Vide chaque champ
    });
}

// Sauvegarde une nouvelle ordonnance et l'ajoute à la liste
function saveOrdonnance(event) {
    event.preventDefault(); // Empêche le rechargement de la page

    // Récupère les valeurs saisies
    const medic     = document.getElementById("ord-medic").value.trim();
    const posologie = document.getElementById("ord-posologie").value.trim();
    const duree     = document.getElementById("ord-duree").value.trim();
    const notes     = document.getElementById("ord-notes").value.trim();

    // Formate la date d'aujourd'hui pour l'affichage
    const dateStr = formatDateFR(new Date());

    // Crée la carte HTML de la nouvelle ordonnance
    const card = document.createElement("div");
    card.className = "prescription-card"; // Classe de style définie dans CSS

    card.innerHTML = `
        <div style="display:flex;align-items:center;gap:14px;flex:1;">
            <!-- Icône médicament -->
            <div class="prescription-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <div>
                <!-- Nom du médicament -->
                <div class="prescription-name">${escapeHtml(medic)}</div>
                <!-- Posologie et durée -->
                <div class="prescription-details">${escapeHtml(posologie)} · ${escapeHtml(duree)}</div>
                ${notes ? `<div style="font-size:0.75rem;color:var(--color-text-muted);margin-top:4px;">📝 ${escapeHtml(notes)}</div>` : ""}
                <!-- Date et auteur -->
                <div style="font-size:0.72rem;color:var(--color-text-muted);margin-top:4px;">
                    📅 ${dateStr} · Dr. Diomandé
                </div>
            </div>
        </div>
        <!-- Boutons d'action -->
        <div style="display:flex;gap:8px;">
            <button class="btn-secondary" style="font-size:0.75rem;padding:5px 10px;">Imprimer</button>
            <button class="btn-secondary" style="font-size:0.75rem;padding:5px 10px;">Envoyer</button>
        </div>
    `;

    // Ajoute la carte en haut de la liste (plus récent en premier)
    const liste = document.getElementById("ordonnances-list");
    liste.insertBefore(card, liste.firstChild); // Insère avant le premier élément

    // Ferme le modal
    closeModalOrdonnance();

    // Affiche une notification de succès
    showToast("Ordonnance créée pour " + medic, "success");
}

// ============================================================
// MODAL — ENTRÉE DOSSIER MÉDICAL
// ============================================================

// Ouvre le modal d'ajout d'entrée médicale
function openModalEntree() {
    document.getElementById("modal-entree").classList.add("open");
}

// Ferme le modal d'entrée médicale
function closeModalEntree() {
    document.getElementById("modal-entree").classList.remove("open");
    // Vide les champs
    ["ent-titre", "ent-description"].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.value = "";
    });
}

// Sauvegarde une nouvelle entrée dans la timeline du dossier
function saveEntree(event) {
    event.preventDefault(); // Empêche le rechargement

    // Récupère les valeurs
    const titre       = document.getElementById("ent-titre").value.trim();
    const date        = document.getElementById("ent-date").value;
    const description = document.getElementById("ent-description").value.trim();

    // Formate la date pour l'affichage
    const dateAffichage = formatDateFR(new Date(date + "T00:00:00"));

    // Crée un nouvel élément timeline
    const item = document.createElement("div");
    item.className = "timeline-item"; // Classe CSS de la timeline

    item.innerHTML = `
        <div class="timeline-date">${dateAffichage}</div>
        <div class="timeline-title">${escapeHtml(titre)}</div>
        <div class="timeline-content">${escapeHtml(description)}</div>
    `;

    // Insère en haut de la timeline (entrée la plus récente en premier)
    const timeline = document.getElementById("timeline-dossier");
    timeline.insertBefore(item, timeline.firstChild);

    // Ferme le modal
    closeModalEntree();

    // Notification de succès
    showToast("Entrée médicale ajoutée avec succès", "success");
}

// ============================================================
// MODAL — AJOUTER UN VACCIN
// ============================================================

// Ouvre le modal d'ajout de vaccin
function openModalVaccin() {
    document.getElementById("modal-vaccin").classList.add("open");
}

// Ferme le modal de vaccin
function closeModalVaccin() {
    document.getElementById("modal-vaccin").classList.remove("open");
    // Vide les champs
    ["vac-nom", "vac-lot"].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.value = "";
    });
    // Remet le statut à "Programmé"
    const statut = document.getElementById("vac-statut");
    if (statut) statut.value = "Programmé";
}

// Sauvegarde un nouveau vaccin dans la liste
function saveVaccin(event) {
    event.preventDefault(); // Empêche le rechargement

    // Récupère les valeurs
    const nom    = document.getElementById("vac-nom").value.trim();
    const date   = document.getElementById("vac-date").value;
    const statut = document.getElementById("vac-statut").value;
    const lot    = document.getElementById("vac-lot").value.trim();

    // Formate la date pour l'affichage
    const dateAffichage = formatDateFR(new Date(date + "T00:00:00"));

    // Détermine la couleur de l'icône selon le statut
    let iconClass  = "blue";                // Couleur par défaut
    let badgeClass = "badge-inactive";      // Badge par défaut
    if (statut === "Effectué") {
        iconClass  = "green";
        badgeClass = "badge-active";        // Vert si effectué
    } else if (statut === "En retard") {
        iconClass  = "orange";
        badgeClass = "badge-warning";       // Orange si en retard
    }

    // Crée une nouvelle ligne de record pour le vaccin
    const row = document.createElement("div");
    row.className = "record-row"; // Classe de style

    row.innerHTML = `
        <div class="record-icon ${iconClass}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 2l4 4-10 10H8v-4L18 2z"/>
            </svg>
        </div>
        <div class="record-info">
            <div class="record-title">${escapeHtml(nom)}</div>
            <div class="record-date">${dateAffichage}${lot ? " · Lot #" + escapeHtml(lot) : ""}</div>
        </div>
        <span class="badge ${badgeClass}">${escapeHtml(statut)}</span>
    `;

    // Ajoute en haut de la liste
    const liste = document.getElementById("vaccins-list");
    liste.insertBefore(row, liste.firstChild);

    // Ferme le modal
    closeModalVaccin();

    // Notification
    showToast("Vaccin " + nom + " ajouté", "success");
}

// ============================================================
// MARQUER UN VACCIN COMME EFFECTUÉ
// Appelée depuis le bouton "Marquer effectué" dans la liste vaccins
// ============================================================
function marquerVaccinEffectue(btn) {
    const row = btn.closest(".record-row"); // Remonte jusqu'à la ligne parent

    // Met à jour l'icône (passe en vert)
    const icon = row.querySelector(".record-icon");
    if (icon) {
        icon.className = "record-icon green"; // Classe verte
    }

    // Met à jour le badge
    const badge = row.querySelector(".badge");
    if (badge) {
        badge.className = "badge badge-active"; // Badge vert
        badge.textContent = "Effectué";         // Nouveau texte
    }

    // Supprime le bouton "Marquer effectué" (action accomplie)
    const btnContainer = btn.parentElement;
    if (btnContainer) btnContainer.removeChild(btn);

    // Notification de succès
    showToast("Vaccin marqué comme effectué !", "success");
}

// ============================================================
// MESSAGERIE — ENVOI DE MESSAGE
// ============================================================

// Gère l'appui sur Entrée dans le champ de message
function handleChatKeypress(event) {
    if (event.key === "Enter") { // Si la touche Entrée est pressée
        event.preventDefault(); // Empêche le saut de ligne
        sendMessage();          // Envoie le message
    }
}

// Envoie un message du médecin dans la zone de chat
function sendMessage() {
    const input   = document.getElementById("chat-input");   // Champ de saisie
    const zone    = document.getElementById("messages-zone"); // Zone d'affichage
    const message = input.value.trim();                       // Texte saisi

    // Ne fait rien si le champ est vide
    if (!message) return;

    // Formate l'heure actuelle (HH:MM)
    const now  = new Date();
    const time = now.getHours().toString().padStart(2, "0") + ":" +
                 now.getMinutes().toString().padStart(2, "0"); // Ex: "14:05"

    // Crée le conteneur de la bulle de message du médecin
    const wrapper = document.createElement("div");
    wrapper.style.marginBottom = "16px"; // Espace entre les messages

    // HTML de la bulle de message envoyé (médecin, à droite)
    wrapper.innerHTML = `
        <div class="message-sender" style="text-align:right;">Dr. Diomandé (vous)</div>
        <div class="message-bubble sent">
            ${escapeHtml(message)}
            <div class="message-time">${time}</div>
        </div>
    `;

    zone.appendChild(wrapper);  // Ajoute la bulle au DOM
    input.value = "";           // Vide le champ de saisie
    scrollMessagesToBottom();   // Fait défiler vers le bas
}

// Fait défiler la zone de messages jusqu'en bas (dernier message visible)
function scrollMessagesToBottom() {
    const zone = document.getElementById("messages-zone");
    if (zone) zone.scrollTop = zone.scrollHeight; // scrollTop = hauteur totale = tout en bas
}

// ============================================================
// MODAL — MODIFIER LE PATIENT
// (stub simplifié — à compléter avec les vrais champs)
// ============================================================
function openModalModif() {
    // En production, ouvrir un modal pré-rempli avec les infos du patient
    showToast("Formulaire de modification bientôt disponible", "info");
}

// ============================================================
// FERMETURE DES MODALS EN CLIQUANT EN DEHORS
// ============================================================
["modal-ordonnance", "modal-entree", "modal-vaccin"].forEach(function (id) {
    const overlay = document.getElementById(id);
    if (overlay) {
        // Écoute les clics sur le fond (overlay)
        overlay.addEventListener("click", function (e) {
            if (e.target === overlay) { // Vérifie que le clic est bien sur le fond
                overlay.classList.remove("open"); // Ferme le modal
            }
        });
    }
});

// ============================================================
// FONCTIONS UTILITAIRES
// ============================================================

// Formate une date JavaScript en format français (ex: "10 déc. 2024")
function formatDateFR(date) {
    const options = { day: "numeric", month: "short", year: "numeric" };
    return date.toLocaleDateString("fr-FR", options); // Utilise le locale français
}

// Échappe les caractères HTML dangereux pour éviter les injections XSS
// Remplace les caractères spéciaux par leurs équivalents HTML sûrs
function escapeHtml(str) {
    if (!str) return ""; // Retourne vide si null ou undefined
    return str
        .replace(/&/g, "&amp;")   // & → &amp;
        .replace(/</g, "&lt;")    // < → &lt; (empêche les balises)
        .replace(/>/g, "&gt;")    // > → &gt;
        .replace(/"/g, "&quot;")  // " → &quot;
        .replace(/'/g, "&#039;"); // ' → &#039;
}

// Affiche une notification toast temporaire en bas à droite
function showToast(message, type) {
    const container = document.getElementById("toast-container"); // Conteneur des toasts

    // Crée l'élément toast
    const toast = document.createElement("div");
    toast.className = "toast " + (type || "success"); // Classe selon le type

    // Icône selon le type de toast
    let icon = "";
    if (type === "success" || !type) {
        // Icône coche verte pour succès
        icon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-teal)" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>`;
    } else if (type === "error") {
        // Icône X rouge pour erreur
        icon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
    } else {
        // Icône info pour autres types
        icon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`;
    }

    toast.innerHTML = icon + "<span>" + message + "</span>"; // Icône + texte
    container.appendChild(toast); // Ajoute au conteneur

    // Supprime le toast après 3 secondes avec fondu
    setTimeout(function () {
        toast.style.opacity    = "0";
        toast.style.transition = "opacity 0.4s ease";
        setTimeout(function () { toast.remove(); }, 400); // Supprime du DOM après le fondu
    }, 3000);
}