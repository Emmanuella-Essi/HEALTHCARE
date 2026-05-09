/* ═══════════════════════════════════════════════════
   ALERTES.JS — Page Alertes & Notifications
   MediCarePro Dashboard
   Filtrage, lecture, suppression, actions
═══════════════════════════════════════════════════ */

/* ══════════════════════════════
   STATE
══════════════════════════════ */
let currentFilter = 'all';

/* ══════════════════════════════
   FILTER ALERTS
══════════════════════════════ */
function filterAlerts(filter, tabBtn) {
  currentFilter = filter;

  // Update active tab
  document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
  if (tabBtn) tabBtn.classList.add('active');

  const cards = document.querySelectorAll('.alert-card');
  let visibleCount = 0;

  cards.forEach(card => {
    const isUrgente   = card.classList.contains('urgente');
    const isMessage   = card.classList.contains('message');
    const isVaccin    = card.classList.contains('vaccin');
    const isRisque    = card.classList.contains('risque');
    const isNonLue    = card.classList.contains('non-lue');

    let show = false;

    switch (filter) {
      case 'all':      show = true; break;
      case 'non-lue':  show = isNonLue; break;
      case 'urgente':  show = isUrgente; break;
      case 'message':  show = isMessage; break;
      case 'vaccin':   show = isVaccin; break;
      case 'risque':   show = isRisque; break;
      default:         show = true;
    }

    if (show) {
      card.style.display = 'flex';
      card.style.animation = 'none';
      requestAnimationFrame(() => {
        card.style.animation = 'cardIn 0.3s ease both';
      });
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });

  // Toggle empty state
  const emptyState = document.getElementById('emptyState');
  if (emptyState) {
    emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
  }
}

/* ══════════════════════════════
   MARK AS READ
══════════════════════════════ */
function markRead(alertId, btnEl) {
  const card = document.querySelector(`.alert-card[data-id="${alertId}"]`);
  if (!card) return;

  // Remove unread styling
  card.classList.remove('non-lue');
  card.classList.add('lue');

  // Remove unread dot
  const dot = card.querySelector('.unread-dot');
  if (dot) dot.remove();

  // Remove the check button itself
  if (btnEl) btnEl.remove();

  // Update counts
  updateCounts();

  showToast('Alerte marquée comme lue', 'success');
}

/* ══════════════════════════════
   MARK ALL READ
══════════════════════════════ */
function markAllRead() {
  const unreadCards = document.querySelectorAll('.alert-card.non-lue');
  if (unreadCards.length === 0) {
    showToast('Toutes les alertes sont déjà lues', 'default');
    return;
  }

  unreadCards.forEach((card, index) => {
    setTimeout(() => {
      card.classList.remove('non-lue');
      card.classList.add('lue');
      const dot = card.querySelector('.unread-dot');
      if (dot) dot.remove();
      const checkBtn = card.querySelector('.btn-alert-action.success');
      if (checkBtn) checkBtn.remove();

      // Add "lue" tag if not present
      const tagsWrap = card.querySelector('.alert-tags');
      if (tagsWrap && !tagsWrap.querySelector('.lue-tag')) {
        const tag = document.createElement('span');
        tag.className = 'alert-tag lue-tag';
        tag.textContent = '✓ Lue';
        tagsWrap.appendChild(tag);
      }
    }, index * 80);
  });

  setTimeout(() => {
    updateCounts();
    showToast(`${unreadCards.length} alertes marquées comme lues`, 'success');

    // Re-apply current filter
    const activeTab = document.querySelector('.filter-tab.active');
    filterAlerts(currentFilter, activeTab);
  }, unreadCards.length * 80 + 100);
}

/* ══════════════════════════════
   DELETE ALERT
══════════════════════════════ */
function deleteAlert(alertId, btnEl) {
  const card = document.querySelector(`.alert-card[data-id="${alertId}"]`);
  if (!card) return;

  // Animate out
  card.style.transition = 'all 0.3s ease';
  card.style.opacity = '0';
  card.style.transform = 'translateX(30px)';
  card.style.maxHeight = card.offsetHeight + 'px';

  setTimeout(() => {
    card.style.maxHeight = '0';
    card.style.marginBottom = '0';
    card.style.padding = '0';
    card.style.border = 'none';
  }, 280);

  setTimeout(() => {
    card.remove();
    updateCounts();
    showToast('Alerte supprimée', 'default');

    // Check if container is empty
    const visibleCards = document.querySelectorAll('.alert-card');
    if (visibleCards.length === 0) {
      const emptyState = document.getElementById('emptyState');
      if (emptyState) emptyState.style.display = 'block';
    }
  }, 500);
}

/* ══════════════════════════════
   DELETE ALL READ
══════════════════════════════ */
function deleteAllRead() {
  const readCards = document.querySelectorAll('.alert-card.lue');
  if (readCards.length === 0) {
    showToast('Aucune alerte lue à supprimer', 'default');
    return;
  }

  const count = readCards.length;
  readCards.forEach((card, index) => {
    setTimeout(() => {
      card.style.transition = 'all 0.25s ease';
      card.style.opacity = '0';
      card.style.transform = 'scale(0.95)';
      setTimeout(() => card.remove(), 250);
    }, index * 60);
  });

  setTimeout(() => {
    updateCounts();
    showToast(`${count} alerte(s) supprimée(s)`, 'success');
  }, count * 60 + 300);
}

/* ══════════════════════════════
   UPDATE BADGE COUNTS
══════════════════════════════ */
function updateCounts() {
  const cards = document.querySelectorAll('.alert-card');
  const all     = cards.length;
  const unread  = document.querySelectorAll('.alert-card.non-lue').length;
  const urgente = document.querySelectorAll('.alert-card.urgente').length;
  const vaccin  = document.querySelectorAll('.alert-card.vaccin').length;
  const message = document.querySelectorAll('.alert-card.message').length;
  const risque  = document.querySelectorAll('.alert-card.risque').length;

  // Hero stats
  const countUrgent  = document.getElementById('countUrgent');
  const countVaccin  = document.getElementById('countVaccin');
  const countMessage = document.getElementById('countMessage');
  const countRisque  = document.getElementById('countRisque');

  if (countUrgent)  animateCount(countUrgent,  parseInt(countUrgent.textContent),  document.querySelectorAll('.alert-card.urgente.non-lue').length);
  if (countVaccin)  animateCount(countVaccin,  parseInt(countVaccin.textContent),  document.querySelectorAll('.alert-card.vaccin.non-lue').length);
  if (countMessage) animateCount(countMessage, parseInt(countMessage.textContent), document.querySelectorAll('.alert-card.message.non-lue').length);
  if (countRisque)  animateCount(countRisque,  parseInt(countRisque.textContent),  document.querySelectorAll('.alert-card.risque.non-lue').length);

  // Tab counts
  const setTab = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  setTab('tabAll',    all);
  setTab('tabUnread', unread);
  setTab('tabUrgent', urgente);
  setTab('tabVaccin', vaccin);
  setTab('tabMessage',message);
  setTab('tabRisque', risque);

  // Sidebar badge
  const navBadge = document.querySelector('.nav-item.active .nav-badge');
  if (navBadge) navBadge.textContent = unread;

  // Topbar dot
  const notifDot = document.querySelector('.notif-dot');
  if (notifDot) notifDot.style.display = unread > 0 ? 'block' : 'none';
}

/* ══════════════════════════════
   ANIMATE COUNT NUMBER
══════════════════════════════ */
function animateCount(el, from, to) {
  if (from === to) return;
  const duration = 400;
  const start = performance.now();
  const animate = (now) => {
    const progress = Math.min((now - start) / duration, 1);
    const ease = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.round(from + (to - from) * ease);
    if (progress < 1) requestAnimationFrame(animate);
  };
  requestAnimationFrame(animate);
}

/* ══════════════════════════════
   ACTION HANDLERS
══════════════════════════════ */
function viewPatient(alertId) {
  // In production: redirect to patient-fiche.html?id=X
  const card = document.querySelector(`.alert-card[data-id="${alertId}"]`);
  const patientName = card?.querySelector('.alert-patient-chip span')?.textContent || 'Patient';
  const title = card?.querySelector('.alert-title')?.textContent || '';

  openModal(`
    <div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
        <div style="width:42px;height:42px;border-radius:50%;background:var(--color-urgente-light);display:flex;align-items:center;justify-content:center;color:var(--color-urgente);font-size:18px;">
          <i class="fas fa-user-md"></i>
        </div>
        <div>
          <div class="modal-alert-title">${patientName}</div>
          <div class="modal-alert-sub">${title}</div>
        </div>
      </div>
      <div class="modal-info-grid">
        <div class="modal-info-item"><label>Action recommandée</label><span>Consultation immédiate</span></div>
        <div class="modal-info-item"><label>Priorité</label><span style="color:var(--color-urgente);font-weight:600;">Haute</span></div>
        <div class="modal-info-item"><label>Dernière consultation</label><span>28 avril 2026</span></div>
        <div class="modal-info-item"><label>Médecin traitant</label><span>Dr. Martin</span></div>
      </div>
      <div class="modal-actions">
        <button class="modal-btn secondary" onclick="closeModal()">Fermer</button>
        <button class="modal-btn primary" onclick="closeModal();showToast('Redirection vers la fiche patient...','success')">
          <i class="fas fa-arrow-right"></i> Ouvrir fiche complète
        </button>
      </div>
    </div>
  `);
}

function openMessage(alertId) {
  const card = document.querySelector(`.alert-card[data-id="${alertId}"]`);
  const patientName = card?.querySelector('.alert-patient-chip span')?.textContent || 'Patient';

  openModal(`
    <div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
        <div style="width:42px;height:42px;border-radius:50%;background:var(--color-message-light);display:flex;align-items:center;justify-content:center;color:var(--color-message);font-size:18px;">
          <i class="fas fa-comment-medical"></i>
        </div>
        <div>
          <div class="modal-alert-title">Répondre à ${patientName}</div>
          <div class="modal-alert-sub">Message de téléconsultation</div>
        </div>
      </div>
      <textarea
        id="replyText"
        placeholder="Rédigez votre réponse médicale..."
        style="width:100%;min-height:120px;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:12px 14px;font-family:'DM Sans',sans-serif;font-size:14px;resize:vertical;color:var(--color-text-dark);outline:none;margin-bottom:16px;"
        onfocus="this.style.borderColor='var(--color-accent)'"
        onblur="this.style.borderColor='var(--color-border)'"
      ></textarea>
      <div class="modal-actions">
        <button class="modal-btn secondary" onclick="closeModal()">Annuler</button>
        <button class="modal-btn primary" onclick="sendReply(${alertId})">
          <i class="fas fa-paper-plane"></i> Envoyer
        </button>
      </div>
    </div>
  `);
}

function sendReply(alertId) {
  const text = document.getElementById('replyText')?.value?.trim();
  if (!text) {
    showToast('Veuillez écrire un message avant d\'envoyer', 'warning');
    return;
  }
  closeModal();
  markRead(alertId, document.querySelector(`.alert-card[data-id="${alertId}"] .btn-alert-action.success`));
  showToast('Réponse envoyée au patient ✓', 'success');
}

function scheduleVaccin(alertId) {
  const card = document.querySelector(`.alert-card[data-id="${alertId}"]`);
  const patientName = card?.querySelector('.alert-patient-chip span')?.textContent || 'Patient';

  openModal(`
    <div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
        <div style="width:42px;height:42px;border-radius:50%;background:var(--color-vaccin-light);display:flex;align-items:center;justify-content:center;color:var(--color-vaccin);font-size:18px;">
          <i class="fas fa-syringe"></i>
        </div>
        <div>
          <div class="modal-alert-title">Programmer un vaccin</div>
          <div class="modal-alert-sub">Patient : ${patientName}</div>
        </div>
      </div>
      <div style="display:grid;gap:12px;margin-bottom:20px;">
        <div>
          <label style="font-size:12px;text-transform:uppercase;letter-spacing:0.05em;color:var(--color-text-light);display:block;margin-bottom:6px;">Date souhaitée</label>
          <input type="date" style="width:100%;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:10px 14px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--color-text-dark);outline:none;" />
        </div>
        <div>
          <label style="font-size:12px;text-transform:uppercase;letter-spacing:0.05em;color:var(--color-text-light);display:block;margin-bottom:6px;">Heure</label>
          <input type="time" value="09:00" style="width:100%;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:10px 14px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--color-text-dark);outline:none;" />
        </div>
      </div>
      <div class="modal-actions">
        <button class="modal-btn secondary" onclick="closeModal()">Annuler</button>
        <button class="modal-btn primary" onclick="closeModal();markRead(${alertId},null);showToast('Rendez-vous vaccin programmé ✓','success')">
          <i class="fas fa-calendar-check"></i> Confirmer
        </button>
      </div>
    </div>
  `);
}

function createOrdonnance(alertId) {
  const card = document.querySelector(`.alert-card[data-id="${alertId}"]`);
  const patientName = card?.querySelector('.alert-patient-chip span')?.textContent || 'Patient';

  openModal(`
    <div>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
        <div style="width:42px;height:42px;border-radius:50%;background:#EEF2FF;display:flex;align-items:center;justify-content:center;color:#4F46E5;font-size:18px;">
          <i class="fas fa-file-prescription"></i>
        </div>
        <div>
          <div class="modal-alert-title">Nouvelle ordonnance</div>
          <div class="modal-alert-sub">Patient : ${patientName}</div>
        </div>
      </div>
      <div style="display:grid;gap:12px;margin-bottom:20px;">
        <div>
          <label style="font-size:12px;text-transform:uppercase;letter-spacing:0.05em;color:var(--color-text-light);display:block;margin-bottom:6px;">Médicament</label>
          <input type="text" placeholder="ex: Bisoprolol 5mg" style="width:100%;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:10px 14px;font-size:14px;font-family:'DM Sans',sans-serif;outline:none;" />
        </div>
        <div>
          <label style="font-size:12px;text-transform:uppercase;letter-spacing:0.05em;color:var(--color-text-light);display:block;margin-bottom:6px;">Posologie</label>
          <input type="text" placeholder="ex: 1 comprimé matin et soir" style="width:100%;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:10px 14px;font-size:14px;font-family:'DM Sans',sans-serif;outline:none;" />
        </div>
        <div>
          <label style="font-size:12px;text-transform:uppercase;letter-spacing:0.05em;color:var(--color-text-light);display:block;margin-bottom:6px;">Durée</label>
          <input type="text" placeholder="ex: 30 jours" style="width:100%;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:10px 14px;font-size:14px;font-family:'DM Sans',sans-serif;outline:none;" />
        </div>
      </div>
      <div class="modal-actions">
        <button class="modal-btn secondary" onclick="closeModal()">Annuler</button>
        <button class="modal-btn primary" onclick="closeModal();markRead(${alertId},null);showToast('Ordonnance créée et envoyée ✓','success')">
          <i class="fas fa-paper-plane"></i> Créer & Envoyer
        </button>
      </div>
    </div>
  `);
}

/* ══════════════════════════════
   HERO STAT CLICK FILTER
══════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.hero-stat').forEach(stat => {
    stat.addEventListener('click', () => {
      const filter = stat.dataset.filter;
      const matchingTab = document.querySelector(`.filter-tab[data-filter="${filter}"]`);
      filterAlerts(filter, matchingTab);

      // Scroll to filter section smoothly
      document.querySelector('.alerts-container')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Staggered card entrance animation
  document.querySelectorAll('.alert-card').forEach((card, index) => {
    card.style.animationDelay = `${index * 60}ms`;
  });

  // Initial count update
  updateCounts();
});