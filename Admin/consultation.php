<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_consultation.css">
    <link rel="stylesheet" href="../css/admin_shared.css">
</head>
<body>

<div class="page" id="admin-consultations">
    <div class="app-layout">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <div class="logo">Health<span>Care</span></div>
                <div class="sidebar-role">Administration</div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Supervision</div>
                    <div class="nav-item" onclick="window.location.href='accueil.php'">
                        <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Vue d'ensemble
                    </div>
                    <div class="nav-item" onclick="window.location.href='utilisateur.php'">
                        <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Utilisateurs
                    </div>
                    <div class="nav-item" onclick="window.location.href='medecin.php'">
                        <span class="nav-icon"><i class="fa-solid fa-user-doctor"></i></span> Médecins
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Système</div>
                    <div class="nav-item active" onclick="window.location.href='consultation.php'">
                        <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Consultations
                    </div>

                    <div class="nav-item" onclick="window.location.href='rapport.php'">
                        <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Rapports
                    </div>
                    <div class="nav-item" onclick="window.location.href='securite.php'">
                        <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span> Sécurité &amp; Logs
                    </div>
                </div>
            </nav>

            <div class="sidebar-footer" onclick="window.location.href='accueil.php?logout=1'">
                    <i class="fa-solid fa-arrow-left-from-bracket"></i> Déconnexion
                </div>
                <div class="sidebar-user">
                    <div class="avatar avatar-purple">AD</div>
                    <div class="sidebar-user-info">
                        <div class="name">Admin Système</div>
                        <div class="role">Super Administrateur</div>
                    </div>
                </div>
            </div>
        <!-- /sidebar -->

        <!-- MAIN CONTENT -->
        <div class="main-content">

            <!-- CONSULTATIONS ADMIN -->
            <div id="a-consultations-admin" class="admin-section">
                <div class="top-bar">
                    <h2>🎥 Suivi des Consultations</h2>
                    <div class="top-bar-actions" style="display:none"></div>
                </div>

                <div class="content-area">
                    <div class="admin-welcome">
                        <div>
                            <span class="welcome-kicker">Supervision médicale</span>
                            <h1>Consultations et téléconsultations</h1>
                            <p>Suivez les sessions en cours, les consultations terminées, les médecins connectés et les incidents éventuels pendant les rendez-vous.</p>
                        </div>
                        <div class="welcome-actions">
                            <a href="rapport.php" class="welcome-btn primary"><i class="fa-solid fa-chart-line"></i> Rapports</a>
                            <a href="securite.php" class="welcome-btn"><i class="fa-solid fa-shield-halved"></i> Sécurité</a>
                        </div>
                    </div>

                    <div class="admin-context-grid">
                        <div class="context-card">
                            <i class="fa-solid fa-video"></i>
                            <strong>Temps réel</strong>
                            <span>Contrôler les sessions en cours, leur durée, le médecin responsable et le statut de fin.</span>
                        </div>
                        <div class="context-card">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <strong>Incidents</strong>
                            <span>Repérer les appels interrompus, retards, problèmes de connexion ou sessions non clôturées.</span>
                        </div>
                        <div class="context-card">
                            <i class="fa-solid fa-file-medical"></i>
                            <strong>Traçabilité</strong>
                            <span>Chaque consultation doit être liée à un patient, un médecin, un horaire et un statut clair.</span>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">🎥</div>
                            <div class="stat-value">3</div>
                            <div class="stat-label">En cours maintenant</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">📅</div>
                            <div class="stat-value">18</div>
                            <div class="stat-label">Planifiées aujourd'hui</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">✅</div>
                            <div class="stat-value">342</div>
                            <div class="stat-label">Ce mois (total)</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <span class="card-title">Consultations en temps réel</span>
                                <span class="card-subtitle">Recherche + filtre instantané</span>
                            </div>
                            <div class="card-header-actions">
                                <div class="search-wrap">
                                    <i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i>
                                    <input type="text" id="consultSearch" class="search-input" placeholder="Rechercher patient, médecin, session…" />
                                </div>
                                <select id="consultStatus" class="select">
                                    <option value="all" selected>Tous les statuts</option>
                                    <option value="en-cours">En cours</option>
                                    <option value="terminee">Terminée</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-body" style="padding:0">
                            <div id="consultEmpty" class="empty-state hidden">
                                <div class="empty-emoji">🔎</div>
                                <div class="empty-title">Aucune consultation trouvée</div>
                                <div class="empty-desc">Essayez une autre recherche ou changez le filtre.</div>
                            </div>

                            <div class="table-wrap">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>ID Session</th>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Démarré</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                        <th class="th-actions">Actions</th>
                                    </tr>
                                    </thead>

                                    <tbody id="consultTbody">
                                    <tr data-session="#SESS-2847" data-patient="Martin Dupont" data-médecin="Dr. S. Martin" data-status="en-cours">
                                        <td style="font-family:monospace;font-size:0.8rem">#SESS-2847</td>
                                        <td>Martin Dupont</td>
                                        <td>Dr. S. Martin</td>
                                        <td>14h32</td>
                                        <td>12:34</td>
                                        <td><span class="badge badge-green">● En cours</span></td>
                                        <td class="th-actions"><button class="btn btn-ghost" type="button" data-details="true">Détails</button></td>
                                    </tr>

                                    <tr data-session="#SESS-2846" data-patient="Marie Leclerc" data-médecin="Dr. S. Martin" data-status="terminee">
                                        <td style="font-family:monospace;font-size:0.8rem">#SESS-2846</td>
                                        <td>Marie Leclerc</td>
                                        <td>Dr. S. Martin</td>
                                        <td>09h05</td>
                                        <td>—</td>
                                        <td><span class="badge badge-gray">Terminée</span></td>
                                        <td class="th-actions"><button class="btn btn-ghost" type="button" data-details="true">Détails</button></td>
                                    </tr>

                                    <tr data-session="#SESS-2845" data-patient="Robert Tissier" data-médecin="Dr. Benali" data-status="terminee">
                                        <td style="font-family:monospace;font-size:0.8rem">#SESS-2845</td>
                                        <td>Robert Tissier</td>
                                        <td>Dr. Benali</td>
                                        <td>08h45</td>
                                        <td>—</td>
                                        <td><span class="badge badge-gray">Terminée</span></td>
                                        <td class="th-actions"><button class="btn btn-ghost" type="button" data-details="true">Détails</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Drawer détails (simple, frontend only) -->
                    <div id="consultDrawer" class="drawer hidden" aria-hidden="true">
                        <div class="drawer-overlay" data-close-drawer="true"></div>
                        <div class="drawer-panel" role="dialog" aria-modal="true" aria-label="Détails consultation">
                            <div class="drawer-header">
                                <div>
                                    <div class="drawer-title">Détails consultation</div>
                                    <div class="drawer-subtitle" id="drawerSubtitle">—</div>
                                </div>
                                <button class="drawer-close" id="drawerClose" type="button" aria-label="Fermer">✕</button>
                            </div>

                            <div class="drawer-body">
                                <div class="drawer-grid">
                                    <div class="drawer-field">
                                        <div class="drawer-label">Session</div>
                                        <div class="drawer-value" id="drawerSession">—</div>
                                    </div>
                                    <div class="drawer-field">
                                        <div class="drawer-label">Patient</div>
                                        <div class="drawer-value" id="drawerPatient">—</div>
                                    </div>
                                    <div class="drawer-field">
                                        <div class="drawer-label">Médecin</div>
                                        <div class="drawer-value" id="drawerMedecin">—</div>
                                    </div>
                                    <div class="drawer-field">
                                        <div class="drawer-label">Démarré</div>
                                        <div class="drawer-value" id="drawerDemarre">—</div>
                                    </div>
                                    <div class="drawer-field">
                                        <div class="drawer-label">Durée</div>
                                        <div class="drawer-value" id="drawerDuree">—</div>
                                    </div>
                                    <div class="drawer-field">
                                        <div class="drawer-label">Statut</div>
                                        <div class="drawer-value" id="drawerStatus">—</div>
                                    </div>
                                </div>

                                <div class="drawer-actions">
                                    <button class="btn btn-primary" type="button" id="drawerOk">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /main-content -->

    </div><!-- /app-layout -->
