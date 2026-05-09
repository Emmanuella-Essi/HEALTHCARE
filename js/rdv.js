const RDV_STORAGE_KEY = 'healthcare_rdv';

const RDV_DEMO = [
  {
    id: 1,
    medecin: 'Dr. Yao Ama',
    specialite: 'Medecin generaliste',
    date: '2026-05-15',
    heure: '09:30',
    lieu: 'Clinique Saint-Paul, Abidjan',
    statut: 'confirme',
    notes: 'Controle general et bilan de routine.'
  },
  {
    id: 2,
    medecin: 'Dr. Kouassi Brou',
    specialite: 'Cardiologue',
    date: '2026-05-22',
    heure: '14:00',
    lieu: 'Cabinet medical Cocody',
    statut: 'en_attente',
    notes: 'Apporter les anciens examens.'
  }
];

function getRdvList() {
  const saved = localStorage.getItem(RDV_STORAGE_KEY);
  if (!saved) {
    localStorage.setItem(RDV_STORAGE_KEY, JSON.stringify(RDV_DEMO));
    return [...RDV_DEMO];
  }
  try {
    return JSON.parse(saved) || [];
  } catch {
    return [];
  }
}

function saveRdvList(list) {
  localStorage.setItem(RDV_STORAGE_KEY, JSON.stringify(list));
}

function nextRdvId(list) {
  return list.length ? Math.max(...list.map(r => Number(r.id) || 0)) + 1 : 1;
}

function formatRdvDate(date, heure = '') {
  if (!date) return 'Date non renseignee';
  const d = new Date(date + 'T00:00:00');
  const formatted = d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });
  return heure ? `${formatted} a ${heure}` : formatted;
}

function statutLabel(statut) {
  return {
    confirme: 'Confirme',
    en_attente: 'En attente',
    annule: 'Annule',
    termine: 'Termine'
  }[statut] || statut;
}

function filterRdvList() {
  let list = getRdvList();
  const search = document.getElementById('searchInput')?.value.trim().toLowerCase() || '';
  const status = document.getElementById('filterStatus')?.value || 'all';
  const month = document.getElementById('filterMonth')?.value || 'all';

  if (search) {
    list = list.filter(r =>
      r.medecin.toLowerCase().includes(search) ||
      r.specialite.toLowerCase().includes(search) ||
      r.lieu.toLowerCase().includes(search)
    );
  }

  if (status !== 'all') {
    list = list.filter(r => r.statut === status);
  }

  if (month !== 'all') {
    list = list.filter(r => new Date(r.date + 'T00:00:00').getMonth() === Number(month));
  }

  return list;
}

function updateRdvStats(list) {
  document.getElementById('totalRdv').textContent = list.length;
  document.getElementById('confirmedRdv').textContent = list.filter(r => r.statut === 'confirme').length;
  document.getElementById('pendingRdv').textContent = list.filter(r => r.statut === 'en_attente').length;
  document.getElementById('canceledRdv').textContent = list.filter(r => r.statut === 'annule').length;
}

function renderNextRdv(list) {
  const target = document.getElementById('nextRdvContent');
  const upcoming = [...list]
    .filter(r => ['confirme', 'en_attente'].includes(r.statut) && r.date)
    .sort((a, b) => new Date(`${a.date}T${a.heure || '00:00'}`) - new Date(`${b.date}T${b.heure || '00:00'}`))[0];

  if (!upcoming) {
    target.innerHTML = '<p class="no-next">Aucun rendez-vous a venir</p>';
    return;
  }

  target.innerHTML = `
    <div>
      <strong>${escapeHtml(upcoming.medecin)}</strong>
      <p>${escapeHtml(upcoming.specialite)} - ${escapeHtml(upcoming.lieu)}</p>
    </div>
    <div>
      <strong>${formatRdvDate(upcoming.date, upcoming.heure)}</strong>
      <p>${statutLabel(upcoming.statut)}</p>
    </div>
  `;
}

