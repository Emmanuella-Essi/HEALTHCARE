<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     <!-- UTILISATEURS ADMIN -->
      <div id="a-utilisateurs" class="admin-section" style="display:none">
        <div class="top-bar">
          <h2>👥 Gestion des Utilisateurs</h2>
          <div class="top-bar-actions">
            <input class="form-input" style="width:200px" placeholder="🔍 Rechercher...">
            <button class="btn btn-primary">+ Ajouter</button>
          </div>
        </div>
        <div class="content-area">
          <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
            <div class="stat-card"><div class="stat-icon">🧑</div><div class="stat-value">1 284</div><div class="stat-label">Patients</div></div>
            <div class="stat-card"><div class="stat-icon">👨‍⚕️</div><div class="stat-value">38</div><div class="stat-label">Médecins</div></div>
            <div class="stat-card"><div class="stat-icon">🛡️</div><div class="stat-value">4</div><div class="stat-label">Administrateurs</div></div>
          </div>
          <div class="card">
            <div class="card-body" style="padding:0">
              <table class="table">
                <thead><tr><th>Utilisateur</th><th>Rôle</th><th>Email</th><th>Inscription</th><th>Dernière connexion</th><th>Statut</th><th>Actions</th></tr></thead>
                <tbody>
                  <tr><td><strong>Martin Dupont</strong></td><td><span class="badge badge-blue">Patient</span></td><td>martin.d@email.com</td><td>12 Jan 2024</td><td>Aujourd'hui 08h12</td><td><span class="badge badge-green">Actif</span></td><td><button class="btn-sm">Éditer</button></td></tr>
                  <tr><td><strong>Dr. Sophie Martin</strong></td><td><span class="badge badge-orange">Médecin</span></td><td>s.martin@medecin.fr</td><td>03 Oct 2023</td><td>Aujourd'hui 09h00</td><td><span class="badge badge-green">Actif</span></td><td><button class="btn-sm">Éditer</button></td></tr>
                  <tr><td><strong>Dr. Ahmed Benali</strong></td><td><span class="badge badge-orange">Médecin</span></td><td>a.benali@medecin.fr</td><td>15 Oct 2023</td><td>Hier 17h34</td><td><span class="badge badge-green">Actif</span></td><td><button class="btn-sm">Éditer</button></td></tr>
                  <tr><td><strong>Marie Leclerc</strong></td><td><span class="badge badge-blue">Patient</span></td><td>m.leclerc@gmail.com</td><td>20 Mar 2024</td><td>Aujourd'hui 09h15</td><td><span class="badge badge-green">Actif</span></td><td><button class="btn-sm">Éditer</button></td></tr>
                  <tr><td><strong>Ancien utilisateur</strong></td><td><span class="badge badge-blue">Patient</span></td><td>old@email.com</td><td>05 Jan 2023</td><td>10 Jan 2024</td><td><span class="badge badge-gray">Inactif</span></td><td><button class="btn-sm">Réactiver</button></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
   


</body>
</html>