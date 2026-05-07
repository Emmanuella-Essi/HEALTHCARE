// ===== STORAGE KEY =====
const STORAGE_KEY = 'healthcare_ordonnances';

// ===== DÉMO DATA =====
const DEMO = [
  {
    id: 1,
    medecin: "Dr. Kouassi Brou",
    motif: "Cardiologue — Suivi hypertension",
    date: "2025-04-15",
    expiration: "2025-07-15",
    medicaments: [
      { nom: "Amlodipine", dose: "5mg", posologie: "1 cp/jour le matin" },
      { nom: "Bisoprolol", dose: "2.5mg", posologie: "1 cp/jour" },
      { nom: "Aspirine", dose: "100mg", posologie: "1 cp/jour pendant le repas" }
    ],
    notes: "Éviter l'exposition prolongée au soleil. Contrôle tensionnel dans 3 mois."
  },
  {
    id: 2,
    medecin: "Dr. Yao Ama",
    motif: "Dermatologue — Traitement acné",
    date: "2025-05-02",
    expiration: "2025-08-02",
    medicaments: [
      { nom: "Doxycycline", dose: "100mg", posologie: "1 gélule/jour pendant 3 mois" },
      { nom: "Adapalène gel", dose: "0.1%", posologie: "Application locale le soir" }
    ],
    notes: "Appliquer la crème solaire SPF50+ chaque matin. Éviter l'alcool."
  },
  {
    id: 3,
    medecin: "Dr. Diallo Seydou",
    motif: "Médecin généraliste — Infection respiratoire",
    date: "2025-02-10",
    expiration: "2025-03-10",
    medicaments: [
      { nom: "Amoxicilline", dose: "500mg", posologie: "3 cp/jour pendant 7 jours" },
      { nom: "Paracétamol", dose: "1000mg", posologie: "Si fièvre > 38.5°C" }
    ],
    notes: "Terminer le traitement antibiotique même si amélioration."
  }
];

// ===== UTILS =====
const MOIS = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
const MOIS_FULL = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

function getData() {
  const s = localStorage.getItem(STORAGE_KEY);
  if (!s) { localStorage.setItem(STORAGE_KEY, JSON.stringify(DEMO)); return DEMO; }
  return JSON.parse(s);
}

function saveData(list) { localStorage.setItem(STORAGE_KEY, JSON.stringify(list)); }

function getNextId(list) { return list.length ? Math.max(...list.map(r => r.id)) + 1 : 1; }

function formatDate(str) {
  if (!str) return '—';
  const d = new Date(str + 'T00:00:00');
  return `${d.getDate()} ${MOIS_FULL[d.getMonth()]} ${d.getFullYear()}`;
}

function isExpired(str) {
  if (!str) return false;
  return new Date(str + 'T00:00:00') < new Date();
}

function getStatut(ordo) { return isExpired(ordo.expiration) ? 'expiree' : 'active'; }

// ===== STATS =====
function updateStats(list) {
  document.getElementById('statTotal').textContent = list.length;
  document.getElementById('statActive').textContent = list.filter(o => !isExpired(o.expiration)).length;
  document.getElementById('statExpire').textContent = list.filter(o => isExpired(o.expiration)).length;
  const total = list.reduce((acc, o) => acc + (o.medicaments?.length || 0), 0);
  document.getElementById('statMedicaments').textContent = total;
}

// ===== RENDER GRID =====
function renderGrid(list) {
  const grid = document.getElementById('ordoGrid');
  if (!list.length) {
    grid.innerHTML = `
      <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p>Aucune ordonnance trouvée.</p>
        <button class="btn-primary" onclick="openModal()">+ Ajouter une ordonnance</button>
      </div>`;
    return;
  }

  const sorted = [...list].sort((a, b) => new Date(b.date) - new Date(a.date));

  grid.innerHTML = sorted.map((o, i) => {
    const statut = getStatut(o);
    const expired = statut === 'expiree';
    const meds = o.medicaments || [];
    const preview = meds.slice(0, 2);
    const more = meds.length - 2;

    return `
    <div class="ordo-card" style="animation-delay:${i * 0.07}s">
      <div class="ordo-card-header${expired ? ' expired' : ''}">
        <div class="ordo-medecin">${o.medecin}</div>
        <div class="ordo-motif">${o.motif || ''}</div>
      </div>
      <div class="ordo-card-body">
        <div class="ordo-dates">
          <div class="ordo-date-item">
            <span class="ordo-date-label">Prescrite le</span>
            <span class="ordo-date-val">${formatDate(o.date)}</span>
          </div>
          <div class="ordo-date-item">
            <span class="ordo-date-label">Expire le</span>
            <span class="ordo-date-val" style="${expired ? 'color:#EF4444' : ''}">${formatDate(o.expiration)}</span>
          </div>
        </div>
        <div class="ordo-meds-preview">
          ${preview.map(m => `
            <div class="med-chip">
              <span class="med-name">💊 ${m.nom}</span>
              <span class="med-dose">${m.dose}</span>
            </div>`).join('')}
          ${more > 0 ? `<div class="med-more">+${more} médicament${more > 1 ? 's' : ''}</div>` : ''}
          ${!meds.length ? `<div class="med-more" style="font-style:italic">Aucun médicament renseigné</div>` : ''}
        </div>
      </div>
      <div class="ordo-card-footer">
        <span class="badge badge-${statut}">${statut === 'active' ? '✅ Active' : '⏰ Expirée'}</span>
        <div class="ordo-actions">
          <button class="btn-icon" title="Voir le détail" onclick="viewDetail(${o.id})">👁️</button>
          <button class="btn-icon" title="Modifier" onclick="editOrdo(${o.id})">✏️</button>
          <button class="btn-icon delete" title="Supprimer" onclick="deleteOrdo(${o.id})">🗑️</button>
        </div>
      </div>
    </div>`;
  }).join('');
}

