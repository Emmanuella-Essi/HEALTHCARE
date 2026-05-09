// ============================================================
// messagerie.js — Logique complète de la page Messagerie
// Gestion des conversations, chat en temps réel, notifications
// ============================================================

// ============================================================
// DONNÉES MOCK — Conversations et messages de démonstration
// En production, ces données viendraient d'un fetch PHP/API
// ============================================================

// Tableau de toutes les conversations disponibles
const CONVERSATIONS = [
    {
        id: 1,                                    // Identifiant unique de la conversation
        patient: "Konan Kouassi",                 // Nom complet du patient
        initiales: "KK",                          // Initiales pour l'avatar
        avatarColor: "#EFF6FF",                   // Couleur de fond de l'avatar
        avatarTextColor: "#1D4ED8",               // Couleur du texte de l'avatar
        online: true,                             // Statut en ligne (true/false)
        age: 34,                                  // Âge du patient
        blood: "O+",                              // Groupe sanguin
        tel: "+225 07 12 34 56",                  // Numéro de téléphone
        allergies: "Pénicilline",                 // Allergies connues
        lastVisit: "10 déc. 2024",                // Dernière consultation
        lastMessage: "Bonjour Docteur, les vertiges ont disparu merci !",  // Aperçu du dernier message
        lastTime: "09:14",                        // Heure du dernier message
        unread: 2,                                // Nombre de messages non lus
        highlight: false,                         // Message spécial (ex: demande résa)
        patientId: 1,                             // Lien vers la fiche patient
        messages: [                               // Historique complet des messages
            {
                id: 1,
                from: "patient",                  // "patient" = reçu / "doctor" = envoyé
                text: "Bonjour Docteur, je ressens des vertiges depuis ce matin. Est-ce que je devrais m'inquiéter ?",
                time: "08:32",
                date: "Aujourd'hui",
                read: true                        // Message lu ou non
            },
            {
                id: 2,
                from: "doctor",
                text: "Bonjour Konan, les vertiges peuvent être liés à votre tension. Avez-vous pris votre Amlodipine ce matin ?",
                time: "08:45",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 3,
                from: "patient",
                text: "Oui Docteur, je l'ai prise avec le petit déjeuner comme d'habitude.",
                time: "08:47",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 4,
                from: "doctor",
                text: "Bien. Mesurez votre tension et reposez-vous. Si les vertiges persistent plus de 2h ou s'aggravent, consultez en urgence. Tenez-moi informé.",
                time: "08:50",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 5,
                from: "patient",
                text: "D'accord Docteur. Ma tension est à 138/85 mmHg.",
                time: "09:05",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 6,
                from: "patient",
                text: "Bonjour Docteur, les vertiges ont disparu merci !",
                time: "09:14",
                date: "Aujourd'hui",
                read: false                       // Dernier message non lu
            }
        ]
    },
    {
        id: 2,
        patient: "Aya Bamba",
        initiales: "AB",
        avatarColor: "#FCE7F3",                   // Rose pour femme
        avatarTextColor: "#9D174D",
        online: false,                            // Hors ligne
        age: 28,
        blood: "A+",
        tel: "+225 05 98 76 54",
        allergies: "Aucune",
        lastVisit: "8 déc. 2024",
        lastMessage: "Merci pour l'ordonnance, je vais à la pharmacie.",
        lastTime: "Hier",
        unread: 0,                                // Aucun message non lu
        highlight: false,
        patientId: 2,
        messages: [
            {
                id: 1,
                from: "doctor",
                text: "Bonjour Aya, voici votre renouvellement d'ordonnance pour la Pilule.",
                time: "14:20",
                date: "Hier",
                read: true
            },
            {
                id: 2,
                from: "patient",
                text: "Merci beaucoup Docteur !",
                time: "14:35",
                date: "Hier",
                read: true
            },
            {
                id: 3,
                from: "patient",
                text: "Merci pour l'ordonnance, je vais à la pharmacie.",
                time: "15:10",
                date: "Hier",
                read: true
            }
        ]
    },
    {
        id: 3,
        patient: "Sékou Traoré",
        initiales: "ST",
        avatarColor: "#F0FDF4",
        avatarTextColor: "#166534",
        online: true,
        age: 52,
        blood: "B+",
        tel: "+225 01 23 45 67",
        allergies: "Aspirine, Latex",
        lastVisit: "25 nov. 2024",
        lastMessage: "J'ai des douleurs thoraciques depuis 2h, c'est urgent.",
        lastTime: "08:03",
        unread: 3,
        highlight: false,
        urgent: true,                             // Marqué comme urgent (rouge)
        patientId: 3,
        messages: [
            {
                id: 1,
                from: "patient",
                text: "Bonjour Docteur, j'ai des douleurs thoraciques depuis ce matin.",
                time: "07:45",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 2,
                from: "patient",
                text: "Les douleurs s'intensifient, je transpire beaucoup.",
                time: "07:58",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 3,
                from: "patient",
                text: "J'ai des douleurs thoraciques depuis 2h, c'est urgent.",
                time: "08:03",
                date: "Aujourd'hui",
                read: false
            }
        ]
    },
    {
        id: 4,
        patient: "Fatou Diallo",
        initiales: "FD",
        avatarColor: "#FFF7ED",
        avatarTextColor: "#C2410C",
        online: false,
        age: 19,
        blood: "AB+",
        tel: "+225 07 65 43 21",
        allergies: "Aucune",
        lastVisit: "1 déc. 2024",
        lastMessage: "John requested a booking",   // Message de type réservation
        lastTime: "15:04",
        unread: 0,
        highlight: true,                          // Texte mis en valeur (vert)
        patientId: 4,
        messages: [
            {
                id: 1,
                from: "patient",
                text: "Bonjour Docteur, je souhaite prendre un rendez-vous pour une consultation.",
                time: "14:50",
                date: "Hier",
                read: true
            },
            {
                id: 2,
                from: "doctor",
                text: "Bonjour Fatou, je suis disponible vendredi 20 décembre à 10h. Ça vous convient ?",
                time: "15:00",
                date: "Hier",
                read: true
            },
            {
                id: 3,
                from: "patient",
                text: "Fatou a demandé un rendez-vous pour le vendredi 20 décembre.",
                time: "15:04",
                date: "Hier",
                read: true,
                isBooking: true                   // Type spécial : demande de réservation
            }
        ]
    },
    {
        id: 5,
        patient: "Moussa Coulibaly",
        initiales: "MC",
        avatarColor: "#F5F3FF",
        avatarTextColor: "#6D28D9",
        online: false,
        age: 67,
        blood: "O-",
        tel: "+225 05 11 22 33",
        allergies: "Sulfamides",
        lastVisit: "15 oct. 2024",
        lastMessage: "Vitae justo eget magna ferme...",
        lastTime: "09:29",
        unread: 0,
        highlight: false,
        patientId: 5,
        messages: [
            {
                id: 1,
                from: "patient",
                text: "Docteur, mes résultats de biopsie sont arrivés.",
                time: "09:15",
                date: "Hier",
                read: true
            },
            {
                id: 2,
                from: "doctor",
                text: "Bonjour Moussa. Pouvez-vous me scanner les documents ?",
                time: "09:25",
                date: "Hier",
                read: true
            },
            {
                id: 3,
                from: "patient",
                text: "Vitae justo eget magna ferme...",
                time: "09:29",
                date: "Hier",
                read: true
            }
        ]
    },
    {
        id: 6,
        patient: "Amina Ouédraogo",
        initiales: "AO",
        avatarColor: "#ECFDF5",
        avatarTextColor: "#065F46",
        online: true,
        age: 41,
        blood: "A-",
        tel: "+225 07 55 44 33",
        allergies: "Lactose",
        lastVisit: "5 déc. 2024",
        lastMessage: "Eu tincidunt tortor aliquam...",
        lastTime: "10:11",
        unread: 0,
        highlight: false,
        patientId: 6,
        messages: [
            {
                id: 1,
                from: "patient",
                text: "Bonjour Docteur, mon bébé a de la fièvre depuis hier soir.",
                time: "10:05",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 2,
                from: "doctor",
                text: "Quelle est sa température exacte ? Et quel âge a-t-il ?",
                time: "10:08",
                date: "Aujourd'hui",
                read: true
            },
            {
                id: 3,
                from: "patient",
                text: "Eu tincidunt tortor aliquam...",
                time: "10:11",
                date: "Aujourd'hui",
                read: true
            }
        ]
    }
];

