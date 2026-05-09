<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Télé-expertise — HealthCare</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/telexp.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<!-- SIDEBAR -->
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

    <a href="accueil.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5z"/>
          <polyline points="9 21 9 12 15 12 15 21"/>
        </svg>
      </div>
      <span class="ni-label">Tableau de bord</span>
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

    <a href="telexp.php" class="nav-item active">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="23 7 16 12 23 17 23 7"/>
          <rect x="1" y="5" width="15" height="14" rx="2"/>
        </svg>
      </div>
      <span class="ni-label">Télé-expertise</span>
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

    <a href="profil.php" class="nav-item">
      <div class="ni-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="4"/>
          <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
        </svg>
      </div>
      <span class="ni-label">Mon profil</span>
    </a>
  </nav>

  <div class="sb-footer">
onclick="window.location.href='../Accueil/home.php'"
      <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      <span class="deconnect-label">Déconnexion</span>
    </div>
    <div class="sb-user">
      <div class="avatar">KD</div>
      <div class="user-info">
        <div class="user-name">Kofi Doe</div>
        <div class="user-role">Patient</div>
      </div>
    </div>
  </div>
</aside>

<!-- PAGE WRAPPER -->
<div class="page-wrapper">

  <!-- TOP BAR -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="logo">Health<span>Care</span></div>
      <div class="divider-v"></div>
      <span class="page-title">Télé-expertise</span>
    </div>
    <div class="topbar-right">
      <div class="badge-live"><span class="dot-live"></span>En direct</div>
      <button class="btn-exit" onclick="showEndCallModal()">
        <i class="fa fa-sign-out-alt"></i> Quitter
      </button>
    </div>
  </header>

  <!-- MAIN LAYOUT -->
  <main class="main">

    <!-- COLONNE PRINCIPALE -->
    <div>

      <!-- VIDÉO -->
      <div class="card">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-icon"><i class="fa fa-video"></i></div>
            <span class="card-title">Flux vidéo</span>
          </div>
          <div class="badge-live" style="font-size:0.7rem;padding:4px 10px;">
            <span class="dot-live"></span>HD · 1080p
          </div>
        </div>
        <div class="card-body" style="padding:16px;">
          <div class="video-wrap" id="videoWrap">
            <div class="video-bg" id="videoBg">
              <div class="video-avatar-circle" id="docAvatar">👨‍⚕️</div>
              <span class="video-doc-name" id="docVideoName">Dr. Sangare</span>
            </div>

            <!-- Miniature patient -->
            <div class="video-self" title="Cliquer pour agrandir">
              <span>🧑</span>
              <span class="video-self-label">Vous</span>
            </div>

            <!-- Contrôles -->
            <div class="video-overlay">
              <div class="video-timer">
                <span class="timer-dot"></span>
                <span id="timerDisplay">00:00:00</span>
              </div>
              <div class="video-controls">
                <button class="vbtn" title="Micro" id="micBtn"><i class="fa fa-microphone"></i></button>
                <button class="vbtn" title="Caméra" id="camBtn"><i class="fa fa-camera"></i></button>
                <button class="vbtn" title="Partager l'écran" id="shareBtn"><i class="fa fa-desktop"></i></button>
                <button class="vbtn end-call" title="Raccrocher" onclick="showEndCallModal()">
                  <i class="fa fa-phone-slash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- MESSAGERIE -->
      <div class="card">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-icon"><i class="fa fa-comment-dots"></i></div>
            <span class="card-title">Messagerie instantanée</span>
          </div>
          <span style="font-size:0.72rem;color:var(--text-dim);" id="typingStatus"></span>
        </div>
        <div class="card-body">
          <div class="chat-messages" id="chatMessages">
            <div class="msg msg-in">
              <div class="msg-sender">Dr. Sangare</div>
              <div class="msg-bubble">Bonjour ! Comment vous sentez-vous aujourd'hui ?</div>
              <div class="msg-time">15:42</div>
            </div>
            <div class="msg msg-out">
              <div class="msg-sender">Vous</div>
              <div class="msg-bubble">Bonjour Docteur. J'ai encore des douleurs au ventre depuis hier soir.</div>
              <div class="msg-time">15:43</div>
            </div>
            <div class="msg msg-in">
              <div class="msg-sender">Dr. Sangare</div>
              <div class="msg-bubble">Je vois. Ces douleurs sont-elles localisées ou diffuses ? Sur une échelle de 1 à 10 ?</div>
              <div class="msg-time">15:45</div>
            </div>
          </div>
          <div class="chat-input-wrap">
            <input class="chat-input" type="text" id="msgInput" placeholder="Écrire un message...">
            <button class="chat-send" onclick="sendMessage()" title="Envoyer">
              <i class="fa fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- COLONNE LATÉRALE -->
    <div>

      <!-- MÉDECIN -->
      <div class="card">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-icon"><i class="fa fa-user-md"></i></div>
            <span class="card-title">Informations</span>
          </div>
        </div>
        <div class="card-body">
          <div class="doc-info">
            <div class="doc-avatar">DS</div>
            <div>
              <div class="doc-name">Dr. Sangare Moussa</div>
              <div class="doc-spec">Pédiatre · 12 ans d'expérience</div>
              <div class="doc-status"><span class="dot-live"></span> En ligne</div>
            </div>
          </div>

          <div class="info-row">
            <div class="info-icon"><i class="fa fa-calendar"></i></div>
            <div>
              <div class="info-label">Date</div>
              <div class="info-value" id="currentDate">—</div>
            </div>
          </div>
          <div class="info-row">
            <div class="info-icon"><i class="fa fa-clock"></i></div>
            <div>
              <div class="info-label">Heure de début</div>
              <div class="info-value">15:30</div>
            </div>
          </div>
          <div class="info-row">
            <div class="info-icon"><i class="fa fa-user"></i></div>
            <div>
              <div class="info-label">Patient</div>
              <div class="info-value">Eunice K. — 28 ans</div>
            </div>
          </div>

          <div class="motif-block">
            <div class="motif-label"><i class="fa fa-notes-medical"></i> Motif de consultation</div>
            <div class="motif-text">Douleurs abdominales persistantes depuis 48h, sans fièvre associée.</div>
          </div>
        </div>
      </div>

      <!-- COMPTE-RENDU -->
      <div class="card">
        <div class="card-head">
          <div class="card-head-left">
            <div class="card-icon"><i class="fa fa-file-medical"></i></div>
            <span class="card-title">Compte-rendu médical</span>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label"><i class="fa fa-stethoscope"></i> Observations cliniques</label>
            <textarea class="form-textarea" id="observations" placeholder="Saisir vos observations..." oninput="updateCounter(this, 'obsCount')"></textarea>
            <div class="char-counter"><span id="obsCount">0</span> caractères</div>
          </div>
          <div class="form-group">
            <label class="form-label"><i class="fa fa-prescription"></i> Prescription / Traitement</label>
            <textarea class="form-textarea" id="prescription" style="min-height:80px;" placeholder="Médicaments, posologie..." oninput="updateCounter(this, 'prescCount')"></textarea>
            <div class="char-counter"><span id="prescCount">0</span> caractères</div>
          </div>
          <button class="btn-primary" id="saveBtn" onclick="saveReport()">
            <i class="fa fa-save"></i> Enregistrer le compte-rendu
          </button>
          <button class="btn-secondary" onclick="generatePDF()">
            <i class="fa fa-print"></i> Générer l'ordonnance PDF
          </button>
        </div>
      </div>

    </div>
  </main>