// ===== FILTRES =====
function applyFilters() {
  let list = getData();
  const search = document.getElementById('searchInput').value.trim().toLowerCase();
  const statut = document.getElementById('filterStatut').value;

  if (search) {
    list = list.filter(o =>
      o.medecin.toLowerCase().includes(search) ||
      (o.motif || '').toLowerCase().includes(search) ||
      (o.medicaments || []).some(m => m.nom.toLowerCase().includes(search))
    );
  }

  if (statut !== 'all') {
    list = list.filter(o => getStatut(o) === statut);
  }

  renderGrid(list);
}

// ===== MÉDICAMENTS DANS MODAL =====
let tempMeds = [];

function renderMedRows() {
  const container = document.getElementById('medsList');
  if (!tempMeds.length) {
    container.innerHTML = `<p style="font-size:0.8rem;color:var(--color-text-muted);font-style:italic;padding:6px 0">Cliquez sur "+ Ajouter" pour renseigner les médicaments.</p>`;
    return;
  }
  container.innerHTML = tempMeds.map((m, i) => `
    <div class="med-row">
      <input type="text" placeholder="Médicament" value="${m.nom || ''}"
        oninput="tempMeds[${i}].nom = this.value" />
      <input type="text" placeholder="Dose (5mg)" value="${m.dose || ''}"
        oninput="tempMeds[${i}].dose = this.value" />
      <input type="text" placeholder="Posologie" value="${m.posologie || ''}"
        oninput="tempMeds[${i}].posologie = this.value" />
      <button class="btn-remove-med" onclick="removeMed(${i})">✕</button>
    </div>`).join('');
}

function addMed() {
  tempMeds.push({ nom: '', dose: '', posologie: '' });
  renderMedRows();
}

function removeMed(i) {
  tempMeds.splice(i, 1);
  renderMedRows();
}

// ===== MODAL FORM =====
let editingId = null;

function openModal(id = null) {
  editingId = id;
  document.getElementById('modalTitle').textContent = id ? 'Modifier l\'ordonnance' : 'Nouvelle Ordonnance';

  if (id !== null) {
    const o = getData().find(x => x.id === id);
    if (!o) return;
    document.getElementById('inMedecin').value = o.medecin;
    document.getElementById('inDate').value = o.date;
    document.getElementById('inExpiration').value = o.expiration;
    document.getElementById('inMotif').value = o.motif || '';
    document.getElementById('inNotes').value = o.notes || '';
    tempMeds = (o.medicaments || []).map(m => ({ ...m }));
  } else {
    document.getElementById('inMedecin').value = '';
    document.getElementById('inDate').value = '';
    document.getElementById('inExpiration').value = '';
    document.getElementById('inMotif').value = '';
    document.getElementById('inNotes').value = '';
    tempMeds = [];
  }

  renderMedRows();
  document.getElementById('modalOverlay').classList.add('open');
}

function closeModal() {
  document.getElementById('modalOverlay').classList.remove('open');
  editingId = null;
}

function saveOrdo() {
  const medecin = document.getElementById('inMedecin').value.trim();
  const date = document.getElementById('inDate').value;
  const expiration = document.getElementById('inExpiration').value;
  const motif = document.getElementById('inMotif').value.trim();
  const notes = document.getElementById('inNotes').value.trim();

  if (!medecin || !date) {
    showToast('Médecin et date sont obligatoires', 'error');
    return;
  }

  // Filtrer médicaments vides
  const medicaments = tempMeds.filter(m => m.nom.trim());

  let list = getData();

  if (editingId !== null) {
    list = list.map(o => o.id === editingId
      ? { ...o, medecin, date, expiration, motif, notes, medicaments }
      : o
    );
    showToast('Ordonnance mise à jour ✅', 'success');
  } else {
    list.push({ id: getNextId(list), medecin, date, expiration, motif, notes, medicaments });
    showToast('Ordonnance ajoutée ✅', 'success');
  }

  saveData(list);
  closeModal();
  refreshAll();
}

