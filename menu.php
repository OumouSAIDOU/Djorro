<?php
// Connexion à la base de données
require_once 'includes/db.php';

// 1. Récupération des catégories pour les boutons de filtres
try {
    $stmt_cat = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
    $categories = $stmt_cat->fetchAll();

    // 2. Récupération des plats avec leur catégorie associée
    $stmt_plats = $pdo->query("
        SELECT p.*, c.slug as cat_slug 
        FROM plats p 
        JOIN categories c ON p.categorie_id = c.id 
        WHERE p.disponible = 1 
        ORDER BY p.id DESC
    ");
    $plats = $stmt_plats->fetchAll();
} catch (PDOException $e) {
    $erreur_db = "Erreur lors du chargement du menu : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez la carte du DJORRO Restaurant à Paris 17e : Spécialités ivoiriennes et ouest-africaines, plats braisés, sauces traditionnelles et boissons maison.">
    <title>La Carte & Menu | DJORRO Restaurant Paris 17</title>
    
    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Feuille de style CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- NAVBAR / EN-TÊTE -->
    <header class="navbar">
        <div class="container nav-container">
              <a href="index.php" class="logo">
    <img src="img/logo.png" alt="DJORRO Restaurant" style="max-height: 200px; width: auto; display: block;">
</a>
            
            <nav class="nav-links" id="navLinks">
    <a href="index.php">Accueil</a>
    <a href="menu.php" class="active">Nos Menus</a>
    <a href="commande.php" style="position: relative;">
        Commander <span id="cartCount" style="display: none; background: var(--terracotta); color: white; padding: 2px 7px; border-radius: 50%; font-size: 0.75rem; margin-left: 5px; font-weight: 700;">0</span>
    </a>
    <a href="reservation.php" class="btn-primary">Réserver une table</a>
</nav>

            <button class="burger-menu" id="burgerBtn" aria-label="Ouvrir le menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <!-- BANNIÈRE DE LA PAGE -->
    <section class="menu-hero" style="background: linear-gradient(to bottom, rgba(43, 29, 20, 0.35), rgba(43, 29, 20, 0.55)), url('img/plats/grillade.jpg') center/cover no-repeat fixed;">
        <div class="menu-hero-content text-center">
            <span class="sub-title" style="color: var(--gold);">Fait maison & Parfumé</span>
            <h1>Notre Carte & Spécialités</h1>
            <p>Retrouvez toutes les saveurs d'Afrique de l'Ouest préparées avec passion.</p>
        </div>
    </section>

    <!-- SECTION MENU DYNAMIQUE -->
    <section class="section-padding">
        <div class="container">

            <?php if (isset($erreur_db)): ?>
                <div class="alert alert-danger" style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 8px; text-align: center;">
                    <?php echo $erreur_db; ?>
                </div>
            <?php else: ?>

                <!-- BOUTONS DE FILTRES GÉNÉRÉS DEPUIS LA BD -->
                <div class="filter-container text-center" style="margin-bottom: 40px;">
                    <button class="filter-btn active" data-filter="all">Tous les plats</button>
                    <?php foreach ($categories as $cat): ?>
                        <button class="filter-btn" data-filter="<?php echo htmlspecialchars($cat['slug']); ?>">
                            <?php echo htmlspecialchars($cat['nom']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- GRILLE DES PLATS GÉNÉRÉE DEPUIS LA BD -->
                <div class="menu-grid" id="menuGrid">
                    <?php if (empty($plats)): ?>
                        <p class="text-center" style="grid-column: 1 / -1; color: var(--text-muted); font-size: 1.1rem; padding: 40px 0;">
                            Aucun plat n'est disponible pour le moment.
                        </p>
                    <?php else: ?>
                        <?php foreach ($plats as $plat): ?>
                            <?php 
                                $nom_image = !empty($plat['image']) ? $plat['image'] : 'default.jpg';
                                $chemin_image = 'img/plats/' . $nom_image;
                            ?>
                            <article class="menu-card" data-category="<?php echo htmlspecialchars($plat['cat_slug']); ?>">
                                
                                <!-- ILLUSTRATION / PHOTO DU PLAT -->
                                <div class="menu-card-img">
                                    <img src="<?php echo htmlspecialchars($chemin_image); ?>" 
                                         alt="<?php echo htmlspecialchars($plat['nom']); ?>" 
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='img/plats/default.jpg';">
                                </div>

                                <!-- CONTENU DU PLAT -->
                                <div class="menu-card-body">
                                    <div class="menu-header">
                                        <h3><?php echo htmlspecialchars($plat['nom']); ?></h3>
                                        <span class="price"><?php echo number_format($plat['prix'], 2, ',', ' '); ?> €</span>
                                    </div>
                                    
                                    <p class="description">
                                        <?php echo htmlspecialchars($plat['description']); ?>
                                    </p>
                                    
                                    <?php if (!empty($plat['populaire']) && $plat['populaire'] == 1): ?>
                                        <div style="margin-top: 10px; margin-bottom: 10px;">
                                            <span class="badge-populaire">⭐ Best-seller</span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- BOUTON AJOUTER AU PANIER -->
                                    <!-- ZONE DYNAMIQUE DU BOUTON / QUANTITÉ -->
<div style="margin-top: 15px;" class="plat-action-container" 
     data-id="<?php echo $plat['id']; ?>"
     data-nom="<?php echo htmlspecialchars($plat['nom'], ENT_QUOTES); ?>"
     data-prix="<?php echo $plat['prix']; ?>">
     <!-- Le JavaScript va injecter automatiquement le bouton ou le sélecteur ici -->
</div>
                                </div>

                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

            <!-- APPEL À L'ACTION POUR COMMANDER -->
            <div class="text-center" style="margin-top: 60px;">
                <p style="font-size: 1.2rem; margin-bottom: 20px; font-weight: 500;">
                    Envie de finaliser votre commande ?
                </p>
                <a href="commande.php" class="btn-primary" style="padding: 15px 35px; font-size: 1rem;">
                    Voir mon Panier & Commander (Click & Collect)
                </a>
            </div>

        </div>
    </section>

    <!-- FOOTER / PIED DE PAGE -->
    <footer class="footer">
        <div class="container grid-4">
            <div class="footer-col">
                <h3 class="logo-footer">DJORRO</h3>
                <p>Gastronomie Africaine au cœur du 17e arrondissement de Paris.</p>
            </div>
            <div class="footer-col">
                <h4>Contact & Adresse</h4>
                <p>📍 35 Rue La Condamine, 75017 Paris</p>
                <p>📞 +33 1 83 96 45 47</p>
                <p>✉️ restaurant.djorro@hotmail.com</p>
            </div>
            <div class="footer-col">
                <h4>Horaires d'ouverture</h4>
                <p>Lundi - Dimanche :</p>
                <p><strong>12h00 – 02h00</strong> (Service Continu)</p>
            </div>
            <div class="footer-col">
                <h4>Paiements acceptés</h4>
                <p>CB, Espèces, Ticket Restaurant®</p>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p>&copy; <?php echo date('Y'); ?> DJORRO Restaurant. Tous droits réservés. 
               <a href="admin/login.php" style="color: rgba(255,255,255,0.3); text-decoration: none; margin-left: 15px; font-size: 0.8rem;">🔒 Espace Gérant</a>
            </p>
        </div>
    </footer>

    <!-- Scripts JavaScript -->
    <script src="js/menu-filter.js"></script>
    <script src="js/panier.js"></script>
    <script src="js/main.js"></script>
</body>
</html>