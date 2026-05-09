<?php
// ============================================================
// traitement_dossier.php — Traitement du dossier médical
// Gère : entrées dossier, vaccins, consultations, ordonnances, signes vitaux
// ============================================================

require_once 'config.php';

header('Content-Type: application/json; charset=UTF-8');
$mid    = requireAuth(true);
$pdo    = getDb();
$raw    = file_get_contents('php://input');
$data   = json_decode($raw, true) ?? [];
$action = clean($_GET['action'] ?? $data['action'] ?? '');

// ============================================================
// ROUTEUR
// ============================================================
switch ($action) {
    // Dossier médical
    case 'ajouter_entree':    ajouterEntree($pdo, $mid, $data);        break;
    case 'modifier_entree':   modifierEntree($pdo, $mid, $data);       break;
    case 'supprimer_entree':  supprimerEntree($pdo, $mid, $data);      break;

    // Vaccins
    case 'ajouter_vaccin':    ajouterVaccin($pdo, $mid, $data);        break;
    case 'modifier_vaccin':   modifierVaccin($pdo, $mid, $data);       break;
    case 'supprimer_vaccin':  supprimerVaccin($pdo, $mid, $data);      break;

    // Consultations
    case 'ajouter_consultation': ajouterConsultation($pdo, $mid, $data); break;
    case 'modifier_consultation':modifierConsultation($pdo,$mid,$data);  break;

    // Ordonnances
    case 'creer_ordonnance':    creerOrdonnance($pdo, $mid, $data);    break;
    case 'archiver_ordonnance': archiverOrdonnance($pdo, $mid, $data); break;

    // Signes vitaux
    case 'ajouter_vitaux':    ajouterVitaux($pdo, $mid, $data);        break;

    default: jsonError(400, "Action '$action' inconnue.");
}

// ============================================================
// UTILITAIRE : Vérifie accès au patient
// ============================================================
function peutAccederPatient(PDO $pdo, int $pid, int $mid): bool {
    $s = $pdo->prepare("SELECT id FROM patients WHERE id=:pid AND medecin_id=:mid AND supprime=0 LIMIT 1");
    $s->execute([':pid'=>$pid,':mid'=>$mid]);
    return (bool)$s->fetch();
}

