/* ═══════════════════════════════════════════════════
   GLOBAL.JS — MediCarePro Dashboard
   Sidebar toggle, Toast, Session check
═══════════════════════════════════════════════════ */

/* ══════════════════════════════
   SIDEBAR TOGGLE
══════════════════════════════ */
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const main = document.querySelector('.main-content');
  const topbar = document.querySelector('.topbar');

  if (window.innerWidth <= 768) {
    sidebar.classList.toggle('open');
  } else {
    const isCollapsed = sidebar.classList.toggle('collapsed');
    if (isCollapsed) {
      sidebar.style.width = 'var(--sidebar-collapsed)';
      if (main) main.style.marginLeft = 'var(--sidebar-collapsed)';
      if (topbar) topbar.style.left = 'var(--sidebar-collapsed)';
    } else {
      sidebar.style.width = 'var(--sidebar-width)';
      if (main) main.style.marginLeft = 'var(--sidebar-width)';
      if (topbar) topbar.style.left = 'var(--sidebar-width)';
    }
  }
}

/* ══════════════════════════════
   TOAST NOTIFICATIONS
══════════════════════════════ */
function showToast(message, type = 'default', duration = 3000) {
  const toast = document.getElementById('toast');
  if (!toast) return;

  toast.textContent = message;
  toast.className = 'toast show';
  if (type !== 'default') toast.classList.add(type);

  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => { toast.className = 'toast'; }, 300);
  }, duration);
}

/* ══════════════════════════════
   MODAL
══════════════════════════════ */
function openModal(contentHTML) {
  const overlay = document.getElementById('modalOverlay');
  const content = document.getElementById('modalContent');
  if (!overlay || !content) return;
  content.innerHTML = contentHTML;
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  const overlay = document.getElementById('modalOverlay');
  if (!overlay) return;
  overlay.classList.remove('open');
  document.body.style.overflow = '';
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeModal();
});

/* ══════════════════════════════
   SESSION CHECK (simulated)
══════════════════════════════ */
function checkSession() {
  const loggedIn = sessionStorage.getItem('medecin_logged') || 'true'; // demo
  if (loggedIn !== 'true') {
    window.location.href = 'login.php';
  }
}

/* ══════════════════════════════
   DATE HELPER
══════════════════════════════ */
function formatDate(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });
}

function timeAgo(dateStr) {
  const now = new Date();
  const past = new Date(dateStr);
  const diff = Math.floor((now - past) / 1000);
  if (diff < 60) return `Il y a ${diff}s`;
  if (diff < 3600) return `Il y a ${Math.floor(diff/60)}min`;
  if (diff < 86400) return `Il y a ${Math.floor(diff/3600)}h`;
  return `Il y a ${Math.floor(diff/86400)}j`;
}

/* ══════════════════════════════
   INIT
══════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  checkSession();

  // Close sidebar on outside click (mobile)
  document.addEventListener('click', (e) => {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
      if (!sidebar.contains(e.target) && !e.target.closest('.sidebar-toggle')) {
        sidebar.classList.remove('open');
      }
    }
  });
});