// ============================================================
// ÉTAT GLOBAL DE L'APPLICATION
// Variables qui suivent l'état courant de l'interface
// ============================================================

let currentConvId    = null;   // ID de la conversation actuellement ouverte
let infoPanelOpen    = false;  // Indique si le panneau infos est ouvert
let typingTimer      = null;   // Timer pour simuler l'indicateur de frappe
let filteredConvs    = [];     // Liste filtrée des conversations (recherche)
let messageIdCounter = 1000;   // Compteur auto-incrémenté pour les nouveaux messages

// ============================================================
// INITIALISATION — Exécutée au chargement du DOM
// ============================================================
document.addEventListener("DOMContentLoaded", function () {
    filteredConvs = [...CONVERSATIONS];  // Copie le tableau complet dans le filtre
    renderConvList(filteredConvs);       // Affiche la liste des conversations
    updateTotalUnread();                 // Met à jour le compteur global non lus

    // Simule la réception de nouveaux messages périodiquement
    // En production, cela serait remplacé par WebSocket ou SSE
    simulateIncomingMessages();
});

// ============================================================
// RENDU DE LA LISTE DES CONVERSATIONS
// Génère les éléments HTML pour chaque conversation
// ============================================================
function renderConvList(convs) {
    const list = document.getElementById("conv-list"); // Conteneur de la liste
    list.innerHTML = "";                                // Vide la liste avant de la reconstruire

    // Si aucune conversation ne correspond à la recherche
    if (convs.length === 0) {
        list.innerHTML = `
            <div style="padding:30px 16px;text-align:center;color:var(--color-text-muted);font-size:0.85rem;">
                Aucune conversation trouvée.
            </div>`;
        return; // Arrête la fonction
    }

    // Crée un élément HTML pour chaque conversation
    convs.forEach(function (conv) {
        const item = document.createElement("div"); // Élément conteneur de la conversation

        // Construit les classes CSS selon l'état de la conversation
        let classes = "conv-item";                        // Classe de base
        if (conv.id === currentConvId) classes += " active";  // Active si ouverte
        if (conv.unread > 0)           classes += " unread";   // Non lu si badge > 0

        item.className = classes;

        // Génère le badge de messages non lus (vide si 0)
        const badgeHTML = conv.unread > 0
            ? `<span class="unread-count ${conv.urgent ? 'urgent' : ''}">${conv.unread}</span>`
            : ""; // Pas de badge si tout est lu

        // Génère l'indicateur "en ligne" (point vert si online)
        const onlineDot = conv.online
            ? `<span class="online-dot"></span>` // Point vert
            : "";                                  // Rien si hors ligne

        // Classe de l'aperçu selon le type de message
        const previewClass = conv.highlight ? "conv-preview highlight" : "conv-preview";

        // Construit le HTML de l'élément conversation
        item.innerHTML = `
            <!-- Conteneur de l'avatar avec point en ligne -->
            <div class="conv-avatar-wrap">
                <!-- Avatar avec initiales et couleurs dynamiques -->
                <div class="conv-avatar" style="background:${conv.avatarColor};color:${conv.avatarTextColor};">
                    ${conv.initiales}
                </div>
                <!-- Point vert en ligne si applicable -->
                ${onlineDot}
            </div>

            <!-- Informations texte de la conversation -->
            <div class="conv-info">
                <!-- Ligne haute : nom + heure -->
                <div class="conv-top">
                    <span class="conv-name">${conv.patient}</span>
                    <span class="conv-time">${conv.lastTime}</span>
                </div>
                <!-- Aperçu du dernier message (tronqué) -->
                <div class="${previewClass}">${truncate(conv.lastMessage, 36)}</div>
            </div>

            <!-- Badge de messages non lus (si applicable) -->
            ${badgeHTML}
        `;

        // Écouteur de clic pour ouvrir cette conversation
        item.addEventListener("click", function () {
            openConversation(conv.id); // Ouvre la conversation au clic
        });

        list.appendChild(item); // Ajoute l'élément à la liste DOM
    });
}

