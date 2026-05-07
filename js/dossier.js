/* ============================================
   DOSSIER MÉDICAL — JavaScript
   MediCare Patient Dashboard
   ============================================ */

// ---- DATA (simulation base de données) ----

// Tableau qui simule une base de données (stockage temporaire en mémoire)
let entries = [
  {
    id: 1, // Identifiant unique pour chaque entrée (important pour modifier/supprimer)
    type: "consultation", // Type d’entrée (sert pour les filtres)
    date: "2025-05-02", // Format brut utilisé pour trier
    dateDisplay: "02 Mai 2025", // Format lisible pour l'utilisateur
    title: "Consultation générale — Dr. Kouamé", // Titre affiché
    desc: "Examen de routine...", // Description détaillée
    tags: ["Tension: 120/80", "Poids: 68 kg", "Dr. Kouamé"] // Mots-clés pour recherche
  }
];

// Filtre actuel (par défaut = afficher tout)
let currentFilter = "tous";

// Compteur pour générer un nouvel ID automatiquement
let nextId = 6;


// ---- DOM Elements ----

// On récupère tous les éléments HTML nécessaires pour interagir avec la page
const modalOverlay  = document.getElementById("modalOverlay"); // fond du modal
const modal         = document.getElementById("modal"); // boîte du modal
const openModalBtn  = document.getElementById("openModal"); // bouton ouvrir
const closeModalBtn = document.getElementById("closeModal"); // bouton fermer
const cancelBtn     = document.getElementById("cancelModal"); // bouton annuler
const saveBtn       = document.getElementById("saveEntry"); // bouton enregistrer
const timeline      = document.getElementById("timeline"); // conteneur des cartes
const searchInput   = document.getElementById("searchInput"); // champ de recherche
const toast         = document.getElementById("toast"); // notification visuelle
const toastMsg      = document.getElementById("toastMsg"); // texte du toast
const menuToggle    = document.getElementById("menuToggle"); // bouton menu mobile
const sidebar       = document.getElementById("sidebar"); // sidebar
const totalEntriesStat = document.getElementById("totalEntries"); // stats
const filterBtns    = document.querySelectorAll(".filter-btn"); // boutons filtre


// ---- MODAL OPEN/CLOSE ----

// Fonction pour ouvrir le modal
function openModal(editMode = false) {

  modalOverlay.classList.add("open"); 
  // Ajoute une classe CSS pour afficher le modal

  document.body.style.overflow = "hidden"; 
  // Empêche le scroll en arrière-plan

  if (!editMode) {
    // Si on est en mode création (pas modification)

    document.getElementById("modalTitle").textContent = "Nouvelle Entrée Médicale";
    // Change le titre du modal

    document.getElementById("editId").value = "";
    // Vide l’ID (car nouvelle entrée)

    document.getElementById("entryType").value = "consultation";
    // Valeur par défaut

    document.getElementById("entryDate").value = new Date().toISOString().split("T")[0];
    // Met la date d’aujourd’hui automatiquement

    document.getElementById("entryTitle").value = "";
    document.getElementById("entryDesc").value = "";
    document.getElementById("entryTags").value = "";
    // Réinitialise les champs
  }
}

// Fermer le modal
function closeModal() {
  modalOverlay.classList.remove("open"); // cache le modal
  document.body.style.overflow = ""; // réactive le scroll
}

// Événements clics
openModalBtn.addEventListener("click", () => openModal(false));
closeModalBtn.addEventListener("click", closeModal);
cancelBtn.addEventListener("click", closeModal);

// Fermer si clic à l’extérieur
modalOverlay.addEventListener("click", (e) => {
  if (e.target === modalOverlay) closeModal();
});


// ---- SAVE ENTRY ----

// Quand on clique sur "Enregistrer"
saveBtn.addEventListener("click", () => {

  // Récupération des valeurs du formulaire
  const type    = document.getElementById("entryType").value;
  const dateRaw = document.getElementById("entryDate").value;
  const title   = document.getElementById("entryTitle").value.trim();
  const desc    = document.getElementById("entryDesc").value.trim();
  const tagsRaw = document.getElementById("entryTags").value.trim();
  const editId  = document.getElementById("editId").value;

  // Validation simple
  if (!title || !dateRaw) {
    showToast("⚠️ Veuillez remplir le titre et la date.", false);
    return; // stop si invalide
  }

  // Transformation des tags en tableau
  const tags = tagsRaw 
    ? tagsRaw.split(",").map(t => t.trim()).filter(Boolean)
    : [];

  // Formatage de la date
  const dateDisplay = formatDate(dateRaw);

  if (editId) {
    // MODE MODIFICATION

    const idx = entries.findIndex(e => e.id === parseInt(editId));
    // Trouve l’index de l’entrée

    if (idx !== -1) {
      entries[idx] = { 
        ...entries[idx], // garde anciennes données
        type, date: dateRaw, dateDisplay, title, desc, tags 
      };
    }

    showToast("✏️ Entrée modifiée avec succès !");
  } else {
    // MODE AJOUT

    entries.unshift({ 
      id: nextId++, // nouvel ID auto
      type, date: dateRaw, dateDisplay, title, desc, tags 
    });

    showToast("✅ Entrée ajoutée avec succès !");
  }

  closeModal(); // ferme le modal
  renderTimeline(); // recharge l'affichage
  updateStats(); // met à jour stats
});


// ---- EDIT ENTRY ----

