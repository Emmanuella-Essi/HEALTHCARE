// ============================================================
// patients.js — Logique de la page liste des patients
// Gestion des données, filtres, pagination et actions
// ============================================================

// ---- VARIABLES GLOBALES ----
let patientsData = []; // Données chargées depuis l'API
let filteredPatients = [];
let currentPage = 1;
const ITEMS_PER_PAGE = 8;
const API_BASE = '/api'; // Base URL de l'API

// ---- FONCTION POUR OBTENIR LE TOKEN D'AUTHENTIFICATION ----
function getToken() {
    return localStorage.getItem('authToken'); // Token stocké après login
}

// ---- FONCTION POUR AFFICHER LES TOASTS (NOTIFICATIONS) ----
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000); // Disparaît après 3 secondes
}

// ---- CHARGER LES PATIENTS DEPUIS L'API ----
async function loadPatients() {
    try {
        const response = await fetch(`${API_BASE}/patients`, {
            headers: {
                'Authorization': `Bearer ${getToken()}`
            }
        });
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = '../login.html'; // Rediriger si non authentifié
                return;
            }
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        const data = await response.json();
        patientsData = data.data || [];
        filteredPatients = [...patientsData];
        updateStats();
        renderTable();
    } catch (error) {
        console.error('Erreur chargement patients:', error);
        showToast('Erreur lors du chargement des patients: ' + error.message, 'error');
    }
}

// ---- METTRE À JOUR LES STATISTIQUES ----
function updateStats() {
    const total = patientsData.length;
    document.getElementById('patients-count').textContent = `${total} patients enregistrés`;
    document.getElementById('stat-total').textContent = total;
    // Calculer les nouveaux patients (ex: ce mois-ci)
    const now = new Date();
    const thisMonth = patientsData.filter(p => {
        const created = new Date(p.created_at);
        return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
    }).length;
    document.getElementById('stat-nouveaux').textContent = thisMonth;
}

// ---- CALCULER L'ÂGE ----
function calculateAge(dateNaissance) {
    const birth = new Date(dateNaissance);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    return age;
}

// ---- RENDU DU TABLEAU ----
function renderTable() {
    const tbody = document.querySelector('#patients-table tbody');
    if (!tbody) return;
    tbody.innerHTML = '';
    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;
    const pagePatients = filteredPatients.slice(start, end);
    pagePatients.forEach(patient => {
        const age = calculateAge(patient.date_naissance);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><div class="patient-avatar">${patient.prenom[0]}${patient.nom[0]}</div></td>
            <td>${patient.prenom} ${patient.nom}</td>
            <td>${patient.email}</td>
            <td>${patient.telephone || '-'}</td>
            <td>${age} ans</td>
            <td><span class="badge badge-${patient.sexe.toLowerCase()}">${patient.sexe}</span></td>
            <td>${patient.groupe_sanguin || '-'}</td>
            <td>${patient.allergies || 'Aucune'}</td>
            <td>Actif</td>
            <td><button class="btn-icon" onclick="viewPatient(${patient.id})" title="Voir détails"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></td>
        `;
        tbody.appendChild(row);
    });
    document.getElementById('table-count').textContent = `(${filteredPatients.length} résultats)`;
}

// ---- FONCTIONS DE FILTRAGE ----
function filterPatients() {
    const search = document.getElementById('search-input').value.toLowerCase();
    const sexe = document.getElementById('filter-sexe').value;
    const blood = document.getElementById('filter-blood').value;
    const status = document.getElementById('filter-status').value;
    filteredPatients = patientsData.filter(p => {
        const fullText = `${p.prenom} ${p.nom} ${p.email} ${p.telephone || ''}`.toLowerCase();
        return fullText.includes(search) &&
               (!sexe || p.sexe === sexe) &&
               (!blood || p.groupe_sanguin === blood) &&
               (!status || status === 'Actif'); // Ajuster selon logique de statut
    });
    currentPage = 1;
    renderTable();
}

function resetFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('filter-sexe').value = '';
    document.getElementById('filter-blood').value = '';
    document.getElementById('filter-status').value = '';
    filteredPatients = [...patientsData];
    renderTable();
}

// ---- FONCTIONS DU MODAL ----
function openModalNouveauPatient() {
    document.getElementById('modal-nouveau-patient').style.display = 'flex';
}

function closeModalNouveauPatient() {
    document.getElementById('modal-nouveau-patient').style.display = 'none';
    document.getElementById('patient-form').reset();
}

async function saveNouveauPatient(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = {
        nom: formData.get('nom'),
        prenom: formData.get('prenom'),
        email: formData.get('email'),
        date_naissance: formData.get('date_naissance'),
        sexe: formData.get('sexe'),
        telephone: formData.get('telephone'),
        groupe_sanguin: formData.get('groupe_sanguin'),
        allergies: formData.get('allergies'),
        adresse: formData.get('adresse') || '',
        ville: formData.get('ville') || ''
    };
    try {
        const response = await fetch(`${API_BASE}/patients`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getToken()}`
            },
            body: JSON.stringify(data)
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Erreur ajout patient');
        }
        const result = await response.json();
        showToast(result.message || 'Patient ajouté avec succès', 'success');
        closeModalNouveauPatient();
        loadPatients(); // Recharger la liste
    } catch (error) {
        console.error('Erreur ajout patient:', error);
        showToast('Erreur : ' + error.message, 'error');
    }
}

// ---- FONCTION POUR VOIR LES DÉTAILS D'UN PATIENT ----
function viewPatient(id) {
    // Rediriger vers une page de détails ou ouvrir un modal
    window.location.href = `patient-fiche.html?id=${id}`;
}

// ---- INITIALISATION ----
document.addEventListener('DOMContentLoaded', () => {
    if (!getToken()) {
        showToast('Veuillez vous connecter', 'error');
        setTimeout(() => window.location.href = '../login.html', 2000);
        return;
    }
    loadPatients();
});
