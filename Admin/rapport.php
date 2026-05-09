<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rapports &amp; Statistiques</title>
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- CSS dédié -->
  <link rel="stylesheet" href="../css/admin_rapport.css">
  <link rel="stylesheet" href="../css/admin_shared.css">
  <!-- Chart.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js" defer></script>
</head>
<body>

<div class="page" id="admin-dashboard">
  <div class="app-layout">

    <!-- ══════════════════════════════════
         SIDEBAR
    ══════════════════════════════════ -->
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

          <div class="nav-item active" onclick="window.location.href='rapport.php'">
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

    <!-- ══════════════════════════════════
         CONTENU PRINCIPAL
    ══════════════════════════════════ -->
    <div class="main-content">
      <div id="a-rapports" class="admin-section">

    <!-- ── TOP BAR ── -->
    <div class="top-bar">
      <h2>
        <span class="icon-wrap" aria-hidden="true">📈</span>
        Rapports &amp; Statistiques
      </h2>
      <button class="btn-primary" id="exportBtn">
        📥 Exporter PDF
      </button>
    </div>

    <div class="admin-welcome">
      <div>
        <span class="welcome-kicker">Analyse et pilotage</span>
        <h1>Rapports de performance</h1>
        <p>Centralisez les indicateurs clés: consultations, vaccinations, téléconsultations, satisfaction et tendances mensuelles pour décider rapidement.</p>
      </div>
      <div class="welcome-actions">
        <a href="consultation.php" class="welcome-btn primary"><i class="fa-solid fa-video"></i> Consultations</a>
        <a href="vaccin.php" class="welcome-btn"><i class="fa-solid fa-syringe"></i> Vaccins</a>
      </div>
    </div>

    <div class="admin-context-grid">
      <div class="context-card">
        <i class="fa-solid fa-chart-column"></i>
        <strong>Indicateurs</strong>
        <span>Comparer les volumes mensuels, les tendances de téléconsultation et la vaccination.</span>
      </div>
      <div class="context-card">
        <i class="fa-solid fa-file-export"></i>
        <strong>Exports</strong>
        <span>Préparer les synthèses à transmettre à la direction ou aux équipes médicales.</span>
      </div>
      <div class="context-card">
        <i class="fa-solid fa-circle-info"></i>
        <strong>Décisions</strong>
        <span>Identifier les pics d'activité, les zones de surcharge et les actions prioritaires.</span>
      </div>
    </div>

    <!-- ── STAT CARDS ── -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-value">4 128</div>
        <div class="stat-label">Total consultations</div>
        <div class="stat-change">↑ +18% vs N-1</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">💉</div>
        <div class="stat-value">2 847</div>
        <div class="stat-label">Vaccinations réalisées</div>
        <div class="stat-change">↑ +12%</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🎥</div>
        <div class="stat-value">1 563</div>
        <div class="stat-label">Téléconsultations</div>
        <div class="stat-change">↑ +34%</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-value">4.7 / 5</div>
        <div class="stat-label">Satisfaction patients</div>
        <div class="stat-change">↑ +0.3 pts</div>
      </div>
    </div>

    <!-- ── RÉPARTITION + DONUT ── -->
    <div class="card mt-24">
      <div class="card-header">
        <span class="card-title">Répartition par type de consultation</span>
        <span class="card-subtitle">Année en cours</span>
      </div>
      <div class="card-body">

        <!-- Blocs colorés -->
        <div class="distrib-grid">
          <div class="distrib-item" style="background:#e1f5ee">
            <div class="distrib-pct" style="color:#085041">42%</div>
            <div class="distrib-label" style="color:#0f6e56">Téléconsultations</div>
          </div>
          <div class="distrib-item" style="background:#faece7">
            <div class="distrib-pct" style="color:#4a1b0c">28%</div>
            <div class="distrib-label" style="color:#993c1d">Consultations cabinet</div>
          </div>
          <div class="distrib-item" style="background:#e6f1fb">
            <div class="distrib-pct" style="color:#042c53">18%</div>
            <div class="distrib-label" style="color:#185fa5">Vaccinations</div>
          </div>
          <div class="distrib-item" style="background:#eeedfe">
            <div class="distrib-pct" style="color:#26215c">12%</div>
            <div class="distrib-label" style="color:#534ab7">Bilans &amp; analyses</div>
          </div>
        </div>

        <!-- Donut chart -->
        <div class="chart-section">
          <div class="chart-legend">
            <span><span class="legend-dot" style="background:#1d9e75"></span>Téléconsultations — 42%</span>
            <span><span class="legend-dot" style="background:#d85a30"></span>Cabinet — 28%</span>
            <span><span class="legend-dot" style="background:#378add"></span>Vaccinations — 18%</span>
            <span><span class="legend-dot" style="background:#7f77dd"></span>Bilans — 12%</span>
          </div>
          <div style="position:relative; width:100%; height:240px">
            <canvas id="distChart"
              role="img"
              aria-label="Graphique en anneau : téléconsultations 42%, cabinet 28%, vaccinations 18%, bilans 12%">
              Téléconsultations 42%, Consultations cabinet 28%, Vaccinations 18%, Bilans 12%.
            </canvas>
          </div>
        </div>

      </div>
    </div>

    <!-- ── ÉVOLUTION MENSUELLE ── -->
    <p class="section-header">Évolution mensuelle — consultations</p>
    <div class="card">
      <div class="card-header">
        <span class="card-title">Tendance Jan – Jun</span>
        <span class="card-subtitle">6 derniers mois</span>
      </div>
      <div class="card-body">
        <div class="chart-legend">
          <span><span class="legend-dot" style="background:#7f77dd"></span>Total consultations</span>
          <span><span class="legend-dot" style="background:#1d9e75; border-radius:50%"></span>Téléconsultations</span>
        </div>
        <div style="position:relative; width:100%; height:240px">
          <canvas id="trendChart"
            role="img"
            aria-label="Courbes d'évolution mensuelle des consultations et téléconsultations sur 6 mois">
            Jan–Jun : consultations totales et téléconsultations en hausse constante.
          </canvas>
        </div>
      </div>
    </div>

      </div><!-- /admin-section -->
    </div><!-- /main-content -->
  </div><!-- /app-layout -->
