<?php
session_start();

function redirectByRole(string $role): string {
    return match ($role) {
        'medecin' => '../Medecin/accueil.php',
        'admin' => '../Admin/accueil.php',
        default => '../Patient/accueil.php',
    };
}

function setFlash(string $message, string $type = 'error'): void {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'register') {
    require_once __DIR__ . '/../BD/config/database.php';

    $role = $_POST['role'] ?? 'patient';
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['mot_de_passe'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');

    $errors = [];
    if (!in_array($role, ['patient', 'medecin', 'admin'], true)) {
        $errors[] = 'Role invalide.';
    }
    if ($nom === '') {
        $errors[] = 'Le nom est obligatoire.';
    }
    if ($prenom === '') {
        $errors[] = 'Le prenom est obligatoire.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Adresse email invalide.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caracteres.';
    }

    if ($role === 'patient') {
        if (($_POST['date_naissance'] ?? '') === '') {
            $errors[] = 'La date de naissance est obligatoire.';
        }
        if (!in_array($_POST['sexe'] ?? '', ['M', 'F', 'Autre'], true)) {
            $errors[] = 'Le sexe est obligatoire.';
        }
    }

    if ($role === 'medecin') {
        if (trim($_POST['numero_ordre'] ?? '') === '') {
            $errors[] = "Le numero d'ordre medical est obligatoire.";
        }
        if (trim($_POST['specialite'] ?? '') === '') {
            $errors[] = 'La specialite est obligatoire.';
        }
    }

    if ($role === 'admin') {
        $adminCode = $_POST['admin_code'] ?? '';
        $expectedCode = getenv('HEALTHCARE_ADMIN_CODE') ?: 'ADMIN123';
        if ($adminCode !== $expectedCode) {
            $errors[] = 'Code administrateur incorrect.';
        }
    }

    if ($errors) {
        setFlash(implode(' ', $errors));
        header('Location: inscription.php?role=' . urlencode($role));
        exit;
    }

    try {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            setFlash('Cet email existe deja.');
            header('Location: inscription.php?role=' . urlencode($role));
            exit;
        }

        if ($role === 'medecin') {
            $stmt = $pdo->prepare('SELECT id FROM medecins WHERE numero_ordre = :numero_ordre LIMIT 1');
            $stmt->execute([':numero_ordre' => trim($_POST['numero_ordre'])]);
            if ($stmt->fetch()) {
                setFlash("Ce numero d'ordre medical existe deja.");
                header('Location: inscription.php?role=medecin');
                exit;
            }
        }

        $pdo->beginTransaction();

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, telephone, est_actif)
            VALUES (:nom, :prenom, :email, :mot_de_passe, :role, :telephone, 1)
        ");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => $hash,
            ':role' => $role,
            ':telephone' => $telephone ?: null,
        ]);
        $userId = (int) $pdo->lastInsertId();

        if ($role === 'patient') {
            $stmt = $pdo->prepare("
                INSERT INTO patients (user_id, date_naissance, sexe, groupe_sanguin, adresse, ville)
                VALUES (:user_id, :date_naissance, :sexe, :groupe_sanguin, :adresse, :ville)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':date_naissance' => $_POST['date_naissance'],
                ':sexe' => $_POST['sexe'],
                ':groupe_sanguin' => $_POST['groupe_sanguin'] ?: null,
                ':adresse' => trim($_POST['adresse'] ?? '') ?: null,
                ':ville' => trim($_POST['patient_ville'] ?? '') ?: null,
            ]);
        } elseif ($role === 'medecin') {
            $stmt = $pdo->prepare("
                INSERT INTO medecins (user_id, numero_ordre, specialite, hopital, ville)
                VALUES (:user_id, :numero_ordre, :specialite, :hopital, :ville)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':numero_ordre' => trim($_POST['numero_ordre']),
                ':specialite' => trim($_POST['specialite']),
                ':hopital' => trim($_POST['hopital'] ?? '') ?: null,
                ':ville' => trim($_POST['medecin_ville'] ?? '') ?: null,
            ]);
        }

        $pdo->commit();

        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['derniere_activite'] = time();

        setFlash('Inscription reussie.', 'success');
        header('Location: ' . redirectByRole($role));
        exit;
    } catch (Throwable $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log($e->getMessage());
        setFlash('Inscription impossible. Verifiez la base healthcare et les informations saisies.');
        header('Location: inscription.php?role=' . urlencode($role));
        exit;
    }
}

$activeRole = $_GET['role'] ?? 'patient';
if (!in_array($activeRole, ['patient', 'medecin', 'admin'], true)) {
    $activeRole = 'patient';
}

