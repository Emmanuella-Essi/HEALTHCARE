q(function () {
  const pages = [
    {
      key: "dashboard-medecin",
      href: "../dashboard-medecin/dashboard-medecin.html",
      label: "Tableau de Bord",
      title: "Tableau de bord",
      icon: '<rect x="3" y="3" width="7" height="7" rx="1" /><rect x="14" y="3" width="7" height="7" rx="1" /><rect x="14" y="14" width="7" height="7" rx="1" /><rect x="3" y="14" width="7" height="7" rx="1" />'
    },
    {
      key: "patient",
      href: "../patient/patient.html",
      label: "Patients",
      title: "Mes patients",
      badge: "48",
      icon: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />'
    },
    {
      key: "consultations",
      href: "../consultations/consultations.html",
      label: "Consultations",
      title: "Consultations",
      badge: "3",
      urgent: true,
      icon: '<rect x="3" y="4" width="18" height="18" rx="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />'
    },
    {
      key: "alertes",
      href: "../alertes/alertes.html",
      label: "Alertes",
      title: "Alertes",
      badge: "5",
      urgent: true,
      icon: '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" /><path d="M13.73 21a2 2 0 0 1-3.46 0" />'
    },
    {
      key: "agenda",
      href: "../agenda/agenda.html",
      label: "Agenda",
      title: "Agenda",
      icon: '<circle cx="12" cy="12" r="10" /><polyline points="12,6 12,12 16,14" />'
    },
    {
      key: "vaccins",
      href: "../vaccins/vaccins.html",
      label: "Vaccins",
      title: "Vaccins",
      icon: '<path d="M20 7l-8-4-8 4m16 0v10l-8 4m0 0L4 17V7m8 10V7" />'
    },
    {
      key: "messagerie",
      href: "../messagerie/messagerie.html",
      label: "Messagerie",
      title: "Messages",
      badge: "7",
      urgent: true,
      icon: '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />'
    },
    {
      key: "ordonnances",
      href: "../ordonnances/ordonnances.html",
      label: "Ordonnances",
      title: "Ordonnances",
      icon: '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14,2 14,8 20,8" /><line x1="16" y1="13" x2="8" y2="13" /><line x1="16" y1="17" x2="8" y2="17" />'
    },
    {
      key: "analyses",
      href: "../analyses/analyses.html",
      label: "Analyses",
      title: "Analyses",
      icon: '<line x1="18" y1="20" x2="18" y2="10" /><line x1="12" y1="20" x2="12" y2="4" /><line x1="6" y1="20" x2="6" y2="14" />'
    }
  ];

  function currentSection() {
    const parts = window.location.pathname.replace(/\\/g, "/").split("/").filter(Boolean);
    const file = parts[parts.length - 1] || "";
    const folder = parts[parts.length - 2] || "";

    if (file === "patient-fiche.html") return "patient";
    return folder || file.replace(".html", "");
  }

  function svg(content) {
    return `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">${content}</svg>`;
  }

  function item(page, activeKey) {
    const active = page.key === activeKey ? " active" : "";
    const badge = page.badge
      ? `<span class="nav-badge${page.urgent ? " urgent" : ""}">${page.badge}</span>`
      : "";

    return `
      <a href="${page.href}" class="nav-item${active}" title="${page.title}">
        <span class="nav-icon">${svg(page.icon)}</span>
        <span class="nav-label">${page.label}</span>
        ${badge}
      </a>`;
  }

  function sidebarHtml(activeKey) {
    return `
      <div class="sidebar-logo">
        <div class="logo-icon">${svg('<path d="M12 2v20M2 12h20" />')}</div>
<span class="logo-text">HealthCare</span>
HealthCare</span>
        <span class="logo-badge">Pro</span>
      </div>

      <div class="sidebar-profile">
        <div class="doctor-avatar">
          <span>DK</span>
          <span class="status-dot"></span>
        </div>
        <div class="doctor-info">
          <span class="doctor-name">Dr. Kouame</span>
          <span class="doctor-spec">Medecin Generaliste</span>
        </div>
      </div>

      <nav class="sidebar-nav">
        ${pages.map((page) => item(page, activeKey)).join("")}
        <div class="nav-separator"></div>
        <a href="../profil/profil.html" class="nav-item${activeKey === "profil" ? " active" : ""}" title="Mon profil">
          <span class="nav-icon">${svg('<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />')}</span>
          <span class="nav-label">Mon Profil</span>
        </a>
      </nav>

      <div class="sidebar-logout">
        <a href="../login.php" class="logout-link">
          ${svg('<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><polyline points="16,17 21,12 16,7" /><line x1="21" y1="12" x2="9" y2="12" />')}
          <span>Deconnexion</span>
        </a>
      </div>`;
  }

  function bindSidebar() {
    const sidebar = document.querySelector(".sidebar");
    if (!sidebar) return;

    sidebar.id = sidebar.id || "sidebar";
    sidebar.innerHTML = sidebarHtml(currentSection());
    sidebar.dataset.sharedSidebar = "true";

    let overlay = document.getElementById("sidebarOverlay");
    if (!overlay) {
      overlay = document.createElement("div");
      overlay.className = "sidebar-overlay";
      overlay.id = "sidebarOverlay";
      document.body.appendChild(overlay);
    }

    const openButtons = ["menuToggle", "sidebarToggle", "hamburger"]
      .map((id) => document.getElementById(id))
      .filter(Boolean);

    const close = () => {
      sidebar.classList.remove("open");
      overlay.classList.remove("show");
    };

    openButtons.forEach((button) => {
      button.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        overlay.classList.toggle("show");
      });
    });

    overlay.addEventListener("click", close);
    sidebar.querySelectorAll(".nav-item, .logout-link").forEach((link) => {
      link.addEventListener("click", close);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", bindSidebar);
  } else {
    bindSidebar();
  }
})();