// ============================================================
// FILTRAGE DES CONVERSATIONS — Recherche en temps réel
// Appelée à chaque frappe dans le champ de recherche
// ============================================================
function filterConversations(query) {
    const q = query.toLowerCase().trim(); // Convertit en minuscules et supprime les espaces

    // Filtre le tableau selon le nom du patient
    filteredConvs = CONVERSATIONS.filter(function (conv) {
        return conv.patient.toLowerCase().includes(q); // Vérifie si le nom contient la recherche
    });

    renderConvList(filteredConvs); // Re-rend la liste filtrée
}

// ============================================================
// OUVRIR UNE CONVERSATION
// Charge et affiche le chat d'une conversation sélectionnée
// ============================================================
function openConversation(convId) {
    currentConvId = convId; // Mémorise l'ID de la conversation ouverte

    // Récupère l'objet conversation correspondant
    const conv = CONVERSATIONS.find(function (c) { return c.id === convId; });
    if (!conv) return; // Sécurité : si non trouvé, on sort

    // Marque tous les messages comme lus
    conv.unread = 0;             // Remet le compteur à zéro
    conv.messages.forEach(function (m) { m.read = true; }); // Marque chaque message comme lu

    // Affiche les éléments du chat (cachés par défaut)
    showEl("chat-header");       // Affiche le header du chat
    showEl("chat-messages");     // Affiche la zone des messages
    showEl("chat-input-bar");    // Affiche la barre de saisie
    hideEl("chat-empty");        // Cache l'état "aucune conversation"

    // Met à jour l'en-tête du chat avec les infos du patient
    updateChatHeader(conv);

    // Met à jour le panneau infos patient si ouvert
    updateInfoPanel(conv);

    // Rend les messages dans la zone de chat
    renderMessages(conv.messages, conv);

    // Met à jour la liste (re-rend pour enlever les badges non lus)
    renderConvList(filteredConvs);

    // Met à jour le compteur global non lus
    updateTotalUnread();

    // Fait défiler vers le dernier message
    scrollToBottom();
}

