<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- SÉCURITÉ ADMIN -->
      <div id="a-securite" class="admin-section" style="display:none">
        <div class="top-bar"><h2>🔐 Sécurité & Journaux d'accès</h2></div>
        <div class="content-area">
          <div class="grid-2 mb-24">
            <div class="card">
              <div class="card-header"><span class="card-title">🛡️ Sécurité système</span></div>
              <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:12px">
                  <div class="flex-between"><span style="font-size:0.88rem">Chiffrement TLS 1.3</span><span class="badge badge-green">Actif</span></div>
                  <div class="flex-between"><span style="font-size:0.88rem">Authentification 2FA</span><span class="badge badge-green">Activé</span></div>
                  <div class="flex-between"><span style="font-size:0.88rem">Conformité RGPD</span><span class="badge badge-green">Conforme</span></div>
                  <div class="flex-between"><span style="font-size:0.88rem">Dernière sauvegarde</span><span class="badge badge-blue">Aujourd'hui 03h00</span></div>
                  <div class="flex-between"><span style="font-size:0.88rem">Tentatives intrusion (24h)</span><span class="badge badge-orange">3 bloquées</span></div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">📊 Accès par rôle (ce mois)</span></div>
              <div class="card-body">
                <div class="metric-bar"><div class="metric-header"><span class="metric-name">Patients</span><span class="metric-value">1 247 sessions</span></div><div class="progress-bar"><div class="progress-fill fill-teal" style="width:85%"></div></div></div>
                <div class="metric-bar"><div class="metric-header"><span class="metric-name">Médecins</span><span class="metric-value">486 sessions</span></div><div class="progress-bar"><div class="progress-fill fill-orange" style="width:55%"></div></div></div>
                <div class="metric-bar"><div class="metric-header"><span class="metric-name">Admins</span><span class="metric-value">42 sessions</span></div><div class="progress-bar"><div class="progress-fill fill-blue" style="width:20%"></div></div></div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><span class="card-title">Journal des accès récents</span></div>
            <div class="card-body" style="padding:0">
              <table class="table">
                <thead><tr><th>Utilisateur</th><th>Action</th><th>IP</th><th>Date/Heure</th><th>Statut</th></tr></thead>
                <tbody>
                  <tr><td>Dr. Sophie Martin</td><td>Connexion</td><td>90.12.34.56</td><td>07 Mai 2025 09:01</td><td><span class="badge badge-green">Succès</span></td></tr>
                  <tr><td>Martin Dupont</td><td>Accès dossier médical</td><td>81.24.67.89</td><td>07 Mai 2025 08:12</td><td><span class="badge badge-green">Autorisé</span></td></tr>
                  <tr><td>Inconnu</td><td>Tentative connexion</td><td>45.78.23.11</td><td>07 Mai 2025 03:42</td><td><span class="badge badge-red">Bloqué</span></td></tr>
                  <tr><td>Admin Système</td><td>Export base de données</td><td>192.168.1.12</td><td>06 Mai 2025 22:00</td><td><span class="badge badge-green">Autorisé</span></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
 
</body>
</html>