// Fonction appelée quand on clique sur modifier
function editEntry(id) {

  const entry = entries.find(e => e.id === id);
  // Cherche l’entrée correspondante

  if (!entry) return;

  // Remplit le formulaire avec les données existantes
  document.getElementById("modalTitle").textContent = "Modifier l'Entrée";
  document.getElementById("editId").value  = entry.id;
  document.getElementById("entryType").value  = entry.type;
  document.getElementById("entryDate").value  = entry.date;
  document.getElementById("entryTitle").value = entry.title;
  document.getElementById("entryDesc").value  = entry.desc;
  document.getElementById("entryTags").value  = entry.tags.join(", ");

  openModal(true); // ouvre en mode édition
}


// ---- DELETE ENTRY ----

// Supprimer une entrée
function deleteEntry(id) {

  const card = document.querySelector(`.timeline-item[data-id="${id}"]`);
  // Récupère la carte visuelle

  if (card) {
    // Animation de disparition
    card.style.transition = "opacity 0.3s, transform 0.3s";
    card.style.opacity = "0";
    card.style.transform = "translateX(-20px)";

    setTimeout(() => {
      // Suppression réelle après animation
      entries = entries.filter(e => e.id !== id);
      renderTimeline();
      updateStats();
      showToast("🗑️ Entrée supprimée.");
    }, 300);
  }
}

// Rendre fonctions accessibles en HTML
window.editEntry = editEntry;
window.deleteEntry = deleteEntry;


// ---- FILTER ----

// Gestion des filtres
filterBtns.forEach(btn => {
  btn.addEventListener("click", () => {

    filterBtns.forEach(b => b.classList.remove("active"));
    // Désactive tous

    btn.classList.add("active");
    // Active celui cliqué

    currentFilter = btn.dataset.filter;
    // Change filtre actuel

    renderTimeline();
  });
});


// ---- SEARCH ----

// Anti-spam : attend 200ms avant de lancer la recherche
let searchTimeout;

searchInput.addEventListener("input", () => {
  clearTimeout(searchTimeout);

  searchTimeout = setTimeout(() => renderTimeline(), 200);
});


// ---- RENDER TIMELINE ----

// Fonction principale d’affichage
function renderTimeline() {

  const query = searchInput.value.trim().toLowerCase();
  let filtered = [...entries]; // copie tableau

  // Filtrage par type
  if (currentFilter !== "tous") {
    filtered = filtered.filter(e => e.type === currentFilter);
  }

  // Filtrage par recherche
  if (query) {
    filtered = filtered.filter(e =>
      e.title.toLowerCase().includes(query) ||
      e.desc.toLowerCase().includes(query) ||
      e.tags.some(t => t.toLowerCase().includes(query))
    );
  }

  // Tri par date (plus récent en haut)
  filtered.sort((a, b) => new Date(b.date) - new Date(a.date));

  timeline.innerHTML = ""; // reset

  if (filtered.length === 0) {
    // Cas aucun résultat
    timeline.innerHTML = `<div>🔍 Aucune entrée trouvée</div>`;
    return;
  }

  // Génération des cartes
  filtered.forEach((entry, i) => {

    const item = document.createElement("div");

    item.className = "timeline-item";
    item.dataset.type = entry.type;
    item.dataset.id   = entry.id;

    // Animation progressive
    item.style.animationDelay = `${i * 0.07}s`;

    const tagsHTML = entry.tags
      .map(t => `<span class="tag">${t}</span>`)
      .join("");

    item.innerHTML = `
      <div class="timeline-dot ${entry.type}"></div>
      <div class="timeline-card">
        <div class="timeline-card-header">
          <span class="timeline-type ${entry.type}">${capitalize(entry.type)}</span>
          <span class="timeline-date">${entry.dateDisplay}</span>
        </div>
        <div class="timeline-card-body">
          <h3 class="timeline-title">${entry.title}</h3>
          <p class="timeline-desc">${entry.desc}</p>
          <div class="timeline-tags">${tagsHTML}</div>
        </div>
        <div class="timeline-card-actions">
          <button onclick="editEntry(${entry.id})" class="btn-icon">✏️</button>
          <button onclick="deleteEntry(${entry.id})" class="btn-icon delete">🗑️</button>
        </div>
      </div>`;

    timeline.appendChild(item);
  });
}


// ---- UPDATE STATS ----

// Met à jour le nombre total
function updateStats() {
  if (totalEntriesStat) totalEntriesStat.textContent = entries.length;
}


// ---- TOAST ----

// Notification temporaire
let toastTimer;

function showToast(msg, success = true) {
  toastMsg.textContent = msg;
  toast.classList.add("show");

  clearTimeout(toastTimer);

  toastTimer = setTimeout(() => 
    toast.classList.remove("show"), 3000
  );
}


// ---- SIDEBAR MOBILE ----

if (menuToggle) {
  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("open");
  });

  document.addEventListener("click", (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
      sidebar.classList.remove("open");
    }
  });
}


// ---- UTILS ----

// Format date en français
function formatDate(dateStr) {
  const months = ["Janvier","Février","Mars","Avril","Mai","Juin",
                  "Juillet","Août","Septembre","Octobre","Novembre","Décembre"];

  const d = new Date(dateStr + "T00:00:00");

  return `${String(d.getDate()).padStart(2,'0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

// Met une majuscule
function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}


// ---- INIT ----

// Au chargement de la page
document.addEventListener("DOMContentLoaded", () => {

  updateStats(); // met à jour stats

  const dateInput = document.getElementById("entryDate");

  if (dateInput) {
    dateInput.value = new Date().toISOString().split("T")[0];
    // Date automatique
  }
});