</div><!-- /page-wrapper -->

<!-- MODAL FIN D'APPEL -->
<div class="modal-overlay" id="endCallModal">
  <div class="modal">
    <div class="modal-icon"><i class="fa fa-phone-slash"></i></div>
    <h3>Terminer la consultation ?</h3>
    <p>La session sera clôturée et le compte-rendu sera sauvegardé automatiquement.</p>
    <div class="modal-actions">
      <button class="modal-cancel" onclick="closeModal()">Annuler</button>
      <button class="modal-confirm" onclick="endCall()">Terminer</button>
    </div>
  </div>
</div>

<!-- TOAST NOTIFICATION -->
<div class="toast" id="toast">
  <span class="toast-icon" id="toastIcon">✅</span>
  <span id="toastMsg">Action effectuée</span>
</div>

<script>
  /* ─── TIMER ─── */
  let seconds = 0;
  const timerInterval = setInterval(() => {
    seconds++;
    const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
    const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    document.getElementById('timerDisplay').textContent = `${h}:${m}:${s}`;
  }, 1000);

  /* ─── DATE ─── */
  const now = new Date();
  document.getElementById('currentDate').textContent = now.toLocaleDateString('fr-FR', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
  });

  /* ─── TOAST ─── */
  function showToast(msg, icon = '✅') {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    document.getElementById('toastIcon').textContent = icon;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
  }

  /* ─── CHAT ─── */
  const drReplies = [
    "Je comprends. Avez-vous pris des antalgiques ?",
    "D'accord, pouvez-vous préciser depuis combien de temps exactement ?",
    "Notez bien ces symptômes, je vais faire une ordonnance.",
    "Très bien. Je vous recommande de rester hydraté(e).",
    "Je vais regarder votre dossier pour adapter le traitement."
  ];
  let replyIndex = 0;
  let typingTimeout = null;

  function sendMessage() {
    const input = document.getElementById('msgInput');
    const text = input.value.trim();
    if (!text) return;

    const chat = document.getElementById('chatMessages');
    const time = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

    const msg = document.createElement('div');
    msg.className = 'msg msg-out';
    msg.innerHTML = `
      <div class="msg-sender">Vous</div>
      <div class="msg-bubble">${escapeHtml(text)}</div>
      <div class="msg-time">${time}</div>
    `;
    chat.appendChild(msg);
    chat.scrollTop = chat.scrollHeight;
    input.value = '';

    // Typing indicator
    clearTimeout(typingTimeout);
    const status = document.getElementById('typingStatus');
    status.textContent = 'Dr. Sangare écrit…';

    const typing = document.createElement('div');
    typing.className = 'msg msg-in';
    typing.id = 'typingMsg';
    typing.innerHTML = `
      <div class="msg-sender">Dr. Sangare</div>
      <div class="typing-indicator"><span></span><span></span><span></span></div>
    `;
    chat.appendChild(typing);
    chat.scrollTop = chat.scrollHeight;

    typingTimeout = setTimeout(() => {
      const el = document.getElementById('typingMsg');
      if (el) el.remove();
      status.textContent = '';

      const replyTime = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
      const reply = document.createElement('div');
      reply.className = 'msg msg-in';
      reply.innerHTML = `
        <div class="msg-sender">Dr. Sangare</div>
        <div class="msg-bubble">${drReplies[replyIndex % drReplies.length]}</div>
        <div class="msg-time">${replyTime}</div>
      `;
      chat.appendChild(reply);
      chat.scrollTop = chat.scrollHeight;
      replyIndex++;
    }, 1800);
  }

  function escapeHtml(text) {
    return text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  document.getElementById('msgInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') sendMessage();
  });

  /* ─── MIC / CAM ─── */
  document.getElementById('micBtn').addEventListener('click', function() {
    this.classList.toggle('muted');
    const icon = this.querySelector('i');
    const muted = this.classList.contains('muted');
    icon.className = muted ? 'fa fa-microphone-slash' : 'fa fa-microphone';
    showToast(muted ? 'Microphone coupé' : 'Microphone activé', muted ? '🔇' : '🎤');
  });

  document.getElementById('camBtn').addEventListener('click', function() {
    this.classList.toggle('muted');
    const icon = this.querySelector('i');
    const muted = this.classList.contains('muted');
    icon.className = muted ? 'fa fa-video-slash' : 'fa fa-camera';
    // Simule caméra coupée
    document.getElementById('videoBg').style.filter = muted ? 'brightness(0.3)' : 'brightness(1)';
    showToast(muted ? 'Caméra désactivée' : 'Caméra activée', muted ? '📵' : '📹');
  });

  document.getElementById('shareBtn').addEventListener('click', function() {
    this.classList.toggle('muted');
    showToast('Partage d\'écran ' + (this.classList.contains('muted') ? 'activé' : 'désactivé'), '🖥️');
  });

  /* ─── CHAR COUNTER ─── */
  function updateCounter(el, counterId) {
    document.getElementById(counterId).textContent = el.value.length;
  }

  /* ─── SAVE REPORT ─── */
  function saveReport() {
    const obs = document.getElementById('observations').value.trim();
    const presc = document.getElementById('prescription').value.trim();
    if (!obs && !presc) {
      showToast('Veuillez saisir au moins un champ', '⚠️');
      return;
    }
    const btn = document.getElementById('saveBtn');
    btn.classList.add('saved');
    btn.innerHTML = '<i class="fa fa-check"></i> Enregistré !';
    showToast('Compte-rendu enregistré avec succès', '✅');
    setTimeout(() => {
      btn.classList.remove('saved');
      btn.innerHTML = '<i class="fa fa-save"></i> Enregistrer le compte-rendu';
    }, 3000);
  }

  /* ─── GENERATE PDF ─── */
  function generatePDF() {
    showToast('Génération de l\'ordonnance en cours…', '📄');
  }

  /* ─── MODAL FIN D'APPEL ─── */
  function showEndCallModal() {
    document.getElementById('endCallModal').classList.add('visible');
  }

  function closeModal() {
    document.getElementById('endCallModal').classList.remove('visible');
  }

  function endCall() {
    clearInterval(timerInterval);
    closeModal();
    showToast('Consultation terminée. À bientôt !', '👋');
    setTimeout(() => window.location.href = 'accueil.php', 2000);
  }

  // Fermer modal avec Echap
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
  });

  // Fermer modal en cliquant hors
  document.getElementById('endCallModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
</script>
</body>
</html>