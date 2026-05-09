<?php
// ============================================================
// traitement_patient.php — Traitement COMPLET des formulaires patients
// Gère : ajouter, modifier, supprimer, changer statut
// Toutes les opérations qui modifient la table patients
// ============================================================

require_once 'config.php';

// ---- En-têtes JSON + auth ----
header('Content-Type: application/json; charset=UTF-8');
$medecinId = requireAuth(true); // Vérifie session, retourne ID médecin
$pdo       = getDb();

// ---- Lecture du corps JSON ----
$raw  = file_get_contents('php://input');    // Corps brut de la requête
$data = json_decode($raw, true) ?? [];       // Décode JSON → tableau PHP
$action = clean($_GET['action'] ?? $data['action'] ?? ''); // Action demandée

// ============================================================
// ROUTEUR — Dispatch selon l'action
// ============================================================
switch ($action) {
    case 'ajouter':   ajouterPatient($pdo, $medecinId, $data);   break;
    case 'modifier':  modifierPatient($pdo, $medecinId, $data);  break;
    case 'supprimer': supprimerPatient($pdo, $medecinId, $data); break;
    case 'statut':    changerStatut($pdo, $medecinId, $data);    break;
    default:          jsonError(400, "Action '$action' inconnue.");
}

// ============================================================
// AJOUTER UN PATIENT
// POST body: {prenom, nom, date_naissance, sexe, email?, telephone?, groupe_sanguin?, allergies?}
// ============================================================
function ajouterPatient(PDO $pdo, int $mid, array $d): void {
    // Extraction et nettoyage des champs
    $prenom  = clean($d['prenom']         ?? '');
    $nom     = clean($d['nom']            ?? '');
    $email   = clean($d['email']          ?? '');
    $tel     = clean($d['telephone']      ?? '');
    $dob     = clean($d['date_naissance'] ?? '');
    $sexe    = clean($d['sexe']           ?? '');
    $blood   = clean($d['groupe_sanguin'] ?? '');
    $allerg  = clean($d['allergies']      ?? 'Aucune');
    $adresse = clean($d['adresse']        ?? '');
    $urgence = clean($d['contact_urgence']?? '');
    $telUrg  = clean($d['tel_urgence']    ?? '');

    // ---- Validation ----
    $errors = [];
    if (empty($prenom))             $errors[] = "Prénom obligatoire.";
    if (empty($nom))                $errors[] = "Nom obligatoire.";
    if (empty($dob) || !isDate($dob)) $errors[] = "Date de naissance invalide (YYYY-MM-DD).";
    if (!in_array($sexe, ['M','F'])) $errors[] = "Sexe invalide (M ou F).";
    if (!empty($email) && !isEmail($email)) $errors[] = "Email invalide.";

    $bloods = ['A+','A-','B+','B-','AB+','AB-','O+','O-',''];
    if (!in_array($blood, $bloods)) $errors[] = "Groupe sanguin invalide.";

    if (!empty($errors)) jsonError(422, implode(' | ', $errors));

    // ---- Vérifie unicité de l'email ----
    if (!empty($email)) {
        $chk = $pdo->prepare("SELECT id FROM patients WHERE email=:e AND supprime=0 LIMIT 1");
        $chk->execute([':e'=>$email]);
        if ($chk->fetch()) jsonError(409, "Cet email est déjà utilisé pour un patient.");
    }

    // ---- Insertion ----
    $sql = "
        INSERT INTO patients
            (medecin_id, prenom, nom, email, telephone, date_naissance, sexe,
             groupe_sanguin, allergies, adresse, contact_urgence, tel_urgence, statut, created_at)
        VALUES
            (:mid, :prenom, :nom, :email, :tel, :dob, :sexe,
             :blood, :allerg, :adresse, :urgence, :telUrg, 'Actif', NOW())
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':mid'     => $mid,
        ':prenom'  => $prenom,
        ':nom'     => $nom,
        ':email'   => $email   ?: null,
        ':tel'     => $tel     ?: null,
        ':dob'     => $dob,
        ':sexe'    => $sexe,
        ':blood'   => $blood   ?: null,
        ':allerg'  => $allerg,
        ':adresse' => $adresse ?: null,
        ':urgence' => $urgence ?: null,
        ':telUrg'  => $telUrg  ?: null,
    ]);

    $newId = (int) $pdo->lastInsertId();
    jsonSuccess(
        ['patient_id' => $newId, 'nom_complet' => "$prenom $nom"],
        "Patient $prenom $nom enregistré avec succès."
    );
}

