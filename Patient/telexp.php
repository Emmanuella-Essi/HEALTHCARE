<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consultation Patient</title>
  <link rel="stylesheet" href="../css/telexp.css"> <!-- Ton fichier CSS -->
  <link rel="stylesheet" href="https://ct-awesome/7.0.1/css/all.min.cssdnjs.cloudflare.com/ajax/libs/fon">
</head>
<body>
  <section class="patient-section">
    
    <!-- Barre supérieure -->
    <div class="top-bar">
      <h2>Consultation en ligne</h2>
    </div>

    <!-- Zone de contenu -->
    <div class="content-area">
      <div class="grid-32">
        
        <!-- Colonne principale -->
        <div>
          <!-- Carte vidéo -->
          <div class="card mb-24">
            <div class="card-header">
              <span class="card-title">Vidéo Consultation</span>
              <span class="badge badge-green">En direct</span>
            </div>
            <div class="card-body">
              <div class="video-consultation">
                <div class="video-bg">
                  <span class="video-avatar">👨‍⚕️</span>
                </div>
                <div class="video-overlay">
                  <span class="video-timer">00:12:45</span>
                  <div class="video-controls">
                    <button class="video-btn">🎤</button>
                    <button class="video-btn">🎥</button>
                    <button class="video-btn end">📞</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Carte chat -->
          <div class="card mb-24">
            <div class="card-header">
              <span class="card-title">Messagerie</span>
            </div>
            <div class="card-body">
              <div class="chat-container">
                <div class="chat-messages">
                  <div class="msg msg-in">
                    <div class="msg-bubble">Bonjour, comment vous sentez-vous ?</div>
                    <div class="msg-time">15:45</div>
                  </div>
                  <div class="msg msg-out">
                    <div class="msg-bubble">J’ai encore des douleurs au ventre.</div>
                    <div class="msg-time">15:46</div>
                  </div>
                </div>
                <div class="chat-input">
                  <input type="text" placeholder="Écrire un message...">
                  <button class="chat-send">➤</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Colonne latérale -->
        <div>
          <!-- Informations médecin -->
          <div class="card mb-24">
            <div class="card-header">
              <span class="card-title">Médecin</span>
            </div>
            <div class="card-body">
              <div class="avatar avatar-teal">DR</div>
              <p class="text-muted">Dr. Sangare — Pédiatre</p>
              <div class="divider"></div>
              <div class="info-motif">
                <strong>Motif :</strong> Douleurs abdominales persistantes
              </div>
            </div>
          </div>

          <!-- Formulaire compte-rendu -->
          <div class="card">
            <div class="card-header">
              <span class="card-title">Compte-rendu médical</span>
            </div>
            <div class="card-body">
              <form>
                <div class="form-group">
                  <label class="form-label">Observations</label>
                  <textarea class="form-textarea"></textarea>
                </div>
                <button type="submit" class="btn-submit">Enregistrer</button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</body>
</html>