// ============================================================
// MISE À JOUR DE L'EN-TÊTE DU CHAT
// Remplit les infos du patient dans le header du chat
// ============================================================
function updateChatHeader(conv) {
    // Met à jour l'avatar avec les initiales et couleurs
    const avatarEl = document.getElementById("chat-header-avatar");
    avatarEl.style.background = conv.avatarColor;       // Couleur de fond
    document.getElementById("chat-avatar-initials").textContent = conv.initiales; // Initiales
    avatarEl.style.color = conv.avatarTextColor;        // Couleur du texte

    // Met à jour le nom du patient
    document.getElementById("chat-patient-name").textContent = conv.patient;

    // Met à jour le statut en ligne / hors ligne
    const statusEl = document.getElementById("chat-patient-status");
    if (conv.online) {
        statusEl.textContent = "● En ligne";            // Texte avec point vert
        statusEl.className   = "chat-header-status online"; // Classe verte
    } else {
        statusEl.textContent = "Hors ligne";            // Texte hors ligne
        statusEl.className   = "chat-header-status offline"; // Classe grise
    }

    // Affiche ou masque le point "en ligne" sur l'avatar
    const onlineDot = document.getElementById("chat-online-dot");
    onlineDot.style.display = conv.online ? "block" : "none"; // Visible si en ligne

    // Met à jour le lien vers la fiche patient
    document.getElementById("chat-fiche-link").href =
        "../patient/patient-fiche.html?id=" + conv.patientId; // URL avec l'ID du patient

    // Met à jour l'indicateur de frappe (avatar du patient)
    document.getElementById("typing-avatar").textContent = conv.initiales;
    document.getElementById("typing-avatar").style.background = conv.avatarColor;
    document.getElementById("typing-avatar").style.color      = conv.avatarTextColor;
}

