

/**
 * Active l'item de navigation cliqué et affiche la section correspondante.
 * @param {string} section - Identifiant de la section à afficher
 * @param {HTMLElement} el  - L'élément nav-item cliqué
 */
function Suivant(section, el) {
  // Retirer l'état actif de tous les items
  document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));

  // Activer l'item cliqué
  el.classList.add('active');

  // Masquer toutes les sections de contenu
  document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('visible'));

  // Afficher la section ciblée si elle existe dans le DOM
  const target = document.getElementById('section-' + section);
  if (target) target.classList.add('visible');

  console.log('[HealthCare] Section active :', section);
}

/**
 * Redirige vers une page (ex : page de déconnexion / landing).
 * @param {string} page - Identifiant de la page cible
 */
function AutrePage(page) {
  console.log('[HealthCare] Redirection vers :', page);

  // Exemple : redirection réelle
  // window.location.href = page + '.php';
}

/**
 * Initialisation au chargement de la page.
 * Marque automatiquement l'item actif selon l'URL courante.
 */
document.addEventListener('DOMContentLoaded', () => {
  const currentPage = window.location.pathname.split('/').pop(); // ex: "accueil.php"

  document.querySelectorAll('.nav-item[data-page]').forEach(item => {
    if (item.dataset.page === currentPage) {
      item.classList.add('active');
    }
  });
});