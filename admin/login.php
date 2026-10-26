<?php
session_start();
require_once '../includes/db.php';

// Si déjà connecté, rediriger vers le tableau de bord
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit();
}

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['mot_de_passe'])) {
                // Connexion réussie
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_id'] = $admin['id'];

                header('Location: index.php');
                exit();
            } else {
                $erreur = "Identifiants incorrects.";
            }
        } catch (PDOException $e) {
            $erreur = "Erreur de connexion à la base de données.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | DJORRO Restaurant</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background-color: #2b1d14; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .login-box { background: #ffffff; padding: 40px; border-radius: 16px; width: 100%; max-width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .login-box h1 { text-align: center; color: var(--deep-wood); margin-bottom: 8px; font-size: 1.8rem; }
        .login-box p { text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px; }
    </style>
</head>
<body>

    <div class="login-box">
        <h1>DJORRO<span>.</span> Admin</h1>
        <p>Espace de gestion du restaurant</p>

        <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px; text-align: center; padding: 10px; border-radius: 8px; background: #f8d7da; color: #721c24;">
                <?php echo $erreur; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="email" style="display:block; margin-bottom: 6px; font-weight: 600;">E-mail administrateur</label>
                <input type="email" id="email" name="email" required placeholder="admin@djorro.fr" style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="password" style="display:block; margin-bottom: 6px; font-weight: 600;">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; border: none; cursor: pointer; border-radius: 8px; font-weight: 700;">Se Connecter</button>
        </form>
    </div>

</body>
</html>