// ============================================================
// RENDU DES MESSAGES
// Génère les bulles de messages dans la zone de chat
// ============================================================
function renderMessages(messages, conv) {
    const zone = document.getElementById("chat-messages"); // Zone d'affichage
    zone.innerHTML = "";                                    // Vide la zone

    let lastDate     = null;  // Mémorise la date de la dernière bulle (pour les séparateurs)
    let lastFrom     = null;  // Mémorise l'expéditeur du dernier message (pour grouper)
    let currentGroup = null;  // Élément du groupe de messages courant

    // Parcourt chaque message
    messages.forEach(function (msg, index) {
        const isDoctor = msg.from === "doctor"; // Vrai si c'est le médecin qui envoie

        // ---- Séparateur de date ----
        if (msg.date !== lastDate) {             // Si la date change
            // Crée un séparateur visuel avec la date
            const sep = document.createElement("div");
            sep.className = "date-separator";    // Classe CSS du séparateur
            sep.innerHTML = `<span>${msg.date}</span>`; // Texte de la date
            zone.appendChild(sep);              // Ajoute à la zone
            lastDate      = msg.date;           // Mémorise la nouvelle date
            currentGroup  = null;               // Force la création d'un nouveau groupe
            lastFrom      = null;
        }

        // ---- Groupement des messages consécutifs du même expéditeur ----
        if (msg.from !== lastFrom || !currentGroup) {
            // Crée un nouveau groupe de messages
            currentGroup = document.createElement("div");
            currentGroup.className = "msg-group " + (isDoctor ? "sent" : "received");

            // Crée l'avatar du groupe
            const avatarDiv = document.createElement("div");
            avatarDiv.className = "msg-group-avatar";

            if (isDoctor) {
                // Avatar du médecin (initiales "DD" sur fond vert)
                avatarDiv.textContent       = "DD";
                avatarDiv.style.background  = "var(--color-teal)";
                avatarDiv.style.color       = "white";
            } else {
                // Avatar du patient avec ses couleurs
                avatarDiv.textContent       = conv.initiales;
                avatarDiv.style.background  = conv.avatarColor;
                avatarDiv.style.color       = conv.avatarTextColor;
            }

            // Conteneur des bulles du groupe
            const bubblesDiv = document.createElement("div");
            bubblesDiv.className = "msg-bubbles";

            // Ajoute l'avatar et les bulles dans le bon ordre selon l'expéditeur
            if (isDoctor) {
                currentGroup.appendChild(bubblesDiv); // Bulles à gauche pour le médecin (flex-direction: row-reverse)
                currentGroup.appendChild(avatarDiv);
            } else {
                currentGroup.appendChild(avatarDiv);
                currentGroup.appendChild(bubblesDiv);
            }

            zone.appendChild(currentGroup); // Ajoute le groupe à la zone
            lastFrom = msg.from;            // Mémorise l'expéditeur courant
        }

        // ---- Bulle de message ----
        const bubblesDiv = currentGroup.querySelector(".msg-bubbles"); // Récupère le conteneur

        // Cas spécial : message de type réservation
        if (msg.isBooking) {
            const bookingBubble = document.createElement("div");
            bookingBubble.className = "msg-bubble received"; // Toujours reçu
            bookingBubble.style.borderLeft = "3px solid var(--color-teal)"; // Accent vert
            bookingBubble.innerHTML = `
                <span style="color:var(--color-teal);font-weight:600;font-size:0.78rem;display:block;margin-bottom:4px;">
                    📅 Demande de rendez-vous
                </span>
                ${escapeHtml(msg.text)}
                <span class="msg-time">${msg.time}</span>
            `;
            bubblesDiv.appendChild(bookingBubble);
            return; // Passe au message suivant
        }

        // Crée la bulle standard
        const bubble = document.createElement("div");
        bubble.className = "msg-bubble " + (isDoctor ? "sent" : "received"); // Classe selon l'expéditeur

        // Contenu de la bulle : texte + heure
        bubble.innerHTML = `
            ${escapeHtml(msg.text)}
            <span class="msg-time">${msg.time}</span>
        `;

        // Ajoute l'indicateur "Lu ✓✓" sur le dernier message envoyé
        if (isDoctor && index === messages.length - 1) {
            const receipt = document.createElement("div");
            receipt.className   = "msg-read-receipt"; // Classe CSS
            receipt.textContent = "✓✓ Lu";           // Texte de confirmation
            bubblesDiv.appendChild(bubble);           // Ajoute la bulle
            bubblesDiv.appendChild(receipt);          // Ajoute le reçu de lecture
            return; // Évite le double appendChild
        }

        bubblesDiv.appendChild(bubble); // Ajoute la bulle au groupe
    });
}

// ============================================================
// ENVOYER UN MESSAGE
// Appelée au clic sur le bouton Envoyer ou appui sur Entrée
// ============================================================
function sendMessage() {
    const input = document.getElementById("message-input"); // Champ de saisie
    const text  = input.value.trim();                       // Texte saisi sans espaces

    // Ne fait rien si le champ est vide ou aucune conversation ouverte
    if (!text || !currentConvId) return;

    // Récupère la conversation courante
    const conv = CONVERSATIONS.find(function (c) { return c.id === currentConvId; });
    if (!conv) return;

    // Formate l'heure actuelle (HH:MM)
    const now  = new Date();
    const time = padTime(now.getHours()) + ":" + padTime(now.getMinutes()); // Ex: "14:07"

    // Crée l'objet message du médecin
    const newMsg = {
        id:   ++messageIdCounter,  // ID unique auto-incrémenté
        from: "doctor",            // Expéditeur = médecin
        text: text,                // Contenu du message
        time: time,                // Heure d'envoi
        date: "Aujourd'hui",       // Date (simplifiée)
        read: true                 // Marqué lu immédiatement
    };

    conv.messages.push(newMsg);        // Ajoute le message à l'historique
    conv.lastMessage = text;           // Met à jour l'aperçu dans la liste
    conv.lastTime    = time;           // Met à jour l'heure dans la liste

    input.value = "";                  // Vide le champ de saisie
    autoResize(input);                 // Remet la hauteur à l'état initial
    toggleSendBtn();                   // Désactive le bouton envoyer

    // Re-rend les messages dans la zone de chat
    renderMessages(conv.messages, conv);
    renderConvList(filteredConvs);     // Met à jour la liste des conversations
    scrollToBottom();                  // Fait défiler vers le bas

    // Simule une réponse automatique du patient après un délai aléatoire
    simulatePatientTyping(conv);
}

