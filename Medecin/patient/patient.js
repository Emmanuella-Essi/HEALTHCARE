// ============================================================
// patients.js — Logique de la page liste des patients
// Gestion des données, filtres, pagination et actions
// ============================================================

// ---- DONNÉES MOCK : liste de patients de démonstration ----
// En production, ces données viendraient du serveur PHP via fetch/AJAX
const PATIENTS_DATA = [
    {
        id: 1,
        prenom: "Konan",
        nom: "Kouassi",
        email: "konan.k@email.com",
        tel: "+225 07 12 34 56",
        age: 34,
        sexe: "M",
        blood: "O+",
        allergies: "Pénicilline",
        derniere_consultation: "2024-12-10",
        statut: "Actif",
        initiales: "KK"
    },
    {
        id: 2,
        prenom: "Aya",
        nom: "Bamba",
        email: "aya.bamba@email.com",
        tel: "+225 05 98 76 54",
        age: 28,
        sexe: "F",
        blood: "A+",
        allergies: "Aucune",
        derniere_consultation: "2024-12-08",
        statut: "Actif",
        initiales: "AB"
    },
    {
        id: 3,
        prenom: "Sékou",
        nom: "Traoré",
        email: "sekou.t@email.com",
        tel: "+225 01 23 45 67",
        age: 52,
        sexe: "M",
        blood: "B+",
        allergies: "Aspirine, Latex",
        derniere_consultation: "2024-11-25",
        statut: "En observation",
        initiales: "ST"
    },
    {
        id: 4,
        prenom: "Fatou",
        nom: "Diallo",
        email: "fatou.d@email.com",
        tel: "+225 07 65 43 21",
        age: 19,
        sexe: "F",
        blood: "AB+",
        allergies: "Aucune",
        derniere_consultation: "2024-12-01",
        statut: "Actif",
        initiales: "FD"
    },
    {
        id: 5,
        prenom: "Moussa",
        nom: "Coulibaly",
        email: "moussa.c@email.com",
        tel: "+225 05 11 22 33",
        age: 67,
        sexe: "M",
        blood: "O-",
        allergies: "Sulfamides",
        derniere_consultation: "2024-10-15",
        statut: "En observation",
        initiales: "MC"
    },
    {
        id: 6,
        prenom: "Amina",
        nom: "Ouédraogo",
        email: "amina.o@email.com",
        tel: "+225 07 55 44 33",
        age: 41,
        sexe: "F",
        blood: "A-",
        allergies: "Lactose",
        derniere_consultation: "2024-12-05",
        statut: "Actif",
        initiales: "AO"
    },
    {
        id: 7,
        prenom: "Koffi",
        nom: "Assi",
        email: "koffi.a@email.com",
        tel: "+225 01 77 88 99",
        age: 23,
        sexe: "M",
        blood: "B-",
        allergies: "Aucune",
        derniere_consultation: "2024-09-20",
        statut: "Inactif",
        initiales: "KA"
    },
    {
        id: 8,
        prenom: "Marie",
        nom: "Yao",
        email: "marie.yao@email.com",
        tel: "+225 05 33 22 11",
        age: 36,
        sexe: "F",
        blood: "O+",
        allergies: "Ibuprofène",
        derniere_consultation: "2024-12-12",
        statut: "Actif",
        initiales: "MY"
    },
    {
        id: 9,
        prenom: "Ibrahim",
        nom: "Sanogo",
        email: "ibrahim.s@email.com",
        tel: "+225 07 44 55 66",
        age: 45,
        sexe: "M",
        blood: "A+",
        allergies: "Aucune",
        derniere_consultation: "2024-11-18",
        statut: "Actif",
        initiales: "IS"
    },
    {
        id: 10,
        prenom: "Cécile",
        nom: "Aké",
        email: "cecile.ake@email.com",
        tel: "+225 01 99 88 77",
        age: 30,
        sexe: "F",
        blood: "AB-",
        allergies: "Gluten",
        derniere_consultation: "2024-12-11",
        statut: "Actif",
        initiales: "CA"
    },
    {
        id: 11,
        prenom: "Lamine",
        nom: "Koné",
        email: "lamine.k@email.com",
        tel: "+225 05 66 77 88",
        age: 58,
        sexe: "M",
        blood: "O+",
        allergies: "Morphine",
        derniere_consultation: "2024-10-30",
        statut: "En observation",
        initiales: "LK"
    },
    {
        id: 12,
        prenom: "Nadia",
        nom: "Touré",
        email: "nadia.t@email.com",
        tel: "+225 07 22 33 44",
        age: 26,
        sexe: "F",
        blood: "B+",
        allergies: "Aucune",
        derniere_consultation: "2024-12-09",
        statut: "Actif",
        initiales: "NT"
    }
];

