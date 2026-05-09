<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Portail Patient</title>
  <meta name="description" content="Votre espace santé personnel : consultations, ordonnances, résultats et bien plus.">
  <link rel="stylesheet" href="../css/accueil_patient.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <link rel="stylesheet" href="../css/accueil_patient_ref.css">
</head>
<body>

<!-- ================= LAYOUT WRAPPER ================= -->
<div class="layout">

  <!-- ================= SIDEBAR ================= -->
  <aside class="sidebar">
    <div class="sb-logo">
      <div class="logo-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1de9b6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
        </svg>
      </div>
      <div class="logo-text">Health<span>Care</span></div>
    </div>

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


      <a href="telexp.php" class="nav-item">
        <div class="ni-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="23 7 16 12 23 17 23 7"/>
            <rect x="1" y="5" width="15" height="14" rx="2"/>
          </svg>
        </div>
        <span class="ni-label">Télé-expertise</span>
      </a>
      <a href="rdv.php" class="nav-item">
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
    </a>

      

      <div class="section-label">Santé</div>

      <a href="vaccins.php" class="nav-item">
        <div class="ni-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="16" y2="8"/>
            <line x1="16" y1="2" x2="22" y2="8"/>
            <path d="M16 8l-3 3-1-1-5.5 5.5a2.5 2.5 0 0 0 3.5 3.5L15.5 13l-1-1 3-3"/>
            <line x1="5" y1="20" x2="2" y2="23"/>
          </svg>
        </div>
        <span class="ni-label">Vaccins</span>
      </a>

      <a href="dossier.php" class="nav-item">

        <div class="ni-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
          </svg>
        </div>
        <span class="ni-label">Dossier médical</span>
      </a>

      <a href="ordonnances.php" class="nav-item">
        <div class="ni-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
            <rect x="8" y="2" width="8" height="4" rx="1"/>
            <line x1="8" y1="12" x2="16" y2="12"/>
            <line x1="8" y1="16" x2="12" y2="16"/>
          </svg>
        </div>
        <span class="ni-label">Ordonnances</span>
      </a>

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

    <div class="sb-footer">
      <div class="deconnect-btn" onclick="window.location.href='../Accueil/home.php'">
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
          <div class="user-name">Patient</div>
          <div class="user-role">Dossier</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ================= MAIN CONTENT ================= -->
  <div class="main-content">

    <!-- ================= NAVBAR ================= -->
    <header>
      <div class="navbar">
        <a href="#" class="brand">
          <div class="brand-icon">🏥</div>
          <span class="brand-name">Medi<em>Care</em>+</span>
        </a>

        <nav>
          <ul>
            <li><a href="#services">Services</a></li>
            <li><a href="#rendez-vous">Rendez-vous</a></li>
            <li><a href="#medecins">Médecins</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </nav>

        <div class="nav-actions">
          <a href="login.html" class="btn btn-ghost">Connexion</a>
          <a href="espace.html" class="btn btn-navy">Mon espace</a>
        </div>
      </div>
    </header>

    <!-- ================= HERO ================= -->
    <section class="hero">
      <div class="hero-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
      </div>

      <div class="hero-grid">

        <!-- Texte -->
        <div class="hero-content">
          <div class="hero-pill">
            <span class="pill-dot"></span>
            Portail santé de confiance
          </div>

          <h1 class="hero-h1">
            Bienvenue,<br>
            <span class="accent">Sophie</span> 👋<br>
            votre santé,<br>simplifiée.
          </h1>

          <p class="hero-sub">
            Gérez vos rendez-vous, consultez vos ordonnances et accédez
            à vos résultats médicaux depuis un seul espace sécurisé et intuitif.
          </p>

          <div class="hero-btns">
            <a href="#rendez-vous" class="btn btn-teal btn-lg">📅 Prendre rendez-vous</a>
            <a href="dossier.html" class="btn btn-ghost btn-lg">📂 Mon dossier</a>
          </div>

          <div class="hero-kpi">
            <div>
              <div class="kpi-val">+12k</div>
              <div class="kpi-label">Patients</div>
            </div>
            <div>
              <div class="kpi-val">98%</div>
              <div class="kpi-label">Satisfaction</div>
            </div>
            <div>
              <div class="kpi-val">150+</div>
              <div class="kpi-label">Médecins</div>
            </div>
          </div>
        </div>

        <!-- Carte patient -->
        <div class="patient-card">
          <div class="pc-head">
            <div class="pc-patient">
              <div class="avatar">SM</div>
              <div>
                <div class="pc-name">Sophie Martin</div>
                <div class="pc-id">PAT-20847</div>
              </div>
            </div>
            <span class="tag-active">Actif</span>
          </div>

          <div class="vitals">
            <div class="vital">
              <span class="vital-ico">❤️</span>
              <div>
                <div class="vital-lbl">Tension</div>
                <div class="vital-val">120/80</div>
              </div>
            </div>
            <div class="vital">
              <span class="vital-ico">🩸</span>
              <div>
                <div class="vital-lbl">Groupe</div>
                <div class="vital-val">A+</div>
              </div>
            </div>
            <div class="vital">
              <span class="vital-ico">⚖️</span>
              <div>
                <div class="vital-lbl">IMC</div>
                <div class="vital-val">22.4</div>
              </div>
            </div>
            <div class="vital">
              <span class="vital-ico">🌡️</span>
              <div>
                <div class="vital-lbl">Temp.</div>
                <div class="vital-val">36.7°C</div>
              </div>
            </div>
          </div>

          <div class="next-rdv">
            <div class="label-sm">Prochain rendez-vous</div>
            <div class="rdv-item">
              <div>
                <div class="rdv-doc">Dr. Éric Dubois</div>
                <div class="rdv-spec">Cardiologie</div>
              </div>
              <div>
                <div class="rdv-time">09h30</div>
                <div class="rdv-date">13 Mai 2026</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ================= SERVICES ================= -->
    <section id="services" class="services">
      <div class="section-center">
        <span class="s-tag">Nos services</span>
        <h2 class="s-title">Tout ce dont vous avez besoin</h2>
        <p class="s-sub">Un portail complet pour gérer votre parcours de soins de A à Z.</p>
      </div>

      <div class="services-grid">
        <div class="svc-card">
          <div class="svc-ico">🩺</div>
          <div class="svc-name">Consultation</div>
          <p class="svc-desc">Prenez rendez-vous avec nos spécialistes en quelques clics, 24h/24.</p>
          <a href="#" class="svc-link">En savoir plus →</a>
        </div>
        <div class="svc-card">
          <div class="svc-ico">📋</div>
          <div class="svc-name">Ordonnances</div>
          <p class="svc-desc">Renouvelez vos prescriptions et suivez votre traitement en ligne.</p>
          <a href="#" class="svc-link">En savoir plus →</a>
        </div>
        <div class="svc-card">
          <div class="svc-ico">🔬</div>
          <div class="svc-name">Analyses</div>
          <p class="svc-desc">Accédez à vos résultats d'analyses dès leur disponibilité.</p>
          <a href="#" class="svc-link">En savoir plus →</a>
        </div>
        <div class="svc-card">
          <div class="svc-ico">💊</div>
          <div class="svc-name">Pharmacie</div>
          <p class="svc-desc">Commandez vos médicaments avec livraison à domicile rapide.</p>
          <a href="#" class="svc-link">En savoir plus →</a>
        </div>
        <div class="svc-card">
          <div class="svc-ico">🏥</div>
          <div class="svc-name">Hospitalisation</div>
          <p class="svc-desc">Gérez vos admissions et consultez votre dossier hospitalier.</p>
          <a href="#" class="svc-link">En savoir plus →</a>
        </div>
        <div class="svc-card">
          <div class="svc-ico">📱</div>
          <div class="svc-name">Téléconsultation</div>
          <p class="svc-desc">Consultez un médecin en vidéo, où que vous soyez dans le monde.</p>
          <a href="#" class="svc-link">En savoir plus →</a>
        </div>
      </div>
    </section>

    <!-- ================= RENDEZ-VOUS ================= -->
    <section id="rendez-vous" class="appt-section">
      <div class="appt-inner">
        <div class="appt-text">
          <span class="s-tag" style="background:rgba(0,180,160,.18); color:#00b4a0;">Réservation</span>
          <h2 class="s-title">Prenez rendez-vous en 2 minutes</h2>
          <p class="s-sub">
            Choisissez votre spécialiste, sélectionnez un créneau et confirmez en un clic.
            Notre équipe vous rappelle sous 24 h.
          </p>
          <ul class="check-list">
            <li><span class="check-ico">✓</span>Confirmation immédiate par SMS</li>
            <li><span class="check-ico">✓</span>Rappel automatique 24 h avant le rendez-vous</li>
            <li><span class="check-ico">✓</span>Annulation gratuite jusqu'à 4 h avant</li>
            <li><span class="check-ico">✓</span>Consultation en présentiel ou en vidéo</li>
          </ul>
        </div>

        <div class="appt-form">
          <h3>📅 Nouvelle demande</h3>
          <div id="alert-success" class="alert alert-success hidden">
            ✅ Votre demande a bien été enregistrée. Nous vous contacterons sous 24 h.
          </div>
          <div id="alert-error" class="alert alert-error hidden">
            ⚠️ Veuillez remplir tous les champs correctement.
          </div>

          <form id="rdv-form" novalidate>
            <div class="form-row-2">
              <div class="fg">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
              </div>
              <div class="fg">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
              </div>
            </div>
            <div class="fg">
              <label for="telephone">Téléphone</label>
              <input type="tel" id="telephone" name="telephone" placeholder="+225 07 00 00 00 00" required>
            </div>
            <div class="fg">
              <label for="specialite">Spécialité</label>
              <select id="specialite" name="specialite" required>
                <option value="">-- Sélectionner --</option>
                <option value="Cardiologie">Cardiologie</option>
                <option value="Pédiatrie">Pédiatrie</option>
                <option value="Dermatologie">Dermatologie</option>
                <option value="Neurologie">Neurologie</option>
                <option value="Ophtalmologie">Ophtalmologie</option>
                <option value="Gynécologie">Gynécologie</option>
                <option value="Médecine générale">Médecine générale</option>
                <option value="Orthopédie">Orthopédie</option>
              </select>
            </div>
            <div class="fg">
              <label for="date_rdv">Date souhaitée</label>
              <input type="date" id="date_rdv" name="date_rdv" required>
            </div>
            <button type="submit" class="btn btn-teal btn-lg btn-block">
              Envoyer ma demande →
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- ================= MÉDECINS ================= -->
    <section id="medecins" class="doctors">
      <div class="section-center">
        <span class="s-tag">Notre équipe</span>
        <h2 class="s-title">Des spécialistes à votre écoute</h2>
        <p class="s-sub">Nos médecins sont sélectionnés pour leur expertise et leur bienveillance.</p>
      </div>

      <div class="doctors-grid">
        <div class="doc-card">
          <div class="doc-av">👨‍⚕️</div>
          <div class="doc-name">Dr. Éric Dubois</div>
          <div class="doc-spec">Cardiologie</div>
          <div class="doc-rating">
            <span class="stars">★★★★★</span>
            <strong>4.9</strong>
            <span>(128 avis)</span>
          </div>
          <a href="#rendez-vous" class="btn btn-navy btn-block">Prendre RDV</a>
        </div>
        <div class="doc-card">
          <div class="doc-av">👩‍⚕️</div>
          <div class="doc-name">Dr. Amina Koné</div>
          <div class="doc-spec">Pédiatrie</div>
          <div class="doc-rating">
            <span class="stars">★★★★★</span>
            <strong>4.8</strong>
            <span>(97 avis)</span>
          </div>
          <a href="#rendez-vous" class="btn btn-navy btn-block">Prendre RDV</a>
        </div>
        <div class="doc-card">
          <div class="doc-av">👨‍⚕️</div>
          <div class="doc-name">Dr. Marc Lefevre</div>
          <div class="doc-spec">Neurologie</div>
          <div class="doc-rating">
            <span class="stars">★★★★☆</span>
            <strong>4.7</strong>
            <span>(84 avis)</span>
          </div>
          <a href="#rendez-vous" class="btn btn-navy btn-block">Prendre RDV</a>
        </div>
        <div class="doc-card">
          <div class="doc-av">👩‍⚕️</div>
          <div class="doc-name">Dr. Claire Nguyen</div>
          <div class="doc-spec">Dermatologie</div>
          <div class="doc-rating">
            <span class="stars">★★★★★</span>
            <strong>4.9</strong>
            <span>(156 avis)</span>
          </div>
          <a href="#rendez-vous" class="btn btn-navy btn-block">Prendre RDV</a>
        </div>
      </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer id="contact">
      <div class="footer-grid">
        <div>
          <span class="footer-brand-name">MediCare+</span>
          <p class="footer-desc">
            Votre portail de santé numérique — sécurisé, simple et accessible 24h/24.
            Votre santé est notre priorité.
          </p>
        </div>
        <div class="fc">
          <h4>Services</h4>
          <ul>
            <li><a href="#">Consultations</a></li>
            <li><a href="#">Ordonnances</a></li>
            <li><a href="#">Résultats</a></li>
            <li><a href="#">Télémédecine</a></li>
          </ul>
        </div>
        <div class="fc">
          <h4>Aide</h4>
          <ul>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Support</a></li>
            <li><a href="#">Mon compte</a></li>
            <li><a href="#">Confidentialité</a></li>
          </ul>
        </div>
        <div class="fc">
          <h4>Contact</h4>
          <ul>
            <li><a href="tel:+22507000000">+225 07 00 00 00</a></li>
            <li><a href="mailto:contact@medicare.ci">contact@medicare.ci</a></li>
            <li><a href="#">Abidjan, Côte d'Ivoire</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <span>&copy; 2026 MediCare+. Tous droits réservés.</span>
        <span>Fait avec ❤️ pour votre santé</span>
      </div>
    </footer>

  </div><!-- end .main-content -->
