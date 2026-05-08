<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vaccin</title>
  <link rel="stylesheet" href="../css/admin_vaccin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
  </head>
<body>
  <!-- VACCINS ADMIN -->
<div id="a-vaccins-admin" class="admin-section" style="display:none">
  <div class="top-bar">
    <h2 class="top-bar-title">👉 Suivi Vaccinal Global</h2>
    <div class="top-bar-actions">
      <button class="btn btn-primary" onclick="showToast('Rapport vaccinal exporté ✓')">💥 Exporter</button>
    </div>
  </div>

  <div class="content-area">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value">97.3%</div>
        <div class="stat-label">Couverture COVID</div>
        <div class="stat-change change-up">+2.1%</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">💉</div>
        <div class="stat-value">88%</div>
        <div class="stat-label">Couverture DTP</div>
        <div class="stat-change change-up">Stable</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">⚠️</div>
        <div class="stat-value">156</div>
        <div class="stat-label">Rappels en retard</div>
        <div class="stat-change change-down">Suivi requis</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-value">248</div>
        <div class="stat-label">Rappels planifiés/mois</div>
        <div class="stat-change change-up">+15%</div>
      </div>
    </div>

    <div class="card mt-24">
      <div class="card-header">
        <span class="card-title">Couverture vaccinale par vaccin</span>
      </div>

      <div class="card-body">
        <div class="metric-bar">
          <div class="metric-header">
            <span class="metric-name">COVID-19</span>
            <span class="metric-value">97.3%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill fill-teal" style="width:97%"></div>
          </div>
        </div>

        <div class="metric-bar">
          <div class="metric-header">
            <span class="metric-name">Grippe saisonnière</span>
            <span class="metric-value">74%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill fill-blue" style="width:74%"></div>
          </div>
        </div>

        <div class="metric-bar">
          <div class="metric-header">
            <span class="metric-name">DTP</span>
            <span class="metric-value">88%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill fill-teal" style="width:88%"></div>
          </div>
        </div>

        <div class="metric-bar">
          <div class="metric-header">
            <span class="metric-name">Hépatite B</span>
            <span class="metric-value">61%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill fill-orange" style="width:61%"></div>
          </div>
        </div>

        <div class="metric-bar">
          <div class="metric-header">
            <span class="metric-name">ROR</span>
            <span class="metric-value">93%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill fill-teal" style="width:93%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


</body>
</html>