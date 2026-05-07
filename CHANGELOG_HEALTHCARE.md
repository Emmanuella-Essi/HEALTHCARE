# CHANGELOG_HEALTHCARE.md (explication des modifications)

Ce fichier sert à **expliquer ce qui a été ajouté / modifié** dans le projet **HEALTHCARE** afin de comprendre ce qui “n’allait pas” et comment le code a été corrigé.

## 1) API JSON : routage centralisé dans `Accueil/index.php`

### Fichier concerné
- `Accueil/index.php`

### Problème (fréquent)
- Mélange d’HTML et de JSON dans un même endpoint, ce qui provoque des réponses invalides côté front.

### Modification
- Le fichier commence par du HTML (page), mais pour éviter la corruption JSON, le code PHP ajoute explicitement :
  - `header('Content-Type: application/json; charset=utf-8');`
  - logique de CORS (`Access-Control-Allow-*`)
  - et un routage basé sur l’URL après le segment `/api`.

### Routes couvertes (dans ce fichier)
- `POST /api/auth/register` → `AuthController::register()`
- `POST /api/auth/login` → `AuthController::login()`
- `POST /api/auth/logout` → `AuthController::logout()`
- `GET /api/auth/me` → `AuthController::me()`
- `GET /api/vaccins` → `VaccinationController::listVaccins()`
- `patients/{id}/vaccinations`
  - `GET` → `carnetPatient($id)`
  - `POST` → `ajouter($id)`
- `patients/{id}/rappels` → `rappels($id)`
- `patients/{id}/documents`
  - `GET` → `DocumentController::liste($id)`
  - `POST` → `DocumentController::upload($id)`
- `vaccinations/{id}` `DELETE` → `supprimer($id)`
- `vaccinations/stats` → `stats()`

> Remarque : le fichier signale aussi explicitement des endpoints non gérés (ex: consultations) avec `501`.

---

## 2) Connexion DB : config PDO réutilisable

### Fichier concerné
- `BD/config/database.php`

### Ce qui a été mis en place
- Paramétrage via variables d’environnement avec valeurs par défaut.
- DSN PDO MySQL + options sécurité/performance :
  - `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`
  - `PDO::ATTR_EMULATE_PREPARES => false`

### Utilisation
- Une classe `Database` avec un singleton `getInstance()` pour éviter de recréer les connexions.

---

## 3) Auth JWT + blacklist (logout réel)

### Fichier concerné
- `BD/middleware/auth.php`

### Problème (classique)
- Avec un JWT simple, le logout côté client n’empêche pas le token d’être encore valide tant qu’il n’expire pas.

### Modification
- Ajout d’un mécanisme de **blacklist** :
  - Génération JWT (`generateToken`)
  - Vérification JWT (`verifyToken`)
  - Requête DB sur `tokens_blacklist` pour rejeter un token révoqué.
  - `revokeToken()` pour mettre en blacklist lors du logout.

### Particularités
- Extraction header Authorization: support de `Authorization` et `authorization`.
- `requireRole()` pour restreindre les routes (medecin/admin).

---

## 4) Contrôleurs Auth : register/login/logout/me

### Fichier concerné
- `BD/controllers/authcontrollers.php`

### Ce qui a été mis en place
- Validation d’entrée avec `BD/utils/Validator.php`.
- `register()` :
  - vérifie unicité email
  - hash bcrypt du mot de passe
  - insère dans `utilisateurs`
  - puis insère automatiquement la table profil :
    - `patients` si role=patient
    - `medecins` si role=medecin
  - retourne un JWT

- `login()` :
  - vérifie email actif + password
  - renvoie un JWT + infos utilisateur

- `logout()` :
  - extrait le Bearer token
  - appelle `Auth::revokeToken()`

- `me()` :
  - récupère l’utilisateur courant depuis `utilisateurs`.

---

## 5) Contrôleur Vaccination : carnet + rappels + CRUD

### Fichier concerné
- `BD/controllers/vaccinationcontrollers.php`

### Endpoints couverts
- `listVaccins()` : `GET /api/vaccins`
- `carnetPatient($patientId)` : `GET /api/patients/{id}/vaccinations`
- `ajouter($patientId)` : `POST /api/patients/{id}/vaccinations`
  - si token role = `medecin`, récupère automatiquement `medecin_id` via `user_id`
  - insère dans `vaccinations`
  - si `date_rappel` est fournie → insère dans `rappels_vaccins`
