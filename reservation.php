<?php
session_start();


require_once 'includes/db.php'; 

require_once 'includes/mailer.php';

// Token CSRF de sécurité
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message_status = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message_status = "Erreur de sécurité : Requête non autorisée.";
        $message_type = "danger";
    } else {
        $nom       = htmlspecialchars(trim($_POST['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $couverts  = filter_var($_POST['couverts'] ?? 0, FILTER_VALIDATE_INT);
        $date      = htmlspecialchars(trim($_POST['date'] ?? ''), ENT_QUOTES, 'UTF-8');
        $heure     = htmlspecialchars(trim($_POST['heure'] ?? ''), ENT_QUOTES, 'UTF-8');
        $remarque  = htmlspecialchars(trim($_POST['remarque'] ?? ''), ENT_QUOTES, 'UTF-8');

        if (empty($nom) || empty($email) || empty($telephone) || !$couverts || empty($date) || empty($heure)) {
            $message_status = "Veuillez remplir tous les champs obligatoires.";
            $message_type = "danger";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message_status = "L'adresse e-mail saisie n'est pas valide.";
            $message_type = "danger";
        } else {
            
            try {
    // 2. ENREGISTREMENT DANS LA BASE DE DONNÉES MYSQL
    $sql = "INSERT INTO reservations (nom, telephone, email, couverts, date_resa, heure_resa, remarque, cree_le) 
            VALUES (:nom, :telephone, :email, :couverts, :date_resa, :heure_resa, :remarque, NOW())";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom'        => $nom,
        'telephone'  => $telephone,
        'email'      => $email,
        'couverts'   => $couverts,
        'date_resa'  => $date,
        'heure_resa' => $heure,
        'remarque'   => $remarque
    ]);

    // 3. TENTATIVE D'ENVOI DE L'E-MAIL (Ne bloque pas en local)
    $mail_envoye = false;
    if (function_exists('envoyer_notification_reservation')) {
        @$mail_envoye = envoyer_notification_reservation($nom, $email, $telephone, $couverts, $date, $heure, $remarque);
    }

    if ($mail_envoye) {
        $message_status = "Merci $nom ! Votre réservation pour $couverts personne(s) le $date à $heure a bien été enregistrée et transmise.";
    } else {
        $message_status = "Merci $nom ! Votre réservation a bien été enregistrée (elle est visible dans votre espace gérant).";
    }
    
    $message_type = "success";
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

} catch (PDOException $e) {
    $message_status = "Erreur lors de l'enregistrement : " . $e->getMessage();
    $message_type = "danger";
}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une Table | DJORRO Restaurant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="navbar">
        <div class="container nav-container">
              <a href="index.php" class="logo">
    <img src="img/logo.png" alt="DJORRO Restaurant" style="max-height: 200px; width: auto; display: block;">
</a>

           <nav class="nav-links" id="navLinks">
    <a href="index.php">Accueil</a>
    <a href="menu.php">Nos Menus</a>
    <a href="commande.php" style="position: relative;">
         Commander <span id="cartCount" style="display: none; background: var(--terracotta); color: white; padding: 2px 7px; border-radius: 50%; font-size: 0.75rem; margin-left: 5px; font-weight: 700;">0</span>
    </a>
    <a href="reservation.php" class="btn-primary active">Réserver une table</a>
</nav>

            <button class="burger-menu" id="burgerBtn" aria-label="Ouvrir le menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

   <section class="menu-hero hero-reservation" style="background: linear-gradient(to bottom, rgba(20, 29, 20, 0.10)), url('img/plats/reserver.jpg') center/cover no-repeat fixed;">
    <div class="menu-hero-content text-center">
        <span class="sub-title" style="color: var(--gold);">Réservez votre table</span>
        <h1>Une Table chez DJORRO</h1>
        <p>Profitez d'un moment chaleureux et gourmand en réservant à l'avance.</p>
    </div>
</section>

    <section class="section-padding">
        <div class="container" style="max-width: 800px;">
            <div class="form-card">

                <?php if (!empty($message_status)): ?>
                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo $message_status; ?>
                    </div>
                <?php endif; ?>

                <form action="reservation.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Nom complet *</label>
                            <input type="text" id="nom" name="nom" required placeholder="Ex: Bakary">
                        </div>

                        <div class="form-group">
                            <label for="telephone">Numéro de téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" required placeholder="Ex: 06 12 34 56 78">
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse E-mail *</label>
                            <input type="email" id="email" name="email" required placeholder="Ex: votremail@yahoo.com">
                        </div>

                        <div class="form-group">
                            <label for="couverts">Nombre de convives *</label>
                            <select id="couverts" name="couverts" required>
                                <option value="">Choisir...</option>
                                <option value="1">1 Personne</option>
                                <option value="2">2 Personnes</option>
                                <option value="3">3 Personnes</option>
                                <option value="4">4 Personnes</option>
                                <option value="5">5 Personnes et +</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input type="date" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="heure">Heure *</label>
                            <select id="heure" name="heure" required>
                                <option value="">Choisir l'heure...</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="19:30">19:30</option>
                                <option value="20:30">20:30</option>
                                <option value="21:30">21:30</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label for="remarque">Demande particulière</label>
                        <textarea id="remarque" name="remarque" rows="3" placeholder="Allergies, table calme, etc."></textarea>
                    </div>

                    <div class="text-center" style="margin-top: 30px;">
                        <button type="submit" class="btn-primary" style="width: 100%; max-width: 300px; padding: 15px;">Confirmer la Réservation</button>
                    </div>
                </form>

            </div>
        </div>
    </section>

   <!-- PIED DE PAGE / FOOTER -->
    <footer class="footer">
    <div class="container grid-4">
        <div class="footer-col">
            <h3 class="logo-footer">DJORRO</h3>
            <p>Gastronomie Africaine au cœur du 17e arrondissement de Paris.</p>
        </div>
        <div class="footer-col">
            <h4>Contact</h4>
            <p>📍 35 Rue La Condamine, 75017 Paris</p>
            <p>📞 +33 1 83 96 45 47</p>
        </div>
        <div class="footer-col">
            <h4>Horaires</h4>
            <p>Lundi - Dimanche : 12h00 – 02h00</p>
        </div>
        <div class="footer-col">
            <h4>Paiements</h4>
            <p>CB, Espèces, Ticket Restaurant®</p>
        </div>
    </div>

    <!-- BAS DU FOOTER AVEC LE LIEN ADMIN DISCRET -->
    <div class="footer-bottom text-center">
        <p>&copy; 2026 DJORRO Restaurant. Tous droits réservés. 
            <a href="admin/login.php" style="color: rgba(255,255,255,0.3); text-decoration: none; margin-left: 15px; font-size: 0.8rem;">🔒 Espace Gérant</a>
        </p>
    </div>
</footer>

    <script src="js/main.js"></script>
</body>
</html>