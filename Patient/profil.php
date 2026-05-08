<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mon Profil — HealthCare</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/profil.css" />
</head>
<body>

  <!-- SIDEBAR -->
<aside class="sidebar">
 
  <!-- Logo -->
  <div class="sb-logo">
    <div class="logo-icon">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1de9b6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
      </svg>
    </div>
    <div class="logo-text">Health<span>Care</span></div>
  </div>
 
  <!-- Navigation -->
  <nav class="sb-nav">
 
    <div class="section-label">Principal</div>
 
    <a href="accueil.php" class="nav-item active">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/>
          <polyline points="9 21 9 12 15 12 15 21"/>
        </svg>
      </div>
      <span class="ni-label">Accueil</span>
    </a>
 
    <div class="nav-item" onclick="Suivant('rdv', this)">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <span class="ni-label">Rendez-vous</span>
      <span class="ni-badge">4</span>
    </div>
 
    <div class="nav-item" onclick="Suivant('consultation', this)">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="23 7 16 12 23 17 23 7"/>
          <rect x="1" y="5" width="15" height="14" rx="2"/>
        </svg>
      </div>
      <span class="ni-label">Télé-expertise</span>
    </div>
 
    <div class="section-label">Santé</div>
 
    <div class="nav-item" onclick="Suivant('vaccins', this)">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="16" y2="8"/>
          <line x1="16" y1="2" x2="22" y2="8"/>
          <path d="M16 8l-3 3-1-1-5.5 5.5a2.5 2.5 0 0 0 3.5 3.5L15.5 13l-1-1 3-3"/>
          <line x1="5" y1="20" x2="2" y2="23"/>
        </svg>
      </div>
      <span class="ni-label">Vaccins</span>
    </div>
 
    <div class="nav-item" onclick="Suivant('dossier', this)">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
      </div>
      <span class="ni-label">Dossier médical</span>
    </div>
 
    <div class="nav-item" onclick="Suivant('ordonnances', this)">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
          <rect x="8" y="2" width="8" height="4" rx="1"/>
          <line x1="8" y1="12" x2="16" y2="12"/>
          <line x1="8" y1="16" x2="12" y2="16"/>
        </svg>
      </div>
      <span class="ni-label">Ordonnances</span>
    </div>
 
    <div class="section-label">Compte</div>
 
    <div class="nav-item" onclick="Suivant('profil', this)">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="4"/>
          <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
        </svg>
      </div>
      <span class="ni-label">Mon profil</span>
    </div>
 
  </nav>
 
  <!-- Footer -->
  <div class="sb-footer">
    <div class="deconnect-btn" onclick="AutrePage('landing')">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      <span class="deconnect-label">Déconnexion</span>
    </div>
 
    <div class="sb-user">
      <div class="avatar">MD</div>
      <div class="user-info">
        <div class="user-name">Manue Essi</div>
        <div class="user-role">Patient</div>
      </div>
    </div>
  </div>
 
