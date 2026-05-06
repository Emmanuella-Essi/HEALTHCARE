<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier medical</title>
    <link rel="stylesheet" href="../css/dossier.css">
    <link rel="stylesheet" href="https://ct-awesome/7.0.1/css/all.min.cssdnjs.cloudflare.com/ajax/libs/fon">
</head>
<body>
    
<!-- MAIN -->
  <main class="main-content">

    <!-- TOP BAR -->
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <div class="page-title-block">
          <h1 class="page-title">Dossier Médical</h1>
          <p class="page-subtitle">Historique complet de votre santé</p>
        </div>
      </div>
      <div class="topbar-right">
        <div class="search-bar">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          <input type="text" placeholder="Rechercher une entrée..." id="searchInput" />
        </div>
        <div class="notif-btn" id="notifBtn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <span class="notif-dot"></span>
        </div>
      </div>
    </header>

    <!-- PAGE BODY -->
    <div class="page-body">

      <!-- RÉSUMÉ MÉDICAL -->
      <section class="summary-section">
        <div class="summary-card blood">
          <div class="summary-icon">🩸</div>
          <div class="summary-info">
            <span class="summary-label">Groupe Sanguin</span>
            <span class="summary-value">A+</span>
          </div>
        </div>
        <div class="summary-card allergy">
          <div class="summary-icon">⚠️</div>
          <div class="summary-info">
            <span class="summary-label">Allergies</span>
            <span class="summary-value">2 connues</span>
          </div>
        </div>
        <div class="summary-card entries">
          <div class="summary-icon">📋</div>
          <div class="summary-info">
            <span class="summary-label">Entrées totales</span>
            <span class="summary-value" id="totalEntries">5</span>
          </div>
        </div>
        <div class="summary-card last">
          <div class="summary-icon">📅</div>
          <div class="summary-info">
            <span class="summary-label">Dernière entrée</span>
            <span class="summary-value">02 Mai 2025</span>
          </div>
        </div>
      </section>

      <!-- FILTRES + BOUTON AJOUTER -->
      <section class="controls-section">
        <div class="filters">
          <button class="filter-btn active" data-filter="tous">Tous</button>
          <button class="filter-btn" data-filter="consultation">Consultation</button>
          <button class="filter-btn" data-filter="traitement">Traitement</button>
          <button class="filter-btn" data-filter="analyse">Analyse</button>
          <button class="filter-btn" data-filter="chirurgie">Chirurgie</button>
        </div>
        <button class="add-btn" id="openModal">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Nouvelle entrée
        </button>
      </section>

      <!-- TIMELINE ENTRÉES -->
      <section class="timeline-section">
        <div class="timeline" id="timeline">

          <!-- ENTRÉE 1 -->
          <div class="timeline-item" data-type="consultation" data-id="1">
            <div class="timeline-dot consultation"></div>
            <div class="timeline-card">
              <div class="card-header">
                <div class="card-meta">
                  <span class="badge consultation">Consultation</span>
                  <span class="card-date">02 Mai 2025</span>
                </div>
                <div class="card-actions">
                  <button class="action-icon edit" onclick="editEntry(1)" title="Modifier">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button class="action-icon delete" onclick="deleteEntry(1)" title="Supprimer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/>
                      <path d="M9 6V4h6v2"/>
                    </svg>
                  </button>
                </div>
              </div>
              <h3 class="card-title">Consultation générale — Dr. Kouamé</h3>
              <p class="card-desc">Examen de routine. Tension artérielle normale (120/80). Aucune anomalie détectée. Prescription vitamines D3 recommandée.</p>
              <div class="card-tags">
                <span class="tag">Tension: 120/80</span>
                <span class="tag">Poids: 68 kg</span>
                <span class="tag">Dr. Kouamé</span>
              </div>
            </div>
          </div>

          <!-- ENTRÉE 2 -->
          <div class="timeline-item" data-type="analyse" data-id="2">
            <div class="timeline-dot analyse"></div>
            <div class="timeline-card">
              <div class="card-header">
                <div class="card-meta">
                  <span class="badge analyse">Analyse</span>
                  <span class="card-date">15 Avril 2025</span>
                </div>
                <div class="card-actions">
                  <button class="action-icon edit" onclick="editEntry(2)" title="Modifier">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button class="action-icon delete" onclick="deleteEntry(2)" title="Supprimer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/>
                      <path d="M9 6V4h6v2"/>
                    </svg>
                  </button>
                </div>
              </div>
              <h3 class="card-title">Bilan sanguin complet</h3>
              <p class="card-desc">NFS, glycémie, cholestérol, bilan hépatique. Résultats dans les normes. Légère carence en fer signalée — fer + vitamines prescrits.</p>
              <div class="card-tags">
                <span class="tag">Glycémie: 0.95 g/L</span>
                <span class="tag">Hémoglobine: 12.1</span>
                <span class="tag">Labo Central</span>
              </div>
            </div>
          </div>

          <!-- ENTRÉE 3 -->
          <div class="timeline-item" data-type="traitement" data-id="3">
            <div class="timeline-dot traitement"></div>
            <div class="timeline-card">
              <div class="card-header">
                <div class="card-meta">
                  <span class="badge traitement">Traitement</span>
                  <span class="card-date">10 Mars 2025</span>
                </div>
                <div class="card-actions">
                  <button class="action-icon edit" onclick="editEntry(3)" title="Modifier">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button class="action-icon delete" onclick="deleteEntry(3)" title="Supprimer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/>
                      <path d="M9 6V4h6v2"/>
                    </svg>
                  </button>
                </div>
              </div>
              <h3 class="card-title">Traitement antipaludéen — Coartem</h3>
              <p class="card-desc">Paludisme simple diagnostiqué. Traitement Coartem 6 comprimés sur 3 jours. Récupération complète après 5 jours.</p>
              <div class="card-tags">
                <span class="tag">Durée: 3 jours</span>
                <span class="tag">Coartem 80mg</span>
                <span class="tag">Guéri ✓</span>
              </div>
            </div>
          </div>

          <!-- ENTRÉE 4 -->
          <div class="timeline-item" data-type="consultation" data-id="4">
            <div class="timeline-dot consultation"></div>
            <div class="timeline-card">
              <div class="card-header">
                <div class="card-meta">
                  <span class="badge consultation">Consultation</span>
                  <span class="card-date">22 Janvier 2025</span>
                </div>
                <div class="card-actions">
                  <button class="action-icon edit" onclick="editEntry(4)" title="Modifier">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button class="action-icon delete" onclick="deleteEntry(4)" title="Supprimer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/>
                      <path d="M9 6V4h6v2"/>
                    </svg>
                  </button>
                </div>
              </div>
              <h3 class="card-title">Consultation ORL — Dr. Assi</h3>
              <p class="card-desc">Douleur auriculaire gauche. Otite externe diagnostiquée. Gouttes auriculaires Otipax prescrites. Suivi à 10 jours.</p>
              <div class="card-tags">
                <span class="tag">Otite externe</span>
                <span class="tag">Otipax gouttes</span>
                <span class="tag">Résolu ✓</span>
              </div>
            </div>
          </div>

          <!-- ENTRÉE 5 -->
          <div class="timeline-item" data-type="chirurgie" data-id="5">
            <div class="timeline-dot chirurgie"></div>
            <div class="timeline-card">
              <div class="card-header">
                <div class="card-meta">
                  <span class="badge chirurgie">Chirurgie</span>
                  <span class="card-date">08 Juin 2024</span>
                </div>
                <div class="card-actions">
                  <button class="action-icon edit" onclick="editEntry(5)" title="Modifier">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button class="action-icon delete" onclick="deleteEntry(5)" title="Supprimer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/>
                      <path d="M10 11v6"/><path d="M14 11v6"/>
                      <path d="M9 6V4h6v2"/>
                    </svg>
                  </button>
                </div>
              </div>
              <h3 class="card-title">Appendicectomie — CHU Treichville</h3>
              <p class="card-desc">Appendicite aiguë opérée en urgence. Intervention laparoscopique réussie. Hospitalisation 3 jours. Convalescence totale.</p>
              <div class="card-tags">
                <span class="tag">Laparoscopie</span>
                <span class="tag">3 jours hospit.</span>
                <span class="tag">CHU Treichville</span>
              </div>
            </div>
          </div>

        </div>
      </section>

    </div><!-- end page-body -->
  </main>

  <!-- MODAL AJOUT / MODIFICATION -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal" id="modal">
      <div class="modal-header">
        <h2 class="modal-title" id="modalTitle">Nouvelle Entrée Médicale</h2>
        <button class="modal-close" id="closeModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editId" value="" />
        <div class="form-row">
          <div class="form-group">
            <label for="entryType">Type d'entrée</label>
            <select id="entryType">
              <option value="consultation">Consultation</option>
              <option value="traitement">Traitement</option>
              <option value="analyse">Analyse</option>
              <option value="chirurgie">Chirurgie</option>
            </select>
          </div>
          <div class="form-group">
            <label for="entryDate">Date</label>
            <input type="date" id="entryDate" />
          </div>
        </div>
        <div class="form-group">
          <label for="entryTitle">Titre / Motif</label>
          <input type="text" id="entryTitle" placeholder="Ex: Consultation générale — Dr. Koné" />
        </div>
        <div class="form-group">
          <label for="entryDesc">Description</label>
          <textarea id="entryDesc" rows="4" placeholder="Décrivez le diagnostic, les symptômes, les résultats..."></textarea>
        </div>
        <div class="form-group">
          <label for="entryTags">Tags (séparés par des virgules)</label>
          <input type="text" id="entryTags" placeholder="Ex: Tension: 120/80, Poids: 70kg, Dr. Koné" />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-cancel" id="cancelModal">Annuler</button>
        <button class="btn-save" id="saveEntry">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20,6 9,17 4,12"/>
          </svg>
          Enregistrer
        </button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast" id="toast">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <polyline points="20,6 9,17 4,12"/>
    </svg>
    <span id="toastMsg">Entrée enregistrée avec succès</span>
  </div>

</body>
</html>