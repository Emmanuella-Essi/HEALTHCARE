<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs - Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_accueil.css">
<link rel="stylesheet" href="../css/admin_utlisateur.css">
</head>
<body>

<div class="page" id="admin-utilisateurs">
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
                    <div class="nav-item active" onclick="window.location.href='utilisateur.php'">
                        <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Utilisateurs
                    </div>
                    <div class="nav-item" onclick="window.location.href='medecin.php'">
                        <span class="nav-icon"><i class="fa-solid fa-user-doctor"></i></span> Médecins
                    </div>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Système</div>
                    <div class="nav-item" onclick="window.location.href='consultation.php'">
                        <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Consultations
                    </div>
                    <div class="nav-item" onclick="window.location.href='vaccin.php'">
                        <span class="nav-icon"><i class="fa-solid fa-syringe"></i></span> Suivi vaccinal
                    </div>
                    <div class="nav-item" onclick="window.location.href='rapport.php'">
                        <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Rapports
                    </div>
                    <div class="nav-item" onclick="window.location.href='securite.php'">
                        <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span> Sécurité &amp; Logs
                    </div>
                </div>
            </nav>

            <div class="sidebar-footer">
             <div class="back-btn" onclick="window.location.href='../Accueil/home.php'
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
        </div><!-- /sidebar -->

        <!-- MAIN CONTENT -->
        <div class="main-content">

            <div id="a-utilisateurs" class="admin-section" style="display:none">
                <div class="top-bar">
                    <h2>
                        <i class="fa-solid fa-user-group" style="color:var(--accent)" aria-hidden="true"></i>
                        Gestion des Utilisateurs
                    </h2>
                    <div class="top-bar-actions">
                        <div class="search-wrap" style="min-width:220px">
                            <i class="fa-solid fa-magnifying-glass search-icon" aria-hidden="true"></i>
                            <input type="text" id="userSearch" class="search-input" placeholder="Rechercher...">
                        </div>
                        <button class="btn btn-primary" type="button" id="userAddBtn">+ Ajouter</button>
                    </div>
                </div>

                <div class="content-area">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fa-solid fa-hospital-user" style="color:#0ea5e9"></i></div>
                            <div class="stat-value">1 284</div>
                            <div class="stat-label">Patients</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon"><i class="fa-solid fa-user-doctor" style="color:#10b981"></i></div>
                            <div class="stat-value">38</div>
                            <div class="stat-label">Médecins</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon"><i class="fa-solid fa-user-shield" style="color:#7c3aed"></i></div>
                            <div class="stat-value">4</div>
                            <div class="stat-label">Administrateurs</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon"><i class="fa-solid fa-circle-exclamation" style="color:#f59e0b"></i></div>
                            <div class="stat-value">12</div>
                            <div class="stat-label">En attente</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <span class="card-title">Utilisateurs inscrits</span>
                                <span class="card-subtitle">Vue tableau</span>
                            </div>
                            <div class="card-header-actions">
                                <span class="badge badge-blue" style="padding:6px 12px">Total: 5</span>
                            </div>
                        </div>

                        <div class="card-body" style="padding:0">
                            <div class="table-wrap">
                                <table class="table" aria-label="Liste des utilisateurs">
                                    <thead>
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Rôle</th>
                                            <th>Email</th>
                                            <th>Inscription</th>
                                            <th>Dernière connexion</th>
                                            <th>Statut</th>
                                            <th class="th-actions">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="userTbody">
                                        <tr data-name="Martin Dupont" data-role="Patient" data-email="martin.d@email.com">
                                            <td><strong>Martin Dupont</strong></td>
                                            <td><span class="badge badge-blue">Patient</span></td>
                                            <td>martin.d@email.com</td>
                                            <td>12 Jan 2024</td>
                                            <td>Aujourd'hui 08h12</td>
                                            <td><span class="badge badge-green">Actif</span></td>
                                            <td class="td-actions"><button class="btn btn-ghost" type="button">Éditer</button></td>
                                        </tr>

                                        <tr data-name="Dr. Sophie Martin" data-role="Médecin" data-email="s.martin@medecin.fr">
                                            <td><strong>Dr. Sophie Martin</strong></td>
                                            <td><span class="badge badge-orange">Médecin</span></td>
                                            <td>s.martin@medecin.fr</td>
                                            <td>03 Oct 2023</td>
                                            <td>Aujourd'hui 09h00</td>
                                            <td><span class="badge badge-green">Actif</span></td>
                                            <td class="td-actions"><button class="btn btn-ghost" type="button">Éditer</button></td>
                                        </tr>

                                        <tr data-name="Dr. Ahmed Benali" data-role="Médecin" data-email="a.benali@medecin.fr">
                                            <td><strong>Dr. Ahmed Benali</strong></td>
                                            <td><span class="badge badge-orange">Médecin</span></td>
                                            <td>a.benali@medecin.fr</td>
                                            <td>15 Oct 2023</td>
                                            <td>Hier 17h34</td>
                                            <td><span class="badge badge-green">Actif</span></td>
                                            <td class="td-actions"><button class="btn btn-ghost" type="button">Éditer</button></td>
                                        </tr>

                                        <tr data-name="Marie Leclerc" data-role="Patient" data-email="m.leclerc@gmail.com">
                                            <td><strong>Marie Leclerc</strong></td>
                                            <td><span class="badge badge-blue">Patient</span></td>
                                            <td>m.leclerc@gmail.com</td>
                                            <td>20 Mar 2024</td>
                                            <td>Aujourd'hui 09h15</td>
                                            <td><span class="badge badge-green">Actif</span></td>
                                            <td class="td-actions"><button class="btn btn-ghost" type="button">Éditer</button></td>
                                        </tr>

                                        <tr data-name="Ancien utilisateur" data-role="Patient" data-email="old@email.com">
                                            <td><strong>Ancien utilisateur</strong></td>
                                            <td><span class="badge badge-blue">Patient</span></td>
                                            <td>old@email.com</td>
                                            <td>05 Jan 2023</td>
                                            <td>10 Jan 2024</td>
                                            <td><span class="badge badge-gray">Inactif</span></td>
                                            <td class="td-actions"><button class="btn btn-ghost" type="button">Réactiver</button></td>
                                        </tr>
                                    </tbody>
                                </table>
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
        const adminSection = document.getElementById('a-utilisateurs');
        if (adminSection) adminSection.style.display = 'block';

        const searchEl = document.getElementById('userSearch');
        const tbody = document.getElementById('userTbody');
        const rows = Array.from(tbody ? tbody.querySelectorAll('tr') : []);

        function normalize(s) {
            return String(s || '').toLowerCase().trim();
        }

        function applySearch() {
            const q = normalize(searchEl ? searchEl.value : '');
            rows.forEach(row => {
                const name = normalize(row.dataset.name);
                const role = normalize(row.dataset.role);
                const email = normalize(row.dataset.email);
                const visible = !q || name.includes(q) || role.includes(q) || email.includes(q);
                row.style.display = visible ? '' : 'none';
            });
        }

        if (searchEl) {
            searchEl.addEventListener('input', applySearch);
        }

        applySearch();
    });
</script>

</body>
</html>