</div><!-- /page -->

  <!-- ── SCRIPTS ── -->
  <script>
    // Bouton export
    document.getElementById('exportBtn').addEventListener('click', function () {
      this.textContent = '✓ Rapport exporté !';
      this.style.background = 'linear-gradient(135deg,#059669,#10b981)';
      setTimeout(() => {
        this.textContent = '📥 Exporter PDF';
        this.style.background = '';
      }, 2500);
    });

    // Attendre que Chart.js soit chargé (defer)
    window.addEventListener('DOMContentLoaded', () => {

      /* ── Donut — Répartition ── */
      new Chart(document.getElementById('distChart'), {
        type: 'doughnut',
        data: {
          labels: ['Téléconsultations', 'Cabinet', 'Vaccinations', 'Bilans'],
          datasets: [{
            data: [42, 28, 18, 12],
            backgroundColor: ['#1d9e75', '#d85a30', '#378add', '#7f77dd'],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '70%',
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => '  ' + ctx.label + ' : ' + ctx.parsed + '%'
              }
            }
          },
          animation: { animateRotate: true, duration: 900 }
        }
      });

      /* ── Line — Évolution mensuelle ── */
      new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
          labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
          datasets: [
            {
              label: 'Total consultations',
              data: [620, 680, 710, 740, 780, 810],
              borderColor: '#7f77dd',
              backgroundColor: 'rgba(127,119,221,0.08)',
              fill: true,
              tension: 0.4,
              pointRadius: 5,
              pointBackgroundColor: '#7f77dd',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              borderWidth: 2.5
            },
            {
              label: 'Téléconsultations',
              data: [240, 270, 295, 320, 350, 370],
              borderColor: '#1d9e75',
              backgroundColor: 'rgba(29,158,117,0.07)',
              fill: true,
              tension: 0.4,
              pointRadius: 5,
              pointBackgroundColor: '#1d9e75',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              borderWidth: 2.5,
              borderDash: [6, 3]
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: {
              grid: { color: 'rgba(0,0,0,0.04)' },
              ticks: { font: { size: 12 }, color: '#6b7280' }
            },
            y: {
              grid: { color: 'rgba(0,0,0,0.04)' },
              ticks: {
                font: { size: 12 },
                color: '#6b7280',
                callback: v => v.toLocaleString('fr-FR')
              }
            }
          },
          interaction: { mode: 'index', intersect: false }
        }
      });

    });
  </script>

</body>
</html>