</div><!-- end .layout -->

<!-- ================= JS ================= -->
<script>
  const dateInput = document.getElementById('date_rdv');
  const today = new Date().toISOString().split('T')[0];
  dateInput.setAttribute('min', today);

  document.getElementById('rdv-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const prenom     = document.getElementById('prenom').value.trim();
    const nom        = document.getElementById('nom').value.trim();
    const telephone  = document.getElementById('telephone').value.trim();
    const specialite = document.getElementById('specialite').value;
    const dateRdv    = document.getElementById('date_rdv').value;
    const telRegex   = /^\+?[0-9\s\-]{7,20}$/;
    const alertSuccess = document.getElementById('alert-success');
    const alertError   = document.getElementById('alert-error');

    alertSuccess.classList.add('hidden');
    alertError.classList.add('hidden');

    if (!prenom || !nom || !telephone || !specialite || !dateRdv) {
      alertError.textContent = '⚠️ Veuillez remplir tous les champs du formulaire.';
      alertError.classList.remove('hidden');
      return;
    }
    if (!telRegex.test(telephone)) {
      alertError.textContent = '⚠️ Numéro de téléphone invalide.';
      alertError.classList.remove('hidden');
      return;
    }
    alertSuccess.textContent =
      `✅ Merci ${prenom} ! Votre demande en ${specialite} le ${dateRdv} est bien enregistrée. Nous vous contacterons sous 24 h.`;
    alertSuccess.classList.remove('hidden');
    this.reset();
  });

  function Suivant(section, el) {
    // Placeholder pour la navigation
    console.log('Navigation vers :', section);
  }
</script>

</body>
</html>