// ===== EDIT / DELETE =====
function editOrdo(id) { openModal(id); }

function deleteOrdo(id) {
  if (!confirm('Supprimer cette ordonnance ?')) return;
  let list = getData().filter(o => o.id !== id);
  saveData(list);
  showToast('Ordonnance supprimée', 'error');
  refreshAll();
}

// ===== DETAIL MODAL =====
function viewDetail(id) {
  const o = getData().find(x => x.id === id);
  if (!o) return;
  const statut = getStatut(o);
  const meds = o.medicaments || [];

  document.getElementById('detailContent').innerHTML = `
    <div class="detail-header-card">
      <div class="detail-medecin">${o.medecin}</div>
      <div class="detail-motif">${o.motif || 'Ordonnance médicale'}</div>
    </div>
    <div class="detail-row">
      <div class="detail-item">
        <span class="detail-item-label">Date de prescription</span>
        <span class="detail-item-val">${formatDate(o.date)}</span>
      </div>
      <div class="detail-item">
        <span class="detail-item-label">Date d'expiration</span>
        <span class="detail-item-val" style="${statut === 'expiree' ? 'color:#EF4444' : ''}">${formatDate(o.expiration)}</span>
      </div>
      <div class="detail-item">
        <span class="detail-item-label">Statut</span>
        <span class="badge badge-${statut}">${statut === 'active' ? '✅ Active' : '⏰ Expirée'}</span>
      </div>
      <div class="detail-item">
        <span class="detail-item-label">Médicaments</span>
        <span class="detail-item-val">${meds.length} prescrit${meds.length > 1 ? 's' : ''}</span>
      </div>
    </div>

    <div class="detail-meds-title">💊 Médicaments prescrits</div>
    ${meds.length ? `
    <table class="detail-med-table">
      <thead>
        <tr>
          <th>Médicament</th>
          <th>Dosage</th>
          <th>Posologie</th>
        </tr>
      </thead>
      <tbody>
        ${meds.map(m => `
          <tr>
            <td><strong>${m.nom}</strong></td>
            <td>${m.dose || '—'}</td>
            <td>${m.posologie || '—'}</td>
          </tr>`).join('')}
      </tbody>
    </table>` : `<p style="font-size:0.85rem;color:var(--color-text-muted);font-style:italic">Aucun médicament renseigné.</p>`}

    ${o.notes ? `<div class="detail-notes">📝 ${o.notes}</div>` : ''}
  `;

  // Stocker l'id courant pour impression
  document.getElementById('detailPrintBtn').dataset.id = id;
  document.getElementById('detailOverlay').classList.add('open');
}

function closeDetail() { document.getElementById('detailOverlay').classList.remove('open'); }

// ===== PRINT =====
function printDetail() {
  window.print();
}

// ===== REFRESH =====
function refreshAll() {
  const list = getData();
  updateStats(list);
  applyFilters();
}

// ===== TOAST =====
function showToast(msg, type = '') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'toast show ' + type;
  setTimeout(() => { t.className = 'toast'; }, 3000);
}

// ===== EVENTS =====
document.getElementById('menuToggle')?.addEventListener('click', () => {
  document.getElementById('sidebar').classList.toggle('open');
});

document.getElementById('openModalBtn').addEventListener('click', () => openModal());
document.getElementById('modalClose').addEventListener('click', closeModal);
document.getElementById('cancelBtn').addEventListener('click', closeModal);
document.getElementById('saveBtn').addEventListener('click', saveOrdo);
document.getElementById('addMedBtn').addEventListener('click', addMed);

document.getElementById('modalOverlay').addEventListener('click', e => {
  if (e.target === document.getElementById('modalOverlay')) closeModal();
});

document.getElementById('detailClose').addEventListener('click', closeDetail);
document.getElementById('detailCloseBtn').addEventListener('click', closeDetail);
document.getElementById('detailPrintBtn').addEventListener('click', printDetail);
document.getElementById('detailOverlay').addEventListener('click', e => {
  if (e.target === document.getElementById('detailOverlay')) closeDetail();
});

document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('filterStatut').addEventListener('change', applyFilters);

// ===== INIT =====
document.addEventListener('DOMContentLoaded', refreshAll);