</div><!-- /page -->

<script>
document.addEventListener('DOMContentLoaded', () => {
  const adminSection = document.getElementById('a-consultations-admin');
  if (adminSection) adminSection.style.display = 'block';

  const searchEl = document.getElementById('consultSearch');
  const statusEl = document.getElementById('consultStatus');
  const emptyEl = document.getElementById('consultEmpty');
  const tbody = document.getElementById('consultTbody');

  const rows = Array.from(tbody?.querySelectorAll('tr') || []);

  function normalize(s){
    return String(s || '').toLowerCase().trim();
  }

  function applyFilters(){
    const q = normalize(searchEl?.value);
    const st = statusEl?.value || 'all';

    let shown = 0;
    rows.forEach(row => {
      const session = normalize(row.dataset.session);
      const patient = normalize(row.dataset.patient);
      // note: dataset key with special chars can be tricky; fallback to cell text search
      const medecin = normalize(row.querySelectorAll('td')[2]?.textContent);
      const status = row.dataset.status;

      const matchQ = !q || session.includes(q) || patient.includes(q) || medecin.includes(q);
      const matchStatus = st === 'all' || (st === 'en-cours' ? status === 'en-cours' : status === 'terminee');

      const visible = matchQ && matchStatus;
      row.style.display = visible ? '' : 'none';
      if (visible) shown++;
    });

    emptyEl?.classList.toggle('hidden', shown !== 0);
  }

  searchEl?.addEventListener('input', applyFilters);
  statusEl?.addEventListener('change', applyFilters);

  // Drawer details
  const drawer = document.getElementById('consultDrawer');
  const closeBtn = document.getElementById('drawerClose');
  const okBtn = document.getElementById('drawerOk');
  const overlay = drawer?.querySelector('.drawer-overlay');

  function openDrawer(row){
    const tds = row.querySelectorAll('td');
    const session = row.dataset.session || tds[0]?.textContent;
    const patient = row.dataset.patient || tds[1]?.textContent;
    const medecin = tds[2]?.textContent;
    const demarre = tds[3]?.textContent;
    const duree = tds[4]?.textContent;
    const statusText = tds[5]?.textContent.trim();

    document.getElementById('drawerSubtitle').textContent = patient + ' • ' + medecin;
    document.getElementById('drawerSession').textContent = session;
    document.getElementById('drawerPatient').textContent = patient;
    document.getElementById('drawerMedecin').textContent = medecin;
    document.getElementById('drawerDemarre').textContent = demarre;
    document.getElementById('drawerDuree').textContent = duree;
    document.getElementById('drawerStatus').textContent = statusText;

    drawer?.classList.remove('hidden');
    drawer?.setAttribute('aria-hidden', 'false');
  }

  function closeDrawer(){
    drawer?.classList.add('hidden');
    drawer?.setAttribute('aria-hidden', 'true');
  }

  rows.forEach(row => {
    const btn = row.querySelector('button[data-details="true"]');
    btn?.addEventListener('click', () => openDrawer(row));
  });

  closeBtn?.addEventListener('click', closeDrawer);
  okBtn?.addEventListener('click', closeDrawer);
  overlay?.addEventListener('click', closeDrawer);

  applyFilters();
});
</script>

</body>
</html>