function renderRdvList() {
  const list = filterRdvList();
  const target = document.getElementById('rdvList');
  updateRdvStats(getRdvList());
  renderNextRdv(getRdvList());

  if (!list.length) {
    target.innerHTML = '<div class="empty-state">Aucun rendez-vous trouve.</div>';
    return;
  }

  target.innerHTML = list.map(r => `
    <article class="rdv-card">
      <div class="rdv-date-block">
        <span class="rdv-date-day">${new Date(r.date + 'T00:00:00').getDate()}</span>
        <span class="rdv-date-month">${new Date(r.date + 'T00:00:00').toLocaleDateString('fr-FR', { month: 'short' })}</span>
      </div>
      <div class="rdv-info">
        <div class="rdv-doc-name">${escapeHtml(r.medecin)}</div>
        <div class="rdv-specialite">${escapeHtml(r.specialite)}</div>
        <div class="rdv-details">
          <span>${escapeHtml(r.heure)}</span>
          <span>${escapeHtml(r.lieu)}</span>
        </div>
        ${r.notes ? `<p class="rdv-notes">${escapeHtml(r.notes)}</p>` : ''}
      </div>
      <span class="badge badge-${r.statut}">${statutLabel(r.statut)}</span>
      <div class="rdv-actions">
        <button class="btn-icon" type="button" title="Modifier" onclick="openRdvModal(${r.id})"><i class="fa-solid fa-pen"></i></button>
        <button class="btn-icon delete" type="button" title="Supprimer" onclick="deleteRdv(${r.id})"><i class="fa-solid fa-trash"></i></button>
      </div>
    </article>
  `).join('');
}

function openRdvModal(id = null) {
  const list = getRdvList();
  const rdv = id ? list.find(r => r.id === id) : null;

  document.getElementById('rdvId').value = rdv?.id || '';
  document.getElementById('inputMedecin').value = rdv?.medecin || '';
  document.getElementById('inputSpecialite').value = rdv?.specialite || '';
  document.getElementById('inputDate').value = rdv?.date || '';
  document.getElementById('inputHeure').value = rdv?.heure || '';
  document.getElementById('inputLieu').value = rdv?.lieu || '';
  document.getElementById('inputStatut').value = rdv?.statut || 'en_attente';
  document.getElementById('inputNotes').value = rdv?.notes || '';
  document.getElementById('modalTitle').textContent = rdv ? 'Modifier le rendez-vous' : 'Nouveau Rendez-vous';
  document.getElementById('modalOverlay').classList.add('open');
}

function closeRdvModal() {
  document.getElementById('modalOverlay').classList.remove('open');
}

function saveRdv() {
  const id = document.getElementById('rdvId').value;
  const rdv = {
    id: id ? Number(id) : null,
    medecin: document.getElementById('inputMedecin').value.trim(),
    specialite: document.getElementById('inputSpecialite').value,
    date: document.getElementById('inputDate').value,
    heure: document.getElementById('inputHeure').value,
    lieu: document.getElementById('inputLieu').value.trim(),
    statut: document.getElementById('inputStatut').value,
    notes: document.getElementById('inputNotes').value.trim()
  };

  if (!rdv.medecin || !rdv.specialite || !rdv.date || !rdv.heure) {
    showRdvToast('Medecin, specialite, date et heure sont obligatoires.', 'error');
    return;
  }

  let list = getRdvList();
  if (rdv.id) {
    list = list.map(item => item.id === rdv.id ? rdv : item);
    showRdvToast('Rendez-vous modifie.', 'success');
  } else {
    rdv.id = nextRdvId(list);
    list.push(rdv);
    showRdvToast('Rendez-vous ajoute.', 'success');
  }

  saveRdvList(list);
  closeRdvModal();
  renderRdvList();
}

function deleteRdv(id) {
  if (!confirm('Supprimer ce rendez-vous ?')) return;
  saveRdvList(getRdvList().filter(r => r.id !== id));
  renderRdvList();
  showRdvToast('Rendez-vous supprime.', 'success');
}

function showRdvToast(message, type = '') {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.className = `toast show ${type}`;
  setTimeout(() => {
    toast.className = 'toast';
  }, 2500);
}

function escapeHtml(value) {
  return String(value || '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('menuToggle')?.addEventListener('click', () => {
    document.getElementById('sidebar')?.classList.toggle('open');
  });
  document.getElementById('openModalBtn')?.addEventListener('click', () => openRdvModal());
  document.getElementById('modalClose')?.addEventListener('click', closeRdvModal);
  document.getElementById('cancelBtn')?.addEventListener('click', closeRdvModal);
  document.getElementById('saveBtn')?.addEventListener('click', saveRdv);
  document.getElementById('modalOverlay')?.addEventListener('click', e => {
    if (e.target === e.currentTarget) closeRdvModal();
  });
  document.getElementById('searchInput')?.addEventListener('input', renderRdvList);
  document.getElementById('filterStatus')?.addEventListener('change', renderRdvList);
  document.getElementById('filterMonth')?.addEventListener('change', renderRdvList);
  renderRdvList();
});