- `supprimer($vaccinationId)` : `DELETE /api/vaccinations/{id}`
  - uniquement medecin/admin
- `rappels($patientId)` : `GET /api/patients/{id}/rappels`
  - uniquement admin/medecin (patient = interdit ici sauf accès autorisé)
  - filtre date >= CURDATE()
- `stats()` : `GET /api/vaccinations/stats` (admin)

### Contrôle d’accès patient
- `checkAccess()` empêche d’accéder au carnet d’un patient dont l’utilisateur n’est pas propriétaire.

---

## 6) Documents médicaux : upload et suppression fichier

### Fichier concerné
- `BD/Controllers/DocumentController.php`

### Problèmes typiques résolus
- Il fallait relier l’upload physique (dossier `uploads/`) à la persistance DB.
- Il fallait aussi supprimer le fichier lors du delete.

### Modification
- `liste($patientId)` : joint `documents` ↔ `utilisateurs` (uploader)
- `upload($patientId)` :
  - exige `$_FILES['fichier']` et `$_POST['titre']`
  - limite taille (10MB)
  - accepte types :
    - pdf, jpeg, png, gif
  - crée dossier : `UPLOAD_DIR/documents/{patientId}/`
  - nom fichier random via `bin2hex(random_bytes(12))`
  - insère dans `documents` avec :
    - `patient_id`, `consultation_id?`, `titre`, `type`, `fichier_url`, `taille_ko`, `uploader_id`
- `supprimer($id)` :
  - lit `fichier_url`
  - supprime le fichier physique
  - supprime l’entrée DB
  - rôle requis : medecin/admin

---

## 7) Helpers : `Response` et `Validator`

### Fichier concernés
- `BD/utils/Response.php`
- `BD/utils/Validator.php`

### `Response.php`
- Standardise les sorties JSON :
  - `success(data, code)`
  - `error(message, code)`

### `Validator.php`
- Mini validateur supportant :
  - `required`
  - `min:N`
  - `email`
  - `in:a,b,c`

---

## 8) Schéma DB : ajout des tables du domaine santé

### Fichier concerné
- `BD/database.sql`

### Ce que ça contient
- Base `healthcare`
- Tables :
  - `utilisateurs` (auth + rôle)
  - `patients`, `medecins` (profils)
  - `vaccins`
  - `vaccinations` (carnet)
  - `rappels_vaccins`
  - `consultations` (télé-expertise)
  - `messages`
  - `documents`
  - `disponibilites`
  - `tokens_blacklist`

### Pourquoi c’est important pour les corrections
- Sans schéma cohérent, les controllers JSON ne peuvent pas fonctionner.
- La présence de `tokens_blacklist` est indispensable pour le logout “réel”.

---

## 9) Front : `Patient/telexp.php` et `Patient/dossier.php`

### Fichiers concernés
- `Patient/telexp.php`
- `Patient/dossier.php`

### Ce qui a été fait
- `Patient/telexp.php` : une UI complète (video + chat + sidebar) avec un gros bloc CSS inline et du JS léger (timer + envoi message local).
- `Patient/dossier.php` : structure d’interface (sidebar + contenu), sans logique API visible dans l’extrait.

> Important : l’UI semble être une base “front-end”, mais la partie “connectée API” (messages réels, video réelle) n’est pas visible dans les extraits lus.

---

## 10) Notes de cohérence / points potentiels “qui n’allait pas”

1. **Mauvais chemins / casse de dossiers** :
   - le code référence `BD/Controllers/*` dans certains endroits
   - et `BD/controllers/*` dans d’autres.
   - Sur Windows ça passe souvent, mais sur Linux ça casse. Ici, c’est un risque.

2. **`Accueil/index.php` contient aussi du HTML** :
   - même si des headers JSON existent côté API, la présence de HTML avant le JSON peut provoquer des réponses invalides si la route API n’est pas bien isolée.

3. **Routage partiel** :
   - certaines routes (consultations, medecins CRUD…) sont explicitement indiquées comme non couvertes.

---

## Comment utiliser ce fichier
- Ce changelog décrit les modifications identifiées dans les fichiers inspectés (config DB, auth JWT, controllers vaccin/doc, routage API, DB schema).
- Si tu as des erreurs précises à corriger (ex : token invalide, upload qui échoue, route introuvable), donne-moi le message d’erreur exacte et je localiserai précisément la cause dans ces modules.