// ============================================================
// ENTRÉES DOSSIER MÉDICAL
// ============================================================
function ajouterEntree(PDO $pdo, int $mid, array $d): void {
    $pid  = (int)($d['patient_id'] ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $titre = clean($d['titre'] ?? '');
    $desc  = clean($d['description'] ?? '');
    $type  = clean($d['type'] ?? 'note');
    $date  = clean($d['date'] ?? date('Y-m-d'));

    if (empty($titre)) jsonError(422,"Titre obligatoire.");
    if (empty($desc))  jsonError(422,"Description obligatoire.");
    if (!isDate($date)) jsonError(422,"Date invalide.");

    $typesOk = ['consultation','examen','bilan','vaccination','urgence','note','autre'];
    if (!in_array($type,$typesOk)) $type = 'note';

    $sql = "INSERT INTO dossier_medical (patient_id,medecin_id,titre,description,type_entree,created_at)
            VALUES (:pid,:mid,:titre,:desc,:type,:date)";
    $pdo->prepare($sql)->execute([
        ':pid'=>$pid,':mid'=>$mid,
        ':titre'=>$titre,':desc'=>$desc,
        ':type'=>$type,':date'=>$date.' '.date('H:i:s'),
    ]);

    jsonSuccess(['id'=>(int)$pdo->lastInsertId(),'titre'=>$titre,'date'=>$date],
        "Entrée '$titre' ajoutée au dossier.");
}

function modifierEntree(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    $eid = (int)($d['entree_id']  ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $chk = $pdo->prepare("SELECT id FROM dossier_medical WHERE id=:eid AND patient_id=:pid LIMIT 1");
    $chk->execute([':eid'=>$eid,':pid'=>$pid]);
    if (!$chk->fetch()) jsonError(404,"Entrée introuvable.");

    $sets=[]; $params=[':eid'=>$eid];
    if (!empty($d['titre']))       { $sets[]="titre=:titre";      $params[':titre']=$d['titre']; }
    if (!empty($d['description'])) { $sets[]="description=:desc"; $params[':desc']=$d['description']; }
    if (!empty($d['type']))        { $sets[]="type_entree=:type"; $params[':type']=$d['type']; }
    if (empty($sets)) jsonError(400,"Aucun champ à modifier.");

    $pdo->prepare("UPDATE dossier_medical SET ".implode(",",$sets)." WHERE id=:eid")
        ->execute($params);
    jsonSuccess(['entree_id'=>$eid],"Entrée mise à jour.");
}

function supprimerEntree(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    $eid = (int)($d['entree_id']  ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $pdo->prepare("DELETE FROM dossier_medical WHERE id=:eid AND patient_id=:pid")
        ->execute([':eid'=>$eid,':pid'=>$pid]);
    jsonSuccess(['entree_id'=>$eid],"Entrée supprimée.");
}

// ============================================================
// VACCINS
// ============================================================
function ajouterVaccin(PDO $pdo, int $mid, array $d): void {
    $pid  = (int)($d['patient_id'] ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $nom    = clean($d['nom_vaccin']  ?? '');
    $date   = clean($d['date_prevue'] ?? date('Y-m-d'));
    $statut = clean($d['statut']      ?? 'Programmé');
    $lot    = clean($d['lot']         ?? '');
    $notes  = clean($d['notes']       ?? '');

    if (empty($nom))   jsonError(422,"Nom du vaccin obligatoire.");
    if (!isDate($date)) jsonError(422,"Date invalide.");

    $statusOk = ['Programmé','Effectué','En retard'];
    if (!in_array($statut,$statusOk)) jsonError(422,"Statut vaccin invalide.");

    // Si déjà effectué, enregistre la date réelle = aujourd'hui
    $dateReelle = ($statut === 'Effectué') ? date('Y-m-d') : null;

    $sql = "INSERT INTO vaccins (patient_id,medecin_id,nom_vaccin,date_prevue,date_reelle,lot,statut,notes,created_at)
            VALUES (:pid,:mid,:nom,:datePrev,:dateReel,:lot,:statut,:notes,NOW())";
    $pdo->prepare($sql)->execute([
        ':pid'=>$pid,':mid'=>$mid,
        ':nom'=>$nom,':datePrev'=>$date,':dateReel'=>$dateReelle,
        ':lot'=>$lot?:null,':statut'=>$statut,':notes'=>$notes?:null,
    ]);

    jsonSuccess(['id'=>(int)$pdo->lastInsertId(),'nom'=>$nom,'statut'=>$statut],
        "Vaccin '$nom' ajouté.");
}

function modifierVaccin(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    $vid = (int)($d['vaccin_id']  ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $chk = $pdo->prepare("SELECT id FROM vaccins WHERE id=:vid AND patient_id=:pid LIMIT 1");
    $chk->execute([':vid'=>$vid,':pid'=>$pid]);
    if (!$chk->fetch()) jsonError(404,"Vaccin introuvable.");

    $statut = clean($d['statut'] ?? '');
    $statusOk = ['Programmé','Effectué','En retard'];
    if (!in_array($statut,$statusOk)) jsonError(422,"Statut invalide.");

    // Enregistre la date réelle si marqué effectué
    $dateReelle = ($statut === 'Effectué') ? date('Y-m-d') : null;

    $pdo->prepare("UPDATE vaccins SET statut=:s, date_reelle=:dr WHERE id=:vid")
        ->execute([':s'=>$statut,':dr'=>$dateReelle,':vid'=>$vid]);

    jsonSuccess(['vaccin_id'=>$vid,'statut'=>$statut],"Statut vaccin : $statut");
}

function supprimerVaccin(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    $vid = (int)($d['vaccin_id']  ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $pdo->prepare("DELETE FROM vaccins WHERE id=:vid AND patient_id=:pid")
        ->execute([':vid'=>$vid,':pid'=>$pid]);
    jsonSuccess(['vaccin_id'=>$vid],"Vaccin supprimé.");
}

// ============================================================
// CONSULTATIONS
// ============================================================
function ajouterConsultation(PDO $pdo, int $mid, array $d): void {
    $pid  = (int)($d['patient_id'] ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $motif      = clean($d['motif']        ?? '');
    $diagnostic = clean($d['diagnostic']   ?? '');
    $traitement = clean($d['traitement']   ?? '');
    $notes      = clean($d['notes']        ?? '');
    $statut     = clean($d['statut']       ?? 'Terminée');
    $duree      = (int)($d['duree_minutes'] ?? 0);
    $date       = clean($d['date']         ?? date('Y-m-d H:i:s'));

    if (empty($motif)) jsonError(422,"Motif obligatoire.");

    $statusOk = ['En attente','Confirmée','Terminée','Annulée'];
    if (!in_array($statut,$statusOk)) $statut = 'Terminée';

    $sql = "INSERT INTO consultations
                (patient_id,medecin_id,motif,diagnostic,traitement,notes,statut,duree_minutes,date_consultation)
            VALUES (:pid,:mid,:motif,:diag,:trait,:notes,:statut,:duree,:date)";
    $pdo->prepare($sql)->execute([
        ':pid'=>$pid,':mid'=>$mid,
        ':motif'=>$motif,':diag'=>$diagnostic?:null,
        ':trait'=>$traitement?:null,':notes'=>$notes?:null,
        ':statut'=>$statut,':duree'=>$duree?:null,':date'=>$date,
    ]);

    jsonSuccess(['id'=>(int)$pdo->lastInsertId(),'motif'=>$motif],
        "Consultation '$motif' enregistrée.");
}

function modifierConsultation(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id']      ?? 0);
    $cid = (int)($d['consultation_id'] ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $chk = $pdo->prepare("SELECT id FROM consultations WHERE id=:cid AND patient_id=:pid LIMIT 1");
    $chk->execute([':cid'=>$cid,':pid'=>$pid]);
    if (!$chk->fetch()) jsonError(404,"Consultation introuvable.");

    $sets=[]; $params=[':cid'=>$cid];
    $map = ['motif'=>'motif','diagnostic'=>'diagnostic','traitement'=>'traitement',
            'notes'=>'notes','statut'=>'statut'];
    foreach($map as $f=>$col) {
        if (!empty($d[$f])) { $sets[]="{$col}=:{$f}"; $params[":{$f}"]=clean($d[$f]); }
    }
    if (empty($sets)) jsonError(400,"Aucun champ à modifier.");

    $pdo->prepare("UPDATE consultations SET ".implode(",",$sets)." WHERE id=:cid")
        ->execute($params);
    jsonSuccess(['consultation_id'=>$cid],"Consultation mise à jour.");
}

// ============================================================
// ORDONNANCES
// ============================================================
function creerOrdonnance(PDO $pdo, int $mid, array $d): void {
    $pid  = (int)($d['patient_id'] ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $medic = clean($d['medicament']   ?? '');
    $pos   = clean($d['posologie']    ?? '');
    $dur   = clean($d['duree']        ?? '');
    $instr = clean($d['instructions'] ?? '');

    if (empty($medic)) jsonError(422,"Médicament obligatoire.");
    if (empty($pos))   jsonError(422,"Posologie obligatoire.");
    if (empty($dur))   jsonError(422,"Durée obligatoire.");

    $sql = "INSERT INTO ordonnances (patient_id,medecin_id,medicament,posologie,duree,instructions,archivee,created_at)
            VALUES (:pid,:mid,:med,:pos,:dur,:instr,0,NOW())";
    $pdo->prepare($sql)->execute([
        ':pid'=>$pid,':mid'=>$mid,':med'=>$medic,
        ':pos'=>$pos,':dur'=>$dur,':instr'=>$instr?:null,
    ]);

    jsonSuccess([
        'id'=>(int)$pdo->lastInsertId(),
        'medicament'=>$medic,'posologie'=>$pos,'duree'=>$dur,
        'date'=>date('d/m/Y'),
    ],"Ordonnance créée pour $medic.");
}

function archiverOrdonnance(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id']    ?? 0);
    $oid = (int)($d['ordonnance_id'] ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    $chk = $pdo->prepare("SELECT id FROM ordonnances WHERE id=:oid AND patient_id=:pid AND medecin_id=:mid LIMIT 1");
    $chk->execute([':oid'=>$oid,':pid'=>$pid,':mid'=>$mid]);
    if (!$chk->fetch()) jsonError(404,"Ordonnance introuvable.");

    $pdo->prepare("UPDATE ordonnances SET archivee=1 WHERE id=:oid")
        ->execute([':oid'=>$oid]);
    jsonSuccess(['ordonnance_id'=>$oid],"Ordonnance archivée.");
}

// ============================================================
// SIGNES VITAUX
// ============================================================
function ajouterVitaux(PDO $pdo, int $mid, array $d): void {
    $pid = (int)($d['patient_id'] ?? 0);
    if (!peutAccederPatient($pdo,$pid,$mid)) jsonError(403,"Accès refusé.");

    // Filtre et valide chaque mesure comme float ou null
    $fc    = is_numeric($d['fc_bpm']              ?? null) ? (float)$d['fc_bpm']             : null;
    $temp  = is_numeric($d['temperature_c']       ?? null) ? (float)$d['temperature_c']      : null;
    $gly   = is_numeric($d['glycemie_mgdl']       ?? null) ? (float)$d['glycemie_mgdl']      : null;
    $sys   = is_numeric($d['tension_systolique']  ?? null) ? (int)$d['tension_systolique']   : null;
    $dia   = is_numeric($d['tension_diastolique'] ?? null) ? (int)$d['tension_diastolique']  : null;
    $tail  = is_numeric($d['taille_cm']           ?? null) ? (float)$d['taille_cm']          : null;
    $poids = is_numeric($d['poids_kg']            ?? null) ? (float)$d['poids_kg']           : null;

    // Au moins une mesure doit être fournie
    $vals = array_filter([$fc,$temp,$gly,$sys,$dia,$tail,$poids], fn($v) => $v !== null);
    if (empty($vals)) jsonError(422,"Aucune mesure fournie.");

    $sql = "INSERT INTO signes_vitaux
                (patient_id,medecin_id,fc_bpm,temperature_c,glycemie_mgdl,
                 tension_systolique,tension_diastolique,taille_cm,poids_kg,created_at)
            VALUES (:pid,:mid,:fc,:temp,:gly,:sys,:dia,:tail,:poids,NOW())";
    $pdo->prepare($sql)->execute([
        ':pid'=>$pid,':mid'=>$mid,':fc'=>$fc,':temp'=>$temp,
        ':gly'=>$gly,':sys'=>$sys,':dia'=>$dia,':tail'=>$tail,':poids'=>$poids,
    ]);

    jsonSuccess(['id'=>(int)$pdo->lastInsertId()],"Signes vitaux enregistrés.");
}