/**
 * vaccins.js — Gestion complète du module vaccins
 * Dashboard Patient – MediCare
 */

/* ─────────────────────────────────────────
   DONNÉES (simulées — à remplacer par API)
───────────────────────────────────────── */
let vaccins = [
  {
    id: 1,
    nom: "BCG (Tuberculose)",
    statut: "fait",
    dateAdmin: "2001-03-15",
    dateRappel: null,
    notes: "Administré à la naissance. Cicatrice visible bras gauche."
  },
  {
    id: 2,
    nom: "Hépatite B",
    statut: "fait",
    dateAdmin: "2022-06-10",
    dateRappel: "2027-06-10",
    notes: "3 doses complétées. Prochain rappel dans 5 ans."
  },
  {
    id: 3,
    nom: "Diphtérie – Tétanos – Polio",
    statut: "rappel",
    dateAdmin: "2015-09-20",
    dateRappel: "2025-09-20",
    notes: "Rappel dépassé. Consulter votre médecin rapidement."
  },
  {
    id: 4,
    nom: "Fièvre Typhoïde",
    statut: "a-venir",
    dateAdmin: null,
    dateRappel: "2024-12-01",
    notes: "Recommandé avant voyage. Non encore effectué."
  },
  {
    id: 5,
    nom: "COVID-19 (Moderna)",
    statut: "fait",
    dateAdmin: "2023-10-05",
    dateRappel: "2024-10-05",
    notes: "Booster automnal. Aucun effet secondaire notable."
  },
  {
    id: 6,
    nom: "Méningite ACYW",
    statut: "a-venir",
    dateAdmin: null,
    dateRappel: null,
    notes: ""
  }
];

let nextId = 7;
let filterActif = "tous";
let searchQuery = "";
let deleteTargetId = null;

/* ─────────────────────────────────────────
   INITIALISATION
───────────────────────────────────────── */
document.addEventListener("DOMContentLoaded", () => {
  renderVaccins();
  updateStats();
  bindEvents();
});

/* ─────────────────────────────────────────
   RENDU DES CARTES
───────────────────────────────────────── */
function renderVaccins() {
  const grid = document.getElementById("vaccinsGrid");
  const emptyState = document.getElementById("emptyState");

  const filtered = vaccins.filter(v => {
    const matchFilter =
      filterActif === "tous" ||
      v.statut === filterActif ||
      (filterActif === "rappel" && v.statut === "rappel");

    const matchSearch =
      searchQuery === "" ||
      v.nom.toLowerCase().includes(searchQuery.toLowerCase()) ||
      (v.notes && v.notes.toLowerCase().includes(searchQuery.toLowerCase()));

    return matchFilter && matchSearch;
  });

  grid.innerHTML = "";

  if (filtered.length === 0) {
    emptyState.classList.remove("hidden");
    return;
  }
  emptyState.classList.add("hidden");

  filtered.forEach((v, i) => {
    const card = document.createElement("div");
    card.className = "vaccine-card";
    card.style.animationDelay = `${i * 0.06}s`;
    card.innerHTML = buildCardHTML(v);
    grid.appendChild(card);
  });

  // Bind actions sur les cartes
  grid.querySelectorAll(".btn-edit").forEach(btn => {
    btn.addEventListener("click", () => openEditModal(parseInt(btn.dataset.id)));
  });
  grid.querySelectorAll(".btn-delete").forEach(btn => {
    btn.addEventListener("click", () => openDeleteModal(parseInt(btn.dataset.id)));
  });
}

function buildCardHTML(v) {
  const badgeLabel = { fait: "✅ Effectué", "a-venir": "⏳ À venir", rappel: "⚠️ Rappel" };
  const badgeClass = { fait: "badge-fait", "a-venir": "badge-a-venir", rappel: "badge-rappel" };

  const formatDate = d => d
    ? new Date(d).toLocaleDateString("fr-FR", { day: "2-digit", month: "short", year: "numeric" })
    : "—";

  return `
    <div class="card-top">
      <span class="card-name">${escHtml(v.nom)}</span>
      <span class="card-badge ${badgeClass[v.statut]}">${badgeLabel[v.statut]}</span>
    </div>

    <div class="card-dates">
      <div class="card-date-item">
        <span class="card-date-label">💉 Administré</span>
        <span class="card-date-value">${formatDate(v.dateAdmin)}</span>
      </div>
      <div class="card-date-item">
        <span class="card-date-label">🔔 Rappel</span>
        <span class="card-date-value">${formatDate(v.dateRappel)}</span>
      </div>
    </div>

    ${v.notes ? `<p class="card-notes">📝 ${escHtml(v.notes)}</p>` : ""}

    <div class="card-actions">
      <button class="btn-edit" data-id="${v.id}">✏️ Modifier</button>
      <button class="btn-delete" data-id="${v.id}">🗑️ Supprimer</button>
    </div>
  `;
}

/* ─────────────────────────────────────────
   STATS
───────────────────────────────────────── */
function updateStats() {
  const total    = vaccins.length;
  const faits    = vaccins.filter(v => v.statut === "fait").length;
  const avenir   = vaccins.filter(v => v.statut === "a-venir").length;
  const urgents  = vaccins.filter(v => v.statut === "rappel").length;

  animateCount("totalVaccins",    total);
  animateCount("vaccinsEffectues", faits);
  animateCount("vaccinsAVenir",   avenir);
  animateCount("vaccinsUrgents",  urgents);
}