// ============================================================
// SIMULATION : LE PATIENT "ÉCRIT..."
// Affiche l'indicateur de frappe puis envoie une réponse
// ============================================================
function simulatePatientTyping(conv) {
    // Seuls les patients en ligne peuvent répondre
    if (!conv.online) return;

    // Délai aléatoire entre 2 et 5 secondes avant de répondre
    const thinkingDelay = 2000 + Math.random() * 3000;

    // Affiche l'indicateur de frappe après le délai
    typingTimer = setTimeout(function () {
        showTypingIndicator();  // Affiche "... en train d'écrire"
        scrollToBottom();       // Scroll vers le bas

        // Délai supplémentaire avant l'envoi de la réponse (1 à 3 secondes)
        const typingDuration = 1000 + Math.random() * 2000;

        setTimeout(function () {
            hideTypingIndicator(); // Cache l'indicateur de frappe

            // Choisit une réponse aléatoire parmi les réponses types
            const reponses = [
                "D'accord Docteur, je vais suivre vos conseils.",
                "Merci pour votre réponse rapide Docteur.",
                "Compris, je vous tiens informé de l'évolution.",
                "Très bien Docteur, à bientôt.",
                "Je vais faire comme vous dites. Bonne journée !"
            ];
            const texteReponse = reponses[Math.floor(Math.random() * reponses.length)]; // Aléatoire

            // Formate l'heure de la réponse
            const now  = new Date();
            const time = padTime(now.getHours()) + ":" + padTime(now.getMinutes());

            // Crée le message de réponse du patient
            const reponse = {
                id:   ++messageIdCounter,   // ID unique
                from: "patient",            // Expéditeur = patient
                text: texteReponse,
                time: time,
                date: "Aujourd'hui",
                read: true
            };

            conv.messages.push(reponse);       // Ajoute à l'historique
            conv.lastMessage = texteReponse;   // Met à jour l'aperçu
            conv.lastTime    = time;

            // Re-rend les messages et la liste
            renderMessages(conv.messages, conv);
            renderConvList(filteredConvs);
            scrollToBottom();

            // Affiche une notification toast si la fenêtre n'est pas active
            showToast("Nouveau message de " + conv.patient, "info");

        }, typingDuration); // Fin du délai de frappe

    }, thinkingDelay); // Fin du délai de réflexion
}

// ============================================================
// SIMULATION DE MESSAGES ENTRANTS
// Simule la réception périodique de nouveaux messages
// ============================================================
function simulateIncomingMessages() {
    // Simule un message urgent de Sékou Traoré après 8 secondes
    setTimeout(function () {
        const conv = CONVERSATIONS.find(function (c) { return c.id === 3; }); // Sékou
        if (!conv || conv.id === currentConvId) return; // Ignore si déjà ouvert

        conv.unread++;               // Incrémente les non lus
        conv.lastMessage = "Docteur, c'est urgent ! J'ai du mal à respirer."; // Nouveau message
        conv.lastTime    = "maintenant";

        // Ajoute le message à l'historique
        conv.messages.push({
            id:   ++messageIdCounter,
            from: "patient",
            text: "Docteur, c'est urgent ! J'ai du mal à respirer.",
            time: getCurrentTime(), // Heure actuelle
            date: "Aujourd'hui",
            read: false             // Non lu car conversation non ouverte
        });

        renderConvList(filteredConvs); // Met à jour la liste
        updateTotalUnread();            // Met à jour le compteur global

        // Affiche une notification d'urgence
        showToast("🚨 Message urgent de Sékou Traoré", "error");

    }, 8000); // 8 secondes après chargement
}

// ============================================================
// AFFICHER / MASQUER L'INDICATEUR DE FRAPPE
// ============================================================

// Affiche l'indicateur "en train d'écrire..."
function showTypingIndicator() {
    const indicator = document.getElementById("typing-indicator");
    indicator.style.display = "flex";      // Rend visible
    indicator.classList.add("visible");    // Applique l'opacité 1
}

// Cache l'indicateur de frappe
function hideTypingIndicator() {
    const indicator = document.getElementById("typing-indicator");
    indicator.classList.remove("visible"); // Opacité retourne à 0
    setTimeout(function () {
        indicator.style.display = "none"; // Masque complètement après la transition
    }, 300); // Délai correspondant à la transition CSS
}

