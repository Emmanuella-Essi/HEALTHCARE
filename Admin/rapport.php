<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     <!-- RAPPORTS ADMIN -->
      <div id="a-rapports" class="admin-section" style="display:none">
        <div class="top-bar"><h2>📈 Rapports & Statistiques</h2><div class="top-bar-actions"><button class="btn btn-primary" onclick="showToast('Rapport exporté en PDF ✓')">📥 Exporter PDF</button></div></div>
        <div class="content-area">
          <div class="stats-grid">
            <div class="stat-card"><div class="stat-icon">📊</div><div class="stat-value">4 128</div><div class="stat-label">Total consultations</div><div class="stat-change change-up">+18% vs N-1</div></div>
            <div class="stat-card"><div class="stat-icon">💉</div><div class="stat-value">2 847</div><div class="stat-label">Vaccinations réalisées</div><div class="stat-change change-up">+12%</div></div>
            <div class="stat-card"><div class="stat-icon">🎥</div><div class="stat-value">1 563</div><div class="stat-label">Téléconsultations</div><div class="stat-change change-up">+34%</div></div>
            <div class="stat-card"><div class="stat-icon">⭐</div><div class="stat-value">4.7/5</div><div class="stat-label">Satisfaction patients</div><div class="stat-change change-up">+0.3pts</div></div>
          </div>
          <div class="card mt-24">
            <div class="card-header"><span class="card-title">Répartition par type de consultation</span></div>
            <div class="card-body">
              <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;text-align:center">
                <div style="background:var(--green-soft);border-radius:12px;padding:20px"><div style="font-family:'Fraunces',serif;font-size:2rem;font-weight:900;color:var(--teal-dark)">42%</div><div style="font-size:0.82rem;color:var(--teal-dark);margin-top:4px">Téléconsultations</div></div>
                <div style="background:var(--accent-soft);border-radius:12px;padding:20px"><div style="font-family:'Fraunces',serif;font-size:2rem;font-weight:900;color:#b05820">28%</div><div style="font-size:0.82rem;color:#b05820;margin-top:4px">Consultations cabinet</div></div>
                <div style="background:var(--blue-soft);border-radius:12px;padding:20px"><div style="font-family:'Fraunces',serif;font-size:2rem;font-weight:900;color:#1d4ed8">18%</div><div style="font-size:0.82rem;color:#1d4ed8;margin-top:4px">Vaccinations</div></div>
                <div style="background:#f5f3ff;border-radius:12px;padding:20px"><div style="font-family:'Fraunces',serif;font-size:2rem;font-weight:900;color:#5b21b6">12%</div><div style="font-size:0.82rem;color:#5b21b6;margin-top:4px">Bilans & analyses</div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    


</body>
</html>