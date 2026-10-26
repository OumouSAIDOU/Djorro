<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DJORRO Restaurant - Gastronomie & Spécialités Africaines à Paris 17e. Grillades braisées, sauces traditionnelles et saveurs authentiques.">
    <title>DJORRO Restaurant | Gastronomie Africaine - Paris 17</title>
    
    <!-- Polices Google Fonts (Playfair Display pour le côté chic + Poppins pour la lisibilité) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,800;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Fichier CSS principal -->
    <link rel="stylesheet" href="css/style.css
    ">
</head>
<body>

    <!-- NAV BAR / EN-TÊTE -->
    <header class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
    <img src="img/logo.png" alt="DJORRO Restaurant" style="max-height: 200px; width: auto; display: block;">
</a>
            
           <nav class="nav-links" id="navLinks">
    <a href="index.php" class="active">Accueil</a>
    <a href="menu.php">Nos Menus</a>
    <a href="commande.php" style="position: relative;">
        Commander <span id="cartCount" style="display: none; background: var(--terracotta); color: white; padding: 2px 7px; border-radius: 50%; font-size: 0.75rem; margin-left: 5px; font-weight: 700;">0</span>
    </a>
    <a href="reservation.php" class="btn-primary">Réserver une table</a>
</nav>

            <!-- Bouton Hamburger pour Mobile -->
            <button class="burger-menu" id="burgerBtn" aria-label="Ouvrir le menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

   <!-- SECTION HERO (ACCUEIL PRINCIPAL) AVEC ROTATION JS -->
<section class="hero" id="homeHero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <span class="tagline">Paris 17e • La Fourche - Guy Môquet</span>
        <h1>L'Art de la Gastronomie Africaine </h1>
        <p>Une expérience culinaire authentique au cœur de Paris. Poissons et viandes braisés, ragoûts mijotés et spécialités maison.</p>
        <div class="hero-buttons">
            <a href="menu.php" class="btn-gold">Découvrir le Menu</a>
            <a href="reservation.php" class="btn-outline">Réserver une Table</a>
        </div>
    </div>
