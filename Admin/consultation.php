<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     <!-- CONSULTATIONS ADMIN -->
      <div id="a-consultations-admin" class="admin-section" style="display:none">
        <div class="top-bar"><h2>🎥 Suivi des Consultations</h2></div>
        <div class="content-area">
          <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
            <div class="stat-card"><div class="stat-icon">🎥</div><div class="stat-value">3</div><div class="stat-label">En cours maintenant</div></div>
            <div class="stat-card"><div class="stat-icon">📅</div><div class="stat-value">18</div><div class="stat-label">Planifiées aujourd'hui</div></div>
            <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-value">342</div><div class="stat-label">Ce mois (total)</div></div>
          </div>
          <div class="card">
            <div class="card-header"><span class="card-title">Consultations en temps réel</span></div>
            <div class="card-body" style="padding:0">
              <table class="table">
                <thead><tr><th>ID Session</th><th>Patient</th><th>Médecin</th><th>Démarré</th><th>Durée</th><th>Statut</th></tr></thead>
                <tbody>
                  <tr><td style="font-family:monospace;font-size:0.8rem">#SESS-2847</td><td>Martin Dupont</td><td>Dr. S. Martin</td><td>14h32</td><td>12:34</td><td><span class="badge badge-green">● En cours</span></td></tr>
                  <tr><td style="font-family:monospace;font-size:0.8rem">#SESS-2846</td><td>Marie Leclerc</td><td>Dr. S. Martin</td><td>09h05</td><td>Terminé</td><td><span class="badge badge-gray">Terminé</span></td></tr>
                  <tr><td style="font-family:monospace;font-size:0.8rem">#SESS-2845</td><td>Robert Tissier</td><td>Dr. Benali</td><td>08h45</td><td>Terminé</td><td><span class="badge badge-gray">Terminé</span></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
 
    
    

</body>
</html>