// ============================================================
// FERMER LA CONVERSATION ACTIVE
// Revient à l'état "aucune conversation sélectionnée"
// ============================================================
function closeChat() {
    currentConvId = null;          // Réinitialise l'ID courant

    hideEl("chat-header");         // Cache le header
    hideEl("chat-messages");       // Cache les messages
    hideEl("chat-input-bar");      // Cache la barre de saisie
    hideEl("typing-indicator");    // Cache l'indicateur de frappe
    showEl("chat-empty");          // Affiche l'état vide

    // Ferme aussi le panneau infos si ouvert
    if (infoPanelOpen) toggleInfoPanel();

    renderConvList(filteredConvs); // Re-rend la liste (supprime l'état "active")
}

// ============================================================
// PANNEAU INFOS PATIENT — Ouvrir/Fermer
// ============================================================
function toggleInfoPanel() {
    const panel = document.getElementById("info-panel"); // Panneau infos

    if (infoPanelOpen) {
        panel.classList.remove("open"); // Ferme (width → 0)
        infoPanelOpen = false;          // Met à jour l'état
    } else {
        panel.classList.add("open");    // Ouvre (width → 240px)
        infoPanelOpen = true;

        // Met à jour le contenu du panneau avec la conversation courante
        if (currentConvId) {
            const conv = CONVERSATIONS.find(function (c) { return c.id === currentConvId; });
            if (conv) updateInfoPanel(conv); // Remplit le panneau
        }
    }
}

// Met à jour le contenu du panneau infos avec les données du patient
function updateInfoPanel(conv) {
    setTextContent("info-panel-avatar",    conv.initiales);           // Avatar
    setTextContent("info-panel-name",      conv.patient);             // Nom
    setTextContent("info-panel-age",       conv.age + " ans");        // Âge
    setTextContent("info-panel-blood",     conv.blood);               // Groupe sanguin
    setTextContent("info-panel-tel",       conv.tel);                 // Téléphone
    setTextContent("info-panel-lastvisit", conv.lastVisit);           // Dernière visite
    setTextContent("info-panel-allergies", conv.allergies);           // Allergies

    // Couleurs de l'avatar dans le panneau
    const avatarEl = document.getElementById("info-panel-avatar");
    if (avatarEl) {
        avatarEl.style.background = conv.avatarColor;    // Fond
        avatarEl.style.color      = conv.avatarTextColor; // Texte
    }

    // Lien vers la fiche du patient
    const link = document.querySelector(".info-panel-link");
    if (link) link.href = "../patient/patient-fiche.html?id=" + conv.patientId;
}

// ============================================================
// GESTION DU CLAVIER DANS L'INPUT
// Entrée = envoyer, Maj+Entrée = nouvelle ligne
// ============================================================
function handleKeydown(event) {
    if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault(); // Empêche le saut de ligne
        sendMessage();          // Envoie le message
    }
    // Maj+Entrée est géré nativement par le textarea (saut de ligne)
}

// ============================================================
// AUTO-RESIZE DU TEXTAREA
// La hauteur s'adapte au contenu (max 100px)
// ============================================================
function autoResize(textarea) {
    textarea.style.height = "auto";              // Réinitialise la hauteur
    textarea.style.height = Math.min(textarea.scrollHeight, 100) + "px"; // Ajuste avec un max
}

// ============================================================
// ACTIVER / DÉSACTIVER LE BOUTON ENVOYER
// Le bouton est désactivé si le champ est vide
// ============================================================
function toggleSendBtn() {
    const input  = document.getElementById("message-input"); // Champ de saisie
    const btn    = document.getElementById("send-btn");       // Bouton envoyer
    const hasText = input.value.trim().length > 0;            // Vrai si du texte existe

    btn.disabled = !hasText; // Désactive si vide, active si texte présent
}

// ============================================================
// INVITE NOUVELLE CONVERSATION
// Affiche un toast (en prod : ouvrir un modal de sélection)
// ============================================================
function showNewConvPrompt() {
    showToast("Sélectionnez un patient dans la liste pour démarrer.", "info");
}

// ============================================================
// SIMULER UN APPEL DE TÉLÉCONSULTATION
// ============================================================
function startCall() {
    if (!currentConvId) return; // Sécurité
    const conv = CONVERSATIONS.find(function (c) { return c.id === currentConvId; });
    if (!conv) return;

    if (!conv.online) {
        showToast(conv.patient + " est hors ligne.", "error"); // Erreur si hors ligne
        return;
    }

    // En prod : ouvrir une interface WebRTC ici
    showToast("Appel en cours vers " + conv.patient + "...", "info");
}

// ============================================================
// SIMULER L'ATTACHEMENT D'UN FICHIER
// ============================================================
function attachFile() {
    // En prod : déclencher un input[type=file] caché
    showToast("Fonctionnalité d'attachement disponible en production.", "info");
}