// ---- VARIABLES D'ÉTAT DE PAGINATION ----
let currentPage      = 1;    // Page courante (commence à 1)
const ITEMS_PER_PAGE = 8;    // Nombre de lignes par page

// ---- LISTE FILTRÉE (initialement tous les patients) ----
let filteredPatients = [...PATIENTS_DATA]; // Copie du tableau complet

// ============================================================
// INITIALISATION — Appelée au chargement de la page
// ============================================================
document.addEventListener("DOMContentLoaded", function () {
    // Met à jour le sous-titre avec le nombre total
    document.getElementById("patients-count").textContent =
        PATIENTS_DATA.length + " patients enregistrés";

    // Affiche le tableau avec tous les patients
    renderTable();
});

// ============================================================
// RENDU DU TABLEAU
// Affiche les patients de la page courante dans le <tbody>
// ============================================================
function renderTable() {
    const tbody = document.getElementById("patients-tbody"); // Corps du tableau
    tbody.innerHTML = ""; // Vide le contenu existant

    // Calcul des indices de début et fin pour la pagination
    const start = (currentPage - 1) * ITEMS_PER_PAGE; // Ex: page 2 → start=8
    const end   = start + ITEMS_PER_PAGE;              // Ex: end=16

    // Découpe le tableau filtré selon la page courante
    const pageItems = filteredPatients.slice(start, end);

    // Si aucun résultat, affiche un message
    if (pageItems.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:var(--color-text-muted);">
                    Aucun patient trouvé pour ces critères.
                </td>
            </tr>`;
        // Met à jour le compteur
        document.getElementById("table-count").textContent = "0 résultat";
        document.getElementById("pagination-info").textContent = "Aucun résultat";
        document.getElementById("pagination-buttons").innerHTML = "";
        return; // Arrête la fonction ici
    }

    // Génère une ligne HTML pour chaque patient de la page
    pageItems.forEach(function (p) {
        const tr = document.createElement("tr"); // Crée une ligne de tableau

        // Formate la date de dernière consultation
        const dateConsult = formatDate(p.derniere_consultation);

        // Détermine la classe CSS du badge selon le statut
        const badgeClass = getBadgeClass(p.statut);

        // Détermine la couleur de fond de l'avatar selon le sexe
        const avatarStyle = p.sexe === "F"
            ? "background:#FCE7F3;color:#9D174D;"  // Rose pour femme
            : "background:#EFF6FF;color:#1D4ED8;"; // Bleu pour homme

        // Construit le HTML de la ligne
        tr.innerHTML = `
            <td>
                <!-- Conteneur avatar + nom -->
                <div class="patient-info">
                    <!-- Avatar avec initiales du patient -->
                    <div class="avatar" style="${avatarStyle}">${p.initiales}</div>
                    <div>
                        <!-- Nom complet -->
                        <div class="patient-name">${p.prenom} ${p.nom}</div>
                        <!-- Email en petit -->
                        <div class="patient-email">${p.email}</div>
                    </div>
                </div>
            </td>
            <td>${p.age} ans</td>
            <td>
                <!-- Badge groupe sanguin rouge -->
                <span class="badge badge-blood">${p.blood}</span>
            </td>
            <td>${p.tel}</td>
            <td>${dateConsult}</td>
            <td>
                <!-- Badge statut coloré -->
                <span class="badge ${badgeClass}">${p.statut}</span>
            </td>
            <td>
                <!-- Bouton voir la fiche patient (lien vers patient-fiche.html) -->
                <a href="patient-fiche.html?id=${p.id}" class="btn-secondary" style="font-size:0.78rem;padding:6px 12px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Voir fiche
                </a>
            </td>
        `;

        tbody.appendChild(tr); // Ajoute la ligne au tableau
    });

    // Met à jour le compteur de résultats affiché
    document.getElementById("table-count").textContent =
        `(${filteredPatients.length} patient${filteredPatients.length > 1 ? "s" : ""})`;

    // Met à jour les infos de pagination
    const displayEnd = Math.min(end, filteredPatients.length);
    document.getElementById("pagination-info").textContent =
        `Affichage ${start + 1}–${displayEnd} sur ${filteredPatients.length}`;

    // Re-génère les boutons de pagination
    renderPagination();
}

// ============================================================
// RENDU DE LA PAGINATION
// Génère les boutons numérotés en bas du tableau
// ============================================================
function renderPagination() {
    const container   = document.getElementById("pagination-buttons"); // Conteneur boutons
    container.innerHTML = ""; // Vide les boutons existants

    // Calcule le nombre total de pages nécessaires
    const totalPages = Math.ceil(filteredPatients.length / ITEMS_PER_PAGE);

    // Bouton page précédente
    const prevBtn = document.createElement("button");
    prevBtn.className = "page-btn";
    prevBtn.innerHTML = "‹"; // Flèche gauche
    prevBtn.disabled  = currentPage === 1; // Désactivé sur la première page
    prevBtn.onclick   = function () { goToPage(currentPage - 1); }; // Page précédente
    container.appendChild(prevBtn);

    // Boutons numérotés de 1 à totalPages
    for (let i = 1; i <= totalPages; i++) {
        const btn     = document.createElement("button");
        btn.className = "page-btn" + (i === currentPage ? " active" : ""); // Actif si page courante
        btn.textContent = i;       // Numéro de la page
        btn.onclick = (function (page) {
            return function () { goToPage(page); }; // IIFE pour capturer i
        })(i);
        container.appendChild(btn);
    }

    // Bouton page suivante
    const nextBtn = document.createElement("button");
    nextBtn.className = "page-btn";
    nextBtn.innerHTML = "›"; // Flèche droite
    nextBtn.disabled  = currentPage === totalPages; // Désactivé sur la dernière page
    nextBtn.onclick   = function () { goToPage(currentPage + 1); }; // Page suivante
    container.appendChild(nextBtn);
}

// ============================================================
// NAVIGATION VERS UNE PAGE
// Change la page courante et re-rend le tableau
// ============================================================
function goToPage(page) {
    const totalPages = Math.ceil(filteredPatients.length / ITEMS_PER_PAGE);

    // Vérifie que la page est dans les limites valides
    if (page < 1 || page > totalPages) return;

    currentPage = page; // Met à jour la page courante
    renderTable();      // Re-rend le tableau

    // Fait défiler vers le haut du tableau
    document.querySelector(".table-card").scrollIntoView({ behavior: "smooth" });
}

// ============================================================
// FILTRAGE DES PATIENTS
// Appelée à chaque changement d'input ou de sélecteur
// ============================================================
function filterPatients() {
    // Récupère les valeurs des filtres (en minuscule pour comparaison insensible à la casse)
    const query  = document.getElementById("search-input").value.toLowerCase().trim();
    const sexe   = document.getElementById("filter-sexe").value;
    const blood  = document.getElementById("filter-blood").value;
    const status = document.getElementById("filter-status").value;

    // Filtre le tableau complet selon tous les critères actifs
    filteredPatients = PATIENTS_DATA.filter(function (p) {
        // Recherche textuelle : nom, prénom, email ou téléphone
        const matchQuery = !query ||
            (p.prenom + " " + p.nom).toLowerCase().includes(query) ||
            p.email.toLowerCase().includes(query) ||
            p.tel.includes(query);

        // Filtre sexe : vide = tous
        const matchSexe  = !sexe   || p.sexe   === sexe;

        // Filtre groupe sanguin : vide = tous
        const matchBlood = !blood  || p.blood   === blood;

        // Filtre statut : vide = tous
        const matchStat  = !status || p.statut  === status;

        // Le patient passe si TOUS les critères sont validés
        return matchQuery && matchSexe && matchBlood && matchStat;
    });

    currentPage = 1; // Revient à la première page après un filtre
    renderTable();   // Re-rend le tableau filtré
}

// ============================================================
// RÉINITIALISATION DES FILTRES
// Vide tous les champs et restaure la liste complète
// ============================================================
function resetFilters() {
    document.getElementById("search-input").value   = ""; // Vide la recherche
    document.getElementById("filter-sexe").value    = ""; // Réinitialise sexe
    document.getElementById("filter-blood").value   = ""; // Réinitialise groupe sanguin
    document.getElementById("filter-status").value  = ""; // Réinitialise statut

    filteredPatients = [...PATIENTS_DATA]; // Restaure tous les patients
    currentPage      = 1;                  // Revient à la page 1
    renderTable();                         // Re-rend le tableau
}

// ============================================================
// MODAL NOUVEAU PATIENT
// ============================================================

// Ouvre le modal d'ajout de patient
function openModalNouveauPatient() {
    document.getElementById("modal-nouveau-patient").classList.add("open");
}

// Ferme le modal d'ajout de patient
function closeModalNouveauPatient() {
    document.getElementById("modal-nouveau-patient").classList.remove("open");
    // Réinitialise les champs du formulaire
    document.querySelectorAll("#modal-nouveau-patient input, #modal-nouveau-patient select")
        .forEach(function (el) { el.value = ""; });
}

// Sauvegarde un nouveau patient (côté client uniquement — en prod, envoi PHP)
function saveNouveauPatient(event) {
    event.preventDefault(); // Empêche le rechargement de la page

    // Récupère les valeurs du formulaire
    const prenom = document.getElementById("p-prenom").value.trim();
    const nom    = document.getElementById("p-nom").value.trim();
    const dob    = document.getElementById("p-dob").value;
    const sexe   = document.getElementById("p-sexe").value;
    const email  = document.getElementById("p-email").value.trim();
    const tel    = document.getElementById("p-tel").value.trim();
    const blood  = document.getElementById("p-blood").value;
    const allerg = document.getElementById("p-allergies").value.trim();

    // Calcule l'âge à partir de la date de naissance
    const age = dob ? calcAge(dob) : 0;

    // Crée le nouvel objet patient
    const newPatient = {
        id:                    PATIENTS_DATA.length + 1,       // ID auto-incrémenté
        prenom:                prenom,
        nom:                   nom,
        email:                 email || "—",
        tel:                   tel   || "—",
        age:                   age,
        sexe:                  sexe,
        blood:                 blood || "—",
        allergies:             allerg || "Aucune",
        derniere_consultation: new Date().toISOString().split("T")[0], // Aujourd'hui
        statut:                "Actif",
        initiales:             (prenom[0] + nom[0]).toUpperCase()  // Initiales
    };

    // Ajoute le patient au tableau local (en prod : POST vers PHP)
    PATIENTS_DATA.push(newPatient);
    filteredPatients = [...PATIENTS_DATA]; // Resynchronise le filtre

    // Ferme le modal et re-rend le tableau
    closeModalNouveauPatient();
    renderTable();

    // Met à jour le compteur dans le sous-titre
    document.getElementById("patients-count").textContent =
        PATIENTS_DATA.length + " patients enregistrés";

    // Affiche une notification de succès
    showToast("Patient " + prenom + " " + nom + " ajouté avec succès !", "success");
}

// ============================================================
// FONCTIONS UTILITAIRES
// ============================================================

// Formate une date ISO (YYYY-MM-DD) en format lisible (ex: 10 déc. 2024)
function formatDate(isoDate) {
    if (!isoDate) return "—"; // Valeur par défaut si pas de date

    const date    = new Date(isoDate); // Crée un objet Date
    const options = { day: "numeric", month: "short", year: "numeric" }; // Format court
    return date.toLocaleDateString("fr-FR", options); // Formatage en français
}

// Calcule l'âge en années à partir d'une date de naissance
function calcAge(dob) {
    const today    = new Date();
    const birthDate = new Date(dob);
    let age = today.getFullYear() - birthDate.getFullYear(); // Différence d'années
    const m = today.getMonth() - birthDate.getMonth();       // Différence de mois

    // Si l'anniversaire n'est pas encore passé cette année, soustraire 1
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}

// Retourne la classe CSS du badge selon le statut
function getBadgeClass(statut) {
    if (statut === "Actif")           return "badge-active";   // Vert
    if (statut === "En observation")  return "badge-warning";  // Jaune/orange
    if (statut === "Inactif")         return "badge-inactive"; // Gris
    return "badge-inactive"; // Défaut gris
}

// Affiche une notification toast temporaire en bas à droite
function showToast(message, type) {
    const container = document.getElementById("toast-container"); // Conteneur des toasts
    const toast     = document.createElement("div");              // Crée l'élément toast

    toast.className = "toast " + (type || "success"); // Applique la classe de style

    // Icône selon le type
    const icon = type === "success"
        ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>`
        : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;

    toast.innerHTML = icon + "<span>" + message + "</span>"; // Contenu du toast
    container.appendChild(toast); // Ajoute au DOM

    // Supprime automatiquement après 3 secondes
    setTimeout(function () {
        toast.style.opacity = "0";           // Fondu de sortie
        toast.style.transition = "opacity 0.4s";
        setTimeout(function () { toast.remove(); }, 400); // Supprime du DOM
    }, 3000);
}

// Ferme le modal si on clique en dehors de la boîte
document.getElementById("modal-nouveau-patient").addEventListener("click", function (e) {
    // Si le clic est sur l'overlay (fond), pas sur la boîte
    if (e.target === this) closeModalNouveauPatient();
});