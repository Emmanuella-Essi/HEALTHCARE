(function () {
  const pages = [
    {
      file: "accueil.php",
      label: "Vue d'ensemble",
      section: "Supervision",
      icon: "fa-chart-pie"
    },
    {
      file: "utilisateur.php",
      label: "Utilisateurs",
      section: "Supervision",
      icon: "fa-users"
    },
    {
      file: "medecin.php",
      label: "Medecins",
      section: "Supervision",
      icon: "fa-user-doctor"
    },
    {
      file: "consultation.php",
      label: "Consultations",
      section: "Systeme",
      icon: "fa-video"
    },
    {
      file: "vaccin.php",
      label: "Suivi vaccinal",
      section: "Systeme",
      icon: "fa-syringe"
    },
    {
      file: "rapport.php",
      label: "Rapports",
      section: "Systeme",
      icon: "fa-chart-line"
    },
    {
      file: "securite.php",
      label: "Securite & Logs",
      section: "Systeme",
      icon: "fa-shield-halved"
    }
  ];

  function currentFile() {
    const parts = window.location.pathname.split("/").filter(Boolean);
    return parts[parts.length - 1] || "accueil.php";
  }

  function renderSection(name, activeFile) {
    return `
      <div class="nav-section">
        <div class="nav-section-title">${name}</div>
        ${pages
          .filter((page) => page.section === name)
          .map((page) => `
            <a href="${page.file}" class="nav-item${page.file === activeFile ? " active" : ""}">
              <span class="nav-icon"><i class="fa-solid ${page.icon}"></i></span>
              <span>${page.label}</span>
            </a>
          `)
          .join("")}
      </div>`;
  }

  function bindSidebar() {
    const sidebar = document.querySelector(".sidebar");
    if (!sidebar) return;

    const activeFile = currentFile();
    sidebar.innerHTML = `
      <div class="sidebar-logo">
        <div class="logo">Health<span>Care</span></div>
        <div class="sidebar-role">Administration</div>
      </div>

      <nav class="sidebar-nav">
        ${renderSection("Supervision", activeFile)}
        ${renderSection("Systeme", activeFile)}
      </nav>

      <div class="sidebar-footer">
        <a class="admin-logout" href="accueil.php?logout=1">
          <i class="fa-solid fa-arrow-left-from-bracket"></i>
          <span>Deconnexion</span>
        </a>
      </div>

      <div class="sidebar-user">
        <div class="avatar avatar-purple">AD</div>
        <div class="sidebar-user-info">
          <div class="name">Admin Systeme</div>
          <div class="role">Super Administrateur</div>
        </div>
      </div>`;
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", bindSidebar);
  } else {
    bindSidebar();
  }
})();
