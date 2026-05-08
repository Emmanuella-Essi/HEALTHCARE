<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tele expertise</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/telexp.css">

 
</head>
<body>

  <!-- TOP BAR -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="logo">Health<span>Care</span></div>
      <div class="divider-v"></div>
      <span class="page-title">Télé-expertise</span>
    </div>
    <div class="topbar-right">
      <div class="badge-live"><span class="dot-live"></span>En direct</div>
      <button class="btn-exit"><i class="fa fa-sign-out-alt"></i> Quitter</button>
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
          <div class="video-wrap">
            <div class="video-bg">
              <div class="video-avatar-circle">👨‍⚕️</div>
              <span class="video-doc-name">Dr. Sangare</span>
            </div>

            <!-- Miniature patient -->
            <div class="video-self">
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
                <button class="vbtn" title="Partager l'écran"><i class="fa fa-desktop"></i></button>
                <button class="vbtn end-call" title="Raccrocher"><i class="fa fa-phone-slash"></i></button>
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
            <button class="chat-send" onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
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
            <textarea class="form-textarea" placeholder="Saisir vos observations..."></textarea>
          </div>
          <div class="form-group">
            <label class="form-label"><i class="fa fa-prescription"></i> Prescription / Traitement</label>
            <textarea class="form-textarea" style="min-height:80px;" placeholder="Médicaments, posologie..."></textarea>
          </div>
          <button class="btn-primary"><i class="fa fa-save"></i> Enregistrer le compte-rendu</button>
          <button class="btn-secondary"><i class="fa fa-print"></i> Générer l'ordonnance PDF</button>
        </div>
      </div>

    </div>
  </main>

  <script>
    // Timer de consultation
    let seconds = 0;
    setInterval(() => {
      seconds++;
      const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
      const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
      const s = String(seconds % 60).padStart(2, '0');
      document.getElementById('timerDisplay').textContent = `${h}:${m}:${s}`;
    }, 1000);

    // Date actuelle
    const now = new Date();
    document.getElementById('currentDate').textContent = now.toLocaleDateString('fr-FR', {
      weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });

    // Envoyer message
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
        <div class="msg-bubble">${text}</div>
        <div class="msg-time">${time}</div>
      `;
      chat.appendChild(msg);
      chat.scrollTop = chat.scrollHeight;
      input.value = '';
    }

    document.getElementById('msgInput').addEventListener('keydown', e => {
      if (e.key === 'Enter') sendMessage();
    });

    // Toggle micro/caméra
    document.getElementById('micBtn').addEventListener('click', function() {
      this.classList.toggle('muted');
      const icon = this.querySelector('i');
      icon.className = this.classList.contains('muted') ? 'fa fa-microphone-slash' : 'fa fa-microphone';
    });

    document.getElementById('camBtn').addEventListener('click', function() {
      this.classList.toggle('muted');
      const icon = this.querySelector('i');
      icon.className = this.classList.contains('muted') ? 'fa fa-video-slash' : 'fa fa-camera';
    });
  </script>
</body>
</html>