// ============================================================
// INSÉRER UN EMOJI RAPIDE
// ============================================================
function insertEmoji() {
    const input = document.getElementById("message-input");
    if (!input) return;

    // Liste d'emojis médicaux / courants
    const emojis = ["👍", "✅", "😊", "🙏", "💊", "🩺", "❤️", "📋"];
    const emoji  = emojis[Math.floor(Math.random() * emojis.length)]; // Aléatoire

    // Insère l'emoji à la position du curseur
    const pos   = input.selectionStart;              // Position du curseur
    const before = input.value.substring(0, pos);    // Texte avant le curseur
    const after  = input.value.substring(pos);       // Texte après le curseur
    input.value  = before + emoji + after;           // Insère l'emoji

    input.focus();                                   // Remet le focus
    autoResize(input);                               // Redimensionne
    toggleSendBtn();                                 // Vérifie si le bouton peut s'activer
}

// ============================================================
// COMPTEUR TOTAL DE MESSAGES NON LUS
// Met à jour l'affichage du badge global dans la topbar
// ============================================================
function updateTotalUnread() {
    // Somme de tous les messages non lus de toutes les conversations
    const total = CONVERSATIONS.reduce(function (sum, conv) {
        return sum + conv.unread; // Additionne les non lus
    }, 0);                        // Valeur initiale : 0

    const badge = document.getElementById("total-unread"); // Badge total
    if (badge) {
        badge.textContent = total; // Met à jour le texte
        badge.style.display = total > 0 ? "inline-flex" : "none"; // Cache si 0
    }
}

// ============================================================
// SCROLL VERS LE BAS DE LA ZONE DE MESSAGES
// ============================================================
function scrollToBottom() {
    const zone = document.getElementById("chat-messages"); // Zone de messages
    if (zone) {
        // Délai minimal pour que le DOM soit mis à jour avant de scroller
        setTimeout(function () {
            zone.scrollTop = zone.scrollHeight; // Scrolle tout en bas
        }, 30);
    }
}

// ============================================================
// AFFICHER UNE NOTIFICATION TOAST
// Message temporaire en bas à droite de l'écran
// ============================================================
function showToast(message, type) {
    const container = document.getElementById("toast-container"); // Conteneur des toasts
    const toast     = document.createElement("div");              // Crée l'élément
    toast.className = "toast " + (type || "info");                // Classe selon le type

    // Icône selon le type de toast
    const icons = {
        success: `<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--color-teal)" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>`,
        error:   `<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
        info:    `<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`
    };

    toast.innerHTML = (icons[type] || icons.info) + "<span>" + message + "</span>"; // Icône + texte
    container.appendChild(toast); // Ajoute au DOM

    // Supprime le toast après 3,5 secondes avec fondu de sortie
    setTimeout(function () {
        toast.style.transition = "opacity 0.4s ease"; // Active la transition
        toast.style.opacity    = "0";                 // Lance le fondu
        setTimeout(function () { toast.remove(); }, 400); // Supprime après le fondu
    }, 3500);
}

// ============================================================
// FONCTIONS UTILITAIRES
// ============================================================

// Tronque un texte à la longueur maxLen avec "..."
function truncate(text, maxLen) {
    if (!text) return "";                                    // Retourne vide si null
    return text.length > maxLen
        ? text.substring(0, maxLen) + "..."                 // Tronque si trop long
        : text;                                             // Retourne tel quel
}

// Ajoute un zéro devant les nombres < 10 (ex: 7 → "07")
function padTime(n) {
    return n < 10 ? "0" + n : "" + n; // Padding à gauche
}

// Retourne l'heure actuelle formatée (HH:MM)
function getCurrentTime() {
    const now = new Date();
    return padTime(now.getHours()) + ":" + padTime(now.getMinutes());
}

// Échappe les caractères HTML dangereux (protection XSS)
function escapeHtml(str) {
    if (!str) return "";
    return str
        .replace(/&/g,  "&amp;")   // Esperluette
        .replace(/</g,  "&lt;")    // Inférieur
        .replace(/>/g,  "&gt;")    // Supérieur
        .replace(/"/g,  "&quot;")  // Guillemet double
        .replace(/'/g,  "&#039;"); // Apostrophe
}

// Affiche un élément HTML (le rend visible)
function showEl(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = ""; // Supprime le display:none (revient à la valeur CSS)
}

// Masque un élément HTML
function hideEl(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = "none"; // Masque l'élément
}

// Définit le contenu texte d'un élément par son ID
function setTextContent(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text; // Modifie seulement si l'élément existe
}