function animateCount(id, target) {
  const el = document.getElementById(id);
  let start = 0;
  const step = () => {
    start++;
    el.textContent = start;
    if (start < target) requestAnimationFrame(step);
  };
  if (target > 0) requestAnimationFrame(step);
  else el.textContent = "0";
}

/* ─────────────────────────────────────────
   ÉVÉNEMENTS
───────────────────────────────────────── */
function bindEvents() {
  // Recherche
  document.getElementById("searchInput").addEventListener("input", e => {
    searchQuery = e.target.value;
    renderVaccins();
  });

  // Filtres
  document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      filterActif = btn.dataset.filter;
      renderVaccins();
    });
  });

  // Ouvrir modal ajout
  document.getElementById("btnAjouter").addEventListener("click", openAddModal);

  // Fermer modals
  document.getElementById("modalClose").addEventListener("click", closeModal);
  document.getElementById("btnAnnuler").addEventListener("click", closeModal);
  document.getElementById("modalOverlay").addEventListener("click", e => {
    if (e.target === e.currentTarget) closeModal();
  });

  // Submit formulaire
  document.getElementById("vaccineForm").addEventListener("submit", handleFormSubmit);

  // Modal suppression
  document.getElementById("deleteClose").addEventListener("click", closeDeleteModal);
  document.getElementById("deleteCancelBtn").addEventListener("click", closeDeleteModal);
  document.getElementById("deleteConfirmBtn").addEventListener("click", confirmDelete);
  document.getElementById("deleteOverlay").addEventListener("click", e => {
    if (e.target === e.currentTarget) closeDeleteModal();
  });

  // Sidebar mobile
  document.getElementById("menuToggle").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.toggle("open");
  });
}

/* ─────────────────────────────────────────
   MODAL AJOUT / ÉDITION
───────────────────────────────────────── */
function openAddModal() {
  document.getElementById("modalTitle").textContent = "Ajouter un vaccin";
  document.getElementById("vaccineForm").reset();
  document.getElementById("vaccineId").value = "";
  document.getElementById("modalOverlay").classList.remove("hidden");
}

function openEditModal(id) {
  const v = vaccins.find(x => x.id === id);
  if (!v) return;

  document.getElementById("modalTitle").textContent = "Modifier le vaccin";
  document.getElementById("vaccineId").value = v.id;
  document.getElementById("vaccinNom").value    = v.nom;
  document.getElementById("vaccinStatut").value = v.statut;
  document.getElementById("vaccinDate").value   = v.dateAdmin || "";
  document.getElementById("vaccinRappel").value = v.dateRappel || "";
  document.getElementById("vaccinNotes").value  = v.notes || "";
  document.getElementById("modalOverlay").classList.remove("hidden");
}

function closeModal() {
  document.getElementById("modalOverlay").classList.add("hidden");
}

function handleFormSubmit(e) {
  e.preventDefault();

  const id      = document.getElementById("vaccineId").value;
  const nom     = document.getElementById("vaccinNom").value.trim();
  const statut  = document.getElementById("vaccinStatut").value;
  const dateAdm = document.getElementById("vaccinDate").value || null;
  const dateRap = document.getElementById("vaccinRappel").value || null;
  const notes   = document.getElementById("vaccinNotes").value.trim();

  if (!nom || !statut) {
    showToast("Veuillez remplir les champs obligatoires.", "error");
    return;
  }

  if (id) {
    // MODIFICATION
    const idx = vaccins.findIndex(v => v.id === parseInt(id));
    if (idx !== -1) {
      vaccins[idx] = { id: parseInt(id), nom, statut, dateAdmin: dateAdm, dateRappel: dateRap, notes };
      showToast("✅ Vaccin modifié avec succès !", "success");
    }
  } else {
    // AJOUT
    vaccins.push({ id: nextId++, nom, statut, dateAdmin: dateAdm, dateRappel: dateRap, notes });
    showToast("💉 Vaccin ajouté avec succès !", "success");
  }

  closeModal();
  renderVaccins();
  updateStats();

  // Optionnel : sync avec API PHP
  // syncWithServer(id ? "edit" : "add", ...);
}

/* ─────────────────────────────────────────
   SUPPRESSION
───────────────────────────────────────── */
function openDeleteModal(id) {
  deleteTargetId = id;
  document.getElementById("deleteOverlay").classList.remove("hidden");
}
function closeDeleteModal() {
  deleteTargetId = null;
  document.getElementById("deleteOverlay").classList.add("hidden");
}
function confirmDelete() {
  if (deleteTargetId === null) return;
  vaccins = vaccins.filter(v => v.id !== deleteTargetId);
  closeDeleteModal();
  renderVaccins();
  updateStats();
  showToast("🗑️ Vaccin supprimé.", "success");
}

/* ─────────────────────────────────────────
   TOAST
───────────────────────────────────────── */
function showToast(msg, type = "default") {
  const toast = document.getElementById("toast");
  toast.textContent = msg;
  toast.className = `toast ${type}`;
  setTimeout(() => toast.classList.add("hidden"), 3000);
}

/* ─────────────────────────────────────────
   UTILITAIRES
───────────────────────────────────────── */
function escHtml(str) {
  if (!str) return "";
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

/* ─────────────────────────────────────────
   SYNC SERVEUR (exemple PHP)
   À décommenter et adapter pour production
───────────────────────────────────────── */
/*
async function syncWithServer(action, data) {
  try {
    const res = await fetch("vaccins_api.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action, ...data })
    });
    const result = await res.json();
    if (!result.success) showToast("Erreur serveur.", "error");
  } catch (err) {
    showToast("Connexion impossible au serveur.", "error");
  }
}
*/