<?php
session_start();

// Si le panier est vide, rediriger vers la page commande
if (empty($_SESSION['panier'])) {
    header('Location: commande.php');
    exit();
}

// Token anti-CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Calcul du total
$total_panier = 0;
foreach ($_SESSION['panier'] as $item) {
    $total_panier += $item['prix'];
}

$message_status = "";
$message_type = "";
$commande_validee = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message_status = "Erreur de sécurité : Requête non autorisée.";
        $message_type = "danger";
    } else {
        // Nettoyage des données
        $nom       = htmlspecialchars(trim($_POST['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $heure_retrait = htmlspecialchars(trim($_POST['heure_retrait'] ?? ''), ENT_QUOTES, 'UTF-8');
        $notes     = htmlspecialchars(trim($_POST['notes'] ?? ''), ENT_QUOTES, 'UTF-8');

        if (empty($nom) || empty($telephone) || empty($heure_retrait)) {
            $message_status = "Veuillez remplir tous les champs obligatoires.";
            $message_type = "danger";
        } else {
            // Commande validée
            $commande_validee = true;
            $numero_commande = "DJ-" . rand(1000, 9999);
            
            $message_status = "Merci $nom ! Votre commande N° <strong>$numero_commande</strong> d'un montant de <strong>" . number_format($total_panier, 2, ',', ' ') . " €</strong> a bien été enregistrée pour un retrait à <strong>$heure_retrait</strong> au 35 Rue La Condamine.";
            $message_type = "success";

            // Réinitialiser le panier
            $_SESSION['panier'] = [];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de commande | DJORRO Restaurant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
    <img src="img/logo.png" alt="DJORRO Restaurant" style="max-height: 200px; width: auto; display: block;">
</a>
            <nav class="nav-links" id="navLinks">
                <a href="index.php">Accueil</a>
                <a href="menu.php">Nos Menus</a>
                <a href="commande.php" class="active">Commander</a>
                <a href="reservation.php" class="btn-primary">Réserver une table</a>
            </nav>
            <button class="burger-menu" id="burgerBtn" aria-label="Ouvrir le menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <!-- BANNIÈRE -->
    <section class="menu-hero">
        <div class="menu-hero-overlay"></div>
        <div class="menu-hero-content text-center">
            <span class="sub-title" style="color: var(--gold);">Click & Collect</span>
            <h1>Finaliser Ma Commande</h1>
        </div>
    </section>

    <!-- FORMULAIRE DE FINALISATION -->
    <section class="section-padding">
        <div class="container" style="max-width: 800px;">
            
            <div class="form-card">

                <?php if (!empty($message_status)): ?>
                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo $message_status; ?>
                    </div>
                <?php endif; ?>

                <?php if (!$commande_validee): ?>
                    
                    <div class="checkout-summary" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px dashed var(--border-color);">
                        <h3>Récapitulatif de vos articles :</h3>
                        <ul style="list-style: none; margin-top: 15px;">
                            <?php foreach ($_SESSION['panier'] as $item): ?>
                                <li style="display: flex; justify-content: space-between; padding: 6px 0;">
                                    <span><?php echo $item['nom']; ?></span>
                                    <strong><?php echo number_format($item['prix'], 2, ',', ' '); ?> €</strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div style="display: flex; justify-content: space-between; font-size: 1.2rem; margin-top: 15px; font-weight: 700; color: var(--terracotta);">
                            <span>Total à payer sur place :</span>
                            <span><?php echo number_format($total_panier, 2, ',', ' '); ?> €</span>
                        </div>
                    </div>

                    <form action="checkout.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nom">Nom complet *</label>
                                <input type="text" id="nom" name="nom" placeholder="Ex: Bakary Diallo" required>
                            </div>

                            <div class="form-group">
                                <label for="telephone">Numéro de téléphone (pour SMS) *</label>
                                <input type="tel" id="telephone" name="telephone" placeholder="Ex: 06 00 00 00 00" required>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="heure_retrait">Heure de retrait souhaitée au restaurant *</label>
                                <select id="heure_retrait" name="heure_retrait" required>
                                    <option value="">Choisir l'heure de retrait...</option>
                                    <option value="Dans 30 minutes">Dès que possible (environ 30 min)</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="19:30">19:30</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:30">20:30</option>
                                    <option value="21:00">21:00</option>
                                    <option value="22:00">22:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label for="notes">Instructions particulières (ex: piment à part, sauce en sus)</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Précisez ici vos préférences..."></textarea>
                        </div>

                        <div class="text-center" style="margin-top: 30px;">
                            <button type="submit" class="btn-primary" style="width: 100%; max-width: 350px; padding: 15px;">Confirmer le retrait à emporter</button>
                        </div>
                    </form>

                <?php else: ?>
                    <div class="text-center" style="margin-top: 20px;">
                        <a href="index.php" class="btn-gold">Retourner à l'accueil</a>
                    </div>
                <?php endif; ?>

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