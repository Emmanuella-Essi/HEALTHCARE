<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sécurité & Logs</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_accueil.css">
    <link rel="stylesheet" href="../css/admin_securite.css">
</head>
<body>

<div class="page" id="admin-security">
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
                    <div class="nav-item" onclick="window.location.href='consultation.php'">
                        <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Consultations
                    </div>

                    <div class="nav-item" onclick="window.location.href='rapport.php'">
                        <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Rapports
                    </div>
                    <div class="nav-item active" onclick="window.location.href='securite.php'">
                        <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span> Sécurité &amp; Logs
                    </div>
                </div>
            </nav>

            <div class="sidebar-footer" onclick="window.location.href='../Accueil/home.php'">
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

            <div id="a-securite" class="admin-section">
                <div class="top-bar">
                    <h2><i class="fa-solid fa-shield-halved" style="color:var(--accent)"></i> Sécurité &amp; Journaux d'accès</h2>
                    <div class="top-bar-actions" style="display:none"></div>
                </div>

                <div class="content-area">
                    <div class="grid-2 mb-24">
                        <div class="card">
                            <div class="card-header">
                                <span class="card-title"><i class="fa-solid fa-lock" style="margin-right:10px"></i>Sécurité système</span>
                            </div>
                            <div class="card-body">
                                <div class="security-list">
                                    <div class="flex-between">
                                        <span>Chiffrement TLS 1.3</span>
                                        <span class="badge badge-green">Actif</span>
                                    </div>
                                    <div class="flex-between">
                                        <span>Authentification 2FA</span>
                                        <span class="badge badge-green">Activé</span>
                                    </div>
                                    <div class="flex-between">
                                        <span>Conformité RGPD</span>
                                        <span class="badge badge-green">Conforme</span>
                                    </div>
                                    <div class="flex-between">
                                        <span>Dernière sauvegarde</span>
                                        <span class="badge badge-blue">Aujourd'hui 03h00</span>
                                    </div>
                                    <div class="flex-between">
                                        <span>Tentatives intrusion (24h)</span>
                                        <span class="badge badge-orange">3 bloquées</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <span class="card-title"><i class="fa-solid fa-user-shield" style="margin-right:10px"></i>Accès par rôle (ce mois)</span>
                            </div>
                            <div class="card-body">
                                <div class="metric-bar">
                                    <div class="metric-header">
                                        <span class="metric-name">Patients</span>
                                        <span class="metric-value">1 247 sessions</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill fill-teal" style="width:85%"></div>
                                    </div>
                                </div>

                                <div class="metric-bar">
                                    <div class="metric-header">
                                        <span class="metric-name">Médecins</span>
                                        <span class="metric-value">486 sessions</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill fill-orange" style="width:55%"></div>
                                    </div>
                                </div>

                                <div class="metric-bar">
                                    <div class="metric-header">
                                        <span class="metric-name">Admins</span>
                                        <span class="metric-value">42 sessions</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill fill-blue" style="width:20%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="card-title"><i class="fa-solid fa-clock-rotate-left" style="margin-right:10px"></i>Journal des accès récents</span>
                        </div>
                        <div class="card-body" style="padding:0">
                            <div class="table-wrap">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Action</th>
                                        <th>IP</th>
                                        <th>Date/Heure</th>
                                        <th>Statut</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Dr. Sophie Martin</td>
                                        <td>Connexion</td>
                                        <td>90.12.34.56</td>
                                        <td>07 Mai 2025 09:01</td>
                                        <td><span class="badge badge-green">Succès</span></td>
                                    </tr>
                                    <tr>
                                        <td>Martin Dupont</td>
                                        <td>Accès dossier médical</td>
                                        <td>81.24.67.89</td>
                                        <td>07 Mai 2025 08:12</td>
                                        <td><span class="badge badge-green">Autorisé</span></td>
                                    </tr>
                                    <tr>
                                        <td>Inconnu</td>
                                        <td>Tentative connexion</td>
                                        <td>45.78.23.11</td>
                                        <td>07 Mai 2025 03:42</td>
                                        <td><span class="badge badge-red">Bloqué</span></td>
                                    </tr>
                                    <tr>
                                        <td>Admin Système</td>
                                        <td>Export base de données</td>
                                        <td>192.168.1.12</td>
                                        <td>06 Mai 2025 22:00</td>
                                        <td><span class="badge badge-green">Autorisé</span></td>
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
        const adminSection = document.getElementById('a-securite');
        if (adminSection) adminSection.style.display = 'block';
    });
</script>

</body>
</html>