</section>

    <!-- SECTION VALEURS / HISTOIRE -->
    <section class="about section-padding">
        <div class="container grid-2">
            <div class="about-text">
                <span class="sub-title">Bienvenue chez DJORRO</span>
                <h2>Des saveurs d'exception, mijotées avec passion</h2>
                <p>Situé au 35 Rue La Condamine dans le quartier animé de La Fourche-Guy Môquet, <strong>DJORRO Restaurant</strong> réinvente les grands classiques de la cuisine ouest-africaine dans un cadre élégant et chaleureux.</p>
                <p>Du traditionnel <em>Garba au Thon</em> aux généreuses sauces <em>Graine</em> et <em>Claires</em>, chaque plat est préparé à partir d'ingrédients frais et d'épices soigneusement sélectionnées.</p>
                <div class="badge-container">
                    <div class="badge">🔥 Grillades au Feu</div>
                    <div class="badge">💳 Titres Restaurant Acceptés</div>
                    <div class="badge">🌙 Ouvert jusqu'à 02h00</div>
                </div>
            </div>
            <div class="about-image">
                <img src="img/plats/platbraisé.jpg" alt="Plat braisé DJORRO" loading="lazy">
            </div>
        </div>
    </section>

    <!-- SECTION APERÇU SPÉCIALITÉS -->
    <section class="featured section-padding">
        <div class="container">
            <div class="section-header text-center">
                <span class="sub-title">Nos Incontournables</span>
                <h2>Les Plats Phares de la Maison</h2>
            </div>
            
            <div class="grid-3">
                <!-- Carte Plat 1 -->
                <div class="food-card">
                    <div class="food-img">
                        <!-- <span class="price">24,00 €</span> -->
                        <img src="img/plats/PoissonCapitainebraisé.jpg" alt="Poisson capitaine braisé">
                    </div>
                    <div class="food-info">
                        <h3>Poisson Capitaine Braisé</h3>
                        <p>Poisson entier braisé à la perfection, garni d’oignons rouges frais et d’herbes aromatiques.</p>
                        <!-- <a href="menu.php" class="link-gold">Voir la carte →</a> -->
                    </div>
                </div>

                <!-- Carte Plat 2 -->
                <div class="food-card">
                    <div class="food-img">
                        <!-- <span class="price">18,00 €</span> -->
                        <img src="img/plats/sauceclaire.jpg" alt="Sauce claire">
                    </div>
                    <div class="food-info">
                        <h3>Sauce Claire Royale</h3>
                        <p>Garnie de crabe, poisson frais et aubergines africaines. Un mijoté traditionnel parfumé.</p>
                        <!-- <a href="menu.php" class="link-gold">Voir la carte →</a> -->
                    </div>
                </div>

                <!-- Carte Plat 3 -->
                <div class="food-card">
                    <div class="food-img">
                        <!-- <span class="price">24,00 €</span> -->
                        <img src="img/plats/ChoukouyadeMouton.jpg" alt="Choukouya agneau">
                    </div>
                    <div class="food-info">
                        <h3>Choukouya d'Agneau Bidi</h3>
                        <p>Morceaux d'agneau tendres, marinés aux épices secrètes de la maison puis braisés au feu.</p>
                        <!-- <a href="menu.php" class="link-gold">Voir la carte →</a> -->
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- SECTION AVIS CLIENTS / GOOGLE -->
<section class="section-padding" style="background-color: #fdfaf5;">
    <div class="container text-center">
        <span class="sub-title" style="color: var(--gold);">Ce que disent nos clients</span>
        <h2 style="margin-bottom: 10px;">Avis & Témoignages</h2>
        
        <!-- Note globale -->
        <div style="margin-bottom: 40px; font-size: 1.1rem;">
            <span style="color: #ffb400; font-size: 1.4rem;">⭐⭐⭐⭐⭐</span> 
            <strong>4.5 / 5</strong> sur Google <small style="color: var(--text-muted);">(Avis vérifiés)</small>
        </div>

        <!-- Grille des avis -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
            
            <!-- Avis 1 -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: left;">
                <div style="color: #ffb400; margin-bottom: 10px;">⭐⭐⭐⭐⭐</div>
                <p style="font-style: italic; margin-bottom: 15px; color: #555;">« Supeeer ! Accueil, plats, emplacement, personnel, prix, qualité. Je conseille ce restaurant à toute personne désirant passer une bonne soirée. »</p>
                <strong>— S. Sidiki</strong>
            </div>

            <!-- Avis 2 -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: left;">
                <div style="color: #ffb400; margin-bottom: 10px;">⭐⭐⭐⭐⭐</div>
                <p style="font-style: italic; margin-bottom: 15px; color: #555;">« J'ai été surpris de l'accueil et des plats délicieux car je m'attendais à un resto banal. Une cuisine fraîche et faite sur place en direct, à encourager ! »</p>
                <strong>— B. Bestone</strong>
            </div>

            <!-- Avis 3 -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: left;">
                <div style="color: #ffb400; margin-bottom: 10px;">⭐⭐⭐⭐⭐</div>
                <p style="font-style: italic; margin-bottom: 15px; color: #555;">« Restaurant très sympa et très propre. Le personnel est très sympathique et souriant, le poisson braisé et l'attiéké sont un régal. »</p>
                <strong>— J. Jamy</strong>
            </div>

        </div>

        <!-- Bouton d'action Google Maps -->
        <div style="margin-top: 40px;">
            <a href="https://www.google.com/search?q=djorro+restaurant+paris+17" target="_blank" class="btn-outline" style="display: inline-block; padding: 12px 25px; border: 2px solid var(--terracotta); color: var(--terracotta); border-radius: 6px; text-decoration: none; font-weight: 600;">
                📍 Voir plus d'avis ou laisser le vôtre sur Google
            </a>
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

    <!-- JS (Menu mobile) -->
    <script src="js/main.js"></script>
</body>
</html>