</aside>

  <!-- MAIN -->
  <main class="main">

    <!-- TOPBAR -->
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle">☰</button>
        <div class="page-title">
          <h1>Mon Profil</h1>
          <p>Gérez vos informations personnelles et médicales</p>
        </div>
      </div>
      <div class="topbar-right">
        <div class="notif-btn">🔔<span class="notif-badge">2</span></div>
        <div class="user-avatar" id="topAvatar">KD</div>
      </div>
    </header>

    <!-- HERO PROFIL -->
    <section class="profil-hero animate-in" style="--delay:.1s">
      <div class="hero-bg"></div>
      <div class="hero-content">
        <div class="avatar-wrap">
          <div class="avatar-circle" id="heroAvatar">KD</div>
          <div class="avatar-status"></div>
        </div>
        <div class="hero-info">
          <h2 id="heroName">Konan Diane</h2>
          <p id="heroEmail">diane.konan@email.ci</p>
          <div class="hero-tags">
            <span class="tag tag-blood" id="heroBlood">🩸 A+</span>
            <span class="tag tag-age" id="heroAge">📅 24 ans</span>
            <span class="tag tag-sex" id="heroSex">👤 Féminin</span>
          </div>
        </div>
        <button class="btn-edit-hero" id="openEditBtn">✏️ Modifier le profil</button>
      </div>
    </section>

    <!-- GRILLE INFOS -->
    <div class="profil-grid">

      <!-- INFOS PERSONNELLES -->
      <div class="profil-card animate-in" style="--delay:.2s">
        <div class="card-header">
          <div class="card-icon" style="background:var(--color-primary)">👤</div>
          <h3>Informations personnelles</h3>
        </div>
        <div class="info-list" id="infoPerso">
          <!-- JS -->
        </div>
      </div>

      <!-- INFOS MÉDICALES -->
      <div class="profil-card animate-in" style="--delay:.3s">
        <div class="card-header">
          <div class="card-icon" style="background:var(--color-teal)">🏥</div>
          <h3>Informations médicales</h3>
        </div>
        <div class="info-list" id="infoMedical">
          <!-- JS -->
        </div>
      </div>

      <!-- CONTACT D'URGENCE -->
      <div class="profil-card animate-in" style="--delay:.4s">
        <div class="card-header">
          <div class="card-icon" style="background:#F59E0B">🚨</div>
          <h3>Contact d'urgence</h3>
        </div>
        <div class="info-list" id="infoUrgence">
          <!-- JS -->
        </div>
      </div>

      <!-- SÉCURITÉ -->
      <div class="profil-card animate-in" style="--delay:.5s">
        <div class="card-header">
          <div class="card-icon" style="background:var(--color-accent)">🔐</div>
          <h3>Sécurité du compte</h3>
        </div>
        <div class="security-section">
          <div class="security-item">
            <div class="security-icon">🔑</div>
            <div class="security-info">
              <span class="security-label">Mot de passe</span>
              <span class="security-val">Dernière modif. il y a 30 jours</span>
            </div>
            <button class="btn-outline" id="openPwdBtn">Changer</button>
          </div>
          <div class="security-item">
            <div class="security-icon">📧</div>
            <div class="security-info">
              <span class="security-label">Email de connexion</span>
              <span class="security-val" id="secEmail">diane.konan@email.ci</span>
            </div>
            <span class="badge-verified">✅ Vérifié</span>
          </div>
          <div class="security-item">
            <div class="security-icon">🛡️</div>
            <div class="security-info">
              <span class="security-label">Session active</span>
              <span class="security-val">Connecté depuis aujourd'hui</span>
            </div>
            <span class="badge-active-dot">● Actif</span>
          </div>
        </div>
      </div>

    </div>

    <!-- STATISTIQUES RAPIDES -->
    <section class="stats-section animate-in" style="--delay:.6s">
      <h3 class="section-title">📊 Récapitulatif médical</h3>
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--color-primary)">💉</div>
          <div class="stat-info">
            <span class="stat-number" id="statVaccins">0</span>
            <span class="stat-label">Vaccins</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--color-teal)">📋</div>
          <div class="stat-info">
            <span class="stat-number" id="statOrdos">0</span>
            <span class="stat-label">Ordonnances</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--color-accent)">📅</div>
          <div class="stat-info">
            <span class="stat-number" id="statRdv">0</span>
            <span class="stat-label">Rendez-vous</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:#F59E0B">📘</div>
          <div class="stat-info">
            <span class="stat-number" id="statCarnet">0</span>
            <span class="stat-label">Entrées carnet</span>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- MODAL EDITION PROFIL -->
  <div class="modal-overlay" id="editOverlay">
    <div class="modal">
      <div class="modal-header">
        <h2>✏️ Modifier le profil</h2>
        <button class="modal-close" id="editClose">✕</button>
      </div>
      <div class="modal-body">

        <div class="tab-bar">
          <button class="tab active" data-tab="perso">👤 Personnel</button>
          <button class="tab" data-tab="medical">🏥 Médical</button>
          <button class="tab" data-tab="urgence">🚨 Urgence</button>
        </div>

        <!-- TAB PERSONNEL -->
        <div class="tab-content active" id="tab-perso">
          <div class="form-row">
            <div class="form-group">
              <label>Prénom</label>
              <input type="text" id="inPrenom" placeholder="Diane" />
            </div>
            <div class="form-group">
              <label>Nom</label>
              <input type="text" id="inNom" placeholder="Konan" />
            </div>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" id="inEmail" placeholder="diane.konan@email.ci" />
          </div>
          <div class="form-group">
            <label>Téléphone</label>
            <input type="tel" id="inTel" placeholder="+225 07 00 00 00 00" />
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Date de naissance</label>
              <input type="date" id="inDob" />
            </div>
            <div class="form-group">
              <label>Sexe</label>
              <select id="inSexe">
                <option value="Féminin">Féminin</option>
                <option value="Masculin">Masculin</option>
                <option value="Autre">Autre</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Adresse</label>
            <input type="text" id="inAdresse" placeholder="Cocody, Abidjan" />
          </div>
        </div>

        <!-- TAB MEDICAL -->
        <div class="tab-content" id="tab-medical">
          <div class="form-row">
            <div class="form-group">
              <label>Groupe sanguin</label>
              <select id="inBlood">
                <option>A+</option><option>A-</option>
                <option>B+</option><option>B-</option>
                <option>AB+</option><option>AB-</option>
                <option>O+</option><option>O-</option>
              </select>
            </div>
            <div class="form-group">
              <label>Taille (cm)</label>
              <input type="number" id="inTaille" placeholder="165" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Poids (kg)</label>
              <input type="number" id="inPoids" placeholder="58" />
            </div>
            <div class="form-group">
              <label>Médecin traitant</label>
              <input type="text" id="inMedecin" placeholder="Dr. Yao Ama" />
            </div>
          </div>
          <div class="form-group">
            <label>Allergies connues</label>
            <textarea id="inAllergies" rows="2" placeholder="Pénicilline, arachides…"></textarea>
          </div>
          <div class="form-group">
            <label>Antécédents médicaux</label>
            <textarea id="inAntecedents" rows="2" placeholder="Hypertension, diabète…"></textarea>
          </div>
          <div class="form-group">
            <label>Traitements en cours</label>
            <textarea id="inTraitements" rows="2" placeholder="Amlodipine 5mg / jour…"></textarea>
          </div>
        </div>

        <!-- TAB URGENCE -->
        <div class="tab-content" id="tab-urgence">
          <div class="form-row">
            <div class="form-group">
              <label>Nom du contact</label>
              <input type="text" id="inUrgNom" placeholder="Konan Paul" />
            </div>
            <div class="form-group">
              <label>Lien de parenté</label>
              <input type="text" id="inUrgLien" placeholder="Frère, mère…" />
            </div>
          </div>
          <div class="form-group">
            <label>Téléphone</label>
            <input type="tel" id="inUrgTel" placeholder="+225 05 00 00 00 00" />
          </div>
          <div class="form-group">
            <label>Email (optionnel)</label>
            <input type="email" id="inUrgEmail" placeholder="paul.konan@email.ci" />
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn-secondary" id="editCancel">Annuler</button>
        <button class="btn-primary" id="editSave">💾 Enregistrer</button>
      </div>
    </div>
  </div>

  <!-- MODAL MOT DE PASSE -->
  <div class="modal-overlay" id="pwdOverlay">
    <div class="modal modal-sm">
      <div class="modal-header">
        <h2>🔑 Changer le mot de passe</h2>
        <button class="modal-close" id="pwdClose">✕</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Mot de passe actuel</label>
          <div class="input-eye">
            <input type="password" id="inPwdOld" placeholder="••••••••" />
            <button class="eye-btn" onclick="togglePwd('inPwdOld', this)">👁️</button>
          </div>
        </div>
        <div class="form-group">
          <label>Nouveau mot de passe</label>
          <div class="input-eye">
            <input type="password" id="inPwdNew" placeholder="••••••••" />
            <button class="eye-btn" onclick="togglePwd('inPwdNew', this)">👁️</button>
          </div>
        </div>
        <div class="form-group">
          <label>Confirmer le mot de passe</label>
          <div class="input-eye">
            <input type="password" id="inPwdConfirm" placeholder="••••••••" />
            <button class="eye-btn" onclick="togglePwd('inPwdConfirm', this)">👁️</button>
          </div>
        </div>
        <div class="pwd-strength" id="pwdStrength"></div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" id="pwdCancel">Annuler</button>
        <button class="btn-primary" id="pwdSave">Enregistrer</button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast" id="toast"></div>

  <script src="js/profil.js"></script>
</body>
</html>