// ============================================================
// MODIFIER UN PATIENT
// PUT body: {patient_id, prenom?, nom?, email?, telephone?, groupe_sanguin?, allergies?, adresse?, statut?}
// ============================================================
function modifierPatient(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    if ($pid <= 0) jsonError(400, "patient_id invalide.");

    // Vérifie que ce patient appartient à ce médecin
    if (!patientAppartient($pdo, $pid, $mid)) jsonError(403, "Accès refusé.");

    // ---- Construction dynamique des colonnes à modifier ----
    $sets   = [];  // Clauses SET : "colonne = :param"
    $params = [':pid' => $pid]; // Paramètres PDO

    // Mappe les champs autorisés → noms de colonnes SQL
    $allowed = [
        'prenom'          => 'prenom',
        'nom'             => 'nom',
        'email'           => 'email',
        'telephone'       => 'telephone',
        'groupe_sanguin'  => 'groupe_sanguin',
        'allergies'       => 'allergies',
        'adresse'         => 'adresse',
        'contact_urgence' => 'contact_urgence',
        'tel_urgence'     => 'tel_urgence',
        'statut'          => 'statut',
    ];

    foreach ($allowed as $field => $col) {
        if (array_key_exists($field, $d) && $d[$field] !== '') {
            $key          = ":$field";
            $sets[]       = "$col = $key";
            $params[$key] = clean($d[$field]);
        }
    }

    if (empty($sets)) jsonError(400, "Aucun champ à modifier.");

    // Validation spécifique
    if (isset($params[':email']) && !isEmail($params[':email'])) {
        jsonError(422, "Email invalide.");
    }

    $statuts = ['Actif','En observation','Inactif'];
    if (isset($params[':statut']) && !in_array($params[':statut'], $statuts)) {
        jsonError(422, "Statut invalide.");
    }

    $sets[] = "updated_at = NOW()"; // Timestamp modification automatique

    $sql  = "UPDATE patients SET " . implode(", ", $sets) . " WHERE id = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    jsonSuccess(['patient_id' => $pid], "Patient mis à jour.");
}

// ============================================================
// SUPPRIMER UN PATIENT (soft delete)
// DELETE body: {patient_id}
// ============================================================
function supprimerPatient(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    if ($pid <= 0) jsonError(400, "patient_id invalide.");
    if (!patientAppartient($pdo, $pid, $mid)) jsonError(403, "Accès refusé.");

    // Soft delete : marque supprime=1 (conserve les données médicales)
    $pdo->prepare("UPDATE patients SET supprime=1, supprime_at=NOW() WHERE id=:pid")
        ->execute([':pid' => $pid]);

    jsonSuccess(['patient_id' => $pid], "Patient archivé.");
}

// ============================================================
// CHANGER LE STATUT RAPIDEMENT
// POST body: {patient_id, statut}
// ============================================================
function changerStatut(PDO $pdo, int $mid, array $d): void {
    $pid    = (int)($d['patient_id'] ?? 0);
    $statut = clean($d['statut'] ?? '');

    if ($pid <= 0) jsonError(400, "patient_id invalide.");
    if (!patientAppartient($pdo, $pid, $mid)) jsonError(403, "Accès refusé.");

    $valid = ['Actif','En observation','Inactif'];
    if (!in_array($statut, $valid)) jsonError(422, "Statut invalide.");

    $pdo->prepare("UPDATE patients SET statut=:s, updated_at=NOW() WHERE id=:pid")
        ->execute([':s'=>$statut, ':pid'=>$pid]);

    jsonSuccess(['patient_id'=>$pid,'statut'=>$statut], "Statut mis à jour : $statut");
}

// ---- Vérifie qu'un patient appartient au médecin ----
function patientAppartient(PDO $pdo, int $pid, int $mid): bool {
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE id=:pid AND medecin_id=:mid AND supprime=0 LIMIT 1");
    $stmt->execute([':pid'=>$pid,':mid'=>$mid]);
    return (bool)$stmt->fetch();
}