$flashMessage = $_SESSION['flash_message'] ?? '';
$flashType = $_SESSION['flash_type'] ?? 'error';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare - Inscription</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
        }
        .form-col {
            display: flex;
            flex-direction: column;
        }
        .login-box {
            width: min(450px, calc(100vw - 24px));
            margin: auto;
        }
        .separateur {
            border: none;
            border-top: 1px dashed #ccc;
            margin: 15px 0;
        }
        .section-label {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 10px;
            font-weight: bold;
        }
        select, textarea, .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .tab-form { display: none; }
        .tab-form.active { display: block; }
        .login-tab.active {
            background-color: #1a3a5c;
            color: white;
            border-radius: 6px;
        }
        .flash {
            margin: 12px 0;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .flash.error {
            background: #fde8e8;
            color: #9b1c1c;
        }
        .flash.success {
            background: #def7ec;
            color: #03543f;
        }
        @media (max-width: 520px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <a href="home.php" class="btn-retour">&#8592; Retour</a>

    <div class="login-page">
        <div class="login-box">
            <div class="logo">Healthcare</div>
            <p class="login-subtitle">Votre plateforme de sante numerique</p>

            <div class="login-tabs">
                <div class="login-tab <?= $activeRole === 'patient' ? 'active' : '' ?>" onclick="switchTab('patient', this)">Patient</div>
                <div class="login-tab <?= $activeRole === 'medecin' ? 'active' : '' ?>" onclick="switchTab('medecin', this)">Medecin</div>
                <div class="login-tab <?= $activeRole === 'admin' ? 'active' : '' ?>" onclick="switchTab('admin', this)">Admin</div>
            </div>

            <?php if ($flashMessage !== ''): ?>
                <div class="flash <?= htmlspecialchars($flashType, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($flashMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="inscription.php">
                <input type="hidden" name="action" value="register">
                <input type="hidden" id="role-field" name="role" value="<?= htmlspecialchars($activeRole, ENT_QUOTES, 'UTF-8') ?>">

                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Nom</label>
                        <input class="form-input" name="nom" type="text" required>
                    </div>
                    <div class="form-col">
                        <label class="form-label">Prenom</label>
                        <input class="form-input" name="prenom" type="text" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Adresse e-mail</label>
                    <input class="form-input" name="email" type="email" required>
                </div>

                <div class="form-row" style="margin-top:15px;">
                    <div class="form-col">
                        <label class="form-label">Mot de passe</label>
                        <input class="form-input" name="mot_de_passe" type="password" minlength="6" required>
                    </div>
                    <div class="form-col">
                        <label class="form-label">Telephone</label>
                        <input class="form-input" name="telephone" type="tel">
                    </div>
                </div>

                <div id="form-patient" class="tab-form <?= $activeRole === 'patient' ? 'active' : '' ?>">
                    <hr class="separateur">
                    <p class="section-label">Informations patient</p>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Date de naissance</label>
                            <input class="form-input patient-required" name="date_naissance" type="date">
                        </div>
                        <div class="form-col">
                            <label class="form-label">Sexe</label>
                            <select class="patient-required" name="sexe">
                                <option value="">-- Choisir --</option>
                                <option value="M">Masculin</option>
                                <option value="F">Feminin</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Groupe sanguin</label>
                            <select name="groupe_sanguin">
                                <option value="">-- Choisir --</option>
                                <option>A+</option><option>A-</option>
                                <option>B+</option><option>B-</option>
                                <option>AB+</option><option>AB-</option>
                                <option>O+</option><option>O-</option>
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Ville</label>
                            <input class="form-input" name="patient_ville" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <textarea name="adresse" rows="2"></textarea>
                    </div>
                </div>

                <div id="form-medecin" class="tab-form <?= $activeRole === 'medecin' ? 'active' : '' ?>">
                    <hr class="separateur">
                    <p class="section-label">Informations medecin</p>
                    <div class="form-group">
                        <label class="form-label">Numero d'ordre medical</label>
                        <input class="form-input medecin-required" name="numero_ordre" type="text" placeholder="Ex: CM-12345">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Specialite</label>
                        <select class="medecin-required" name="specialite">
                            <option value="">-- Choisir --</option>
                            <option>Generaliste</option>
                            <option>Cardiologue</option>
                            <option>Pediatre</option>
                            <option>Dermatologue</option>
                            <option>Gynecologue</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Hopital</label>
                            <input class="form-input" name="hopital" type="text">
                        </div>
                        <div class="form-col">
                            <label class="form-label">Ville</label>
                            <input class="form-input" name="medecin_ville" type="text">
                        </div>
                    </div>
                </div>

                <div id="form-admin" class="tab-form <?= $activeRole === 'admin' ? 'active' : '' ?>">
                    <hr class="separateur">
                    <p class="section-label">Informations administrateur</p>
                    <div class="form-group">
                        <label class="form-label">Code d'acces admin</label>
                        <input class="form-input admin-required" name="admin_code" type="password" placeholder="Code admin">
                    </div>
                </div>

                <button class="btn-submit" style="margin-top: 20px; width: 100%;" type="submit">S'inscrire</button>

                <p style="text-align:center; margin-top:16px; font-size:0.85rem;">
                    Deja un compte ?
                    <span class="lien-compte" style="color:blue; cursor:pointer;" onclick="window.location.href='index.php'">Se connecter</span>
                </p>
            </form>
        </div>
    </div>

    <script>
        var roleActif = <?= json_encode($activeRole) ?>;

        function syncRequiredFields() {
            document.querySelectorAll('.patient-required, .medecin-required, .admin-required').forEach(function(field) {
                field.required = false;
            });

            document.querySelectorAll('.' + roleActif + '-required').forEach(function(field) {
                field.required = true;
            });
        }

        function switchTab(role, el) {
            roleActif = role;
            document.getElementById('role-field').value = role;
            document.querySelectorAll('.tab-form').forEach(function(form) {
                form.classList.remove('active');
            });
            document.querySelectorAll('.login-tab').forEach(function(tab) {
                tab.classList.remove('active');
            });
            document.getElementById('form-' + role).classList.add('active');
            el.classList.add('active');
            syncRequiredFields();
        }

        syncRequiredFields();
    </script>
</body>
</html>
