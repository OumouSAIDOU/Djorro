<?php
session_start();
require_once 'includes/db.php';

$message_succes = "";
$message_erreur = "";

// TRAITEMENT DU FORMULAIRE DE FINALISATION DE COMMANDE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider_commande'])) {
    $nom_client = trim($_POST['nom_client'] ?? '');
    $telephone  = trim($_POST['telephone'] ?? '');
    $heure_retrait = trim($_POST['heure_retrait'] ?? '');
    $details_panier = trim($_POST['details_panier'] ?? '');
    $total = floatval($_POST['total'] ?? 0);

    if (!empty($nom_client) && !empty($telephone) && !empty($heure_retrait) && !empty($details_panier)) {
        try {
            // Génération d'un numéro de commande unique (ex: CMD-8421)
            $numero_commande = 'CMD-' . rand(1000, 9999);

            $stmt = $pdo->prepare("
                INSERT INTO commandes (numero_commande, nom_client, telephone, heure_retrait, details_panier, total, cree_le) 
                VALUES (:numero, :nom, :tel, :heure, :details, :total, NOW())
            ");

            $stmt->execute([
                'numero'  => $numero_commande,
                'nom'     => $nom_client,
                'tel'     => $telephone,
                'heure'   => $heure_retrait,
                'details' => $details_panier,
                'total'   => $total
            ]);

            $message_succes = "Votre commande #$numero_commande a bien été enregistrée ! Vous recevrez votre confirmation.";
        } catch (PDOException $e) {
            $message_erreur = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    } else {
        $message_erreur = "Veuillez remplir tous les champs et vous assurer que votre panier n'est pas vide.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Finalisez votre commande Click & Collect chez DJORRO Restaurant à Paris 17e.">
    <title>Commander en Ligne | DJORRO Restaurant Paris 17</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border-color); }
        .cart-item-actions { display: flex; align-items: center; gap: 8px; }
        .btn-qty { background: #e0e0e0; border: none; padding: 2px 8px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-qty:hover { background: #ccc; }
        .btn-delete { color: #dc3545; background: none; border: none; cursor: pointer; font-size: 0.9rem; margin-left: 5px; }
        .order-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        @media (max-width: 768px) { .order-layout { grid-template-columns: 1fr; } }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-family: inherit; }
    </style>
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
    <a href="commande.php" class="active" style="position: relative;">
        Commander <span id="cartCount" style="display: none; background: var(--terracotta); color: white; padding: 2px 7px; border-radius: 50%; font-size: 0.75rem; margin-left: 5px; font-weight: 700;">0</span>
    </a>
    <a href="reservation.php" class="btn-primary">Réserver une table</a>
</nav>

            <button class="burger-menu" id="burgerBtn" aria-label="Ouvrir le menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <!-- BANNIÈRE DE LA PAGE COMMANDE -->
    <section class="menu-hero hero-commande" style="background: linear-gradient(to bottom, rgba(43, 29, 20, 0.35), rgba(43, 29, 20, 0.55)), url('img/plats/pastel.jpg') center/cover no-repeat fixed;">
        <div class="menu-hero-content text-center">
            <span class="sub-title" style="color: var(--gold);">Click & Collect</span>
            <h1>Finalisez votre Commande</h1>
            <p>Vérifiez vos plats et choisissez l'heure de votre retrait au restaurant.</p>
        </div>
    </section>

    <!-- SECTION COMMANDE & PANIER -->
    <section class="section-padding">
        <div class="container">

            <?php if (!empty($message_succes)): ?>
                <div class="alert alert-success" style="padding: 15px; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 30px; text-align: center;">
                    ✅ <?php echo $message_succes; ?><br>
                    <a href="menu.php" style="color: #155724; font-weight: bold; text-decoration: underline; margin-top: 10px; display: inline-block;">Retourner à la carte</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($message_erreur)): ?>
                <div class="alert alert-danger" style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 30px; text-align: center;">
                    ⚠️ <?php echo $message_erreur; ?>
                </div>
            <?php endif; ?>

            <div class="order-layout">
                
                <!-- COLONNE DE GAUCHE : RECAPITULATIF DU PANIER DYNAMIQUE -->
                <div class="cart-section">
                    <div class="card" style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid var(--border-color);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h3> Votre commande</h3>
                            <button type="button" onclick="viderPanier()" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 0.85rem;">Vider le panier</button>
                        </div>

                        <div id="cartDisplay">
                            <!-- Rempli dynamiquement en JavaScript -->
                        </div>

                        <hr style="margin: 20px 0; border: none; border-top: 1px solid var(--border-color);">

                        <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700;">
                            <span>Total à payer sur place :</span>
                            <span id="cartTotalDisplay">0,00 €</span>
                        </div>
                        
                        <div style="margin-top: 20px; text-align: center;">
                            <a href="menu.php" style="color: var(--terracotta); font-weight: 600; text-decoration: none;">+ Ajouter d'autres plats depuis la carte</a>
                        </div>
                    </div>
                </div>

                <!-- COLONNE DE DROITE : FORMULAIRE CLICK & COLLECT -->
                <div class="form-section">
                    <div class="card" style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid var(--border-color);">
                        <h3>🛍️ Informations de Retrait</h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">Remplissez vos coordonnées pour valider la commande Click & Collect.</p>

                        <form action="commande.php" method="POST" id="orderForm">
                            <!-- Champs cachés transmis au PHP pour enregistrer en BDD -->
                            <input type="hidden" name="details_panier" id="inputDetailsPanier">
                            <input type="hidden" name="total" id="inputTotal">
                            <input type="hidden" name="valider_commande" value="1">

                            <div class="form-group">
                                <label for="nom_client">Nom complet *</label>
                                <input type="text" id="nom_client" name="nom_client" required placeholder="Ex: Oumou Wonkoy">
                            </div>

                            <div class="form-group">
                                <label for="telephone">Numéro de téléphone *</label>
                                <input type="tel" id="telephone" name="telephone" required placeholder="Ex: 0612345678">
                            </div>

                            <div class="form-group">
                                <label for="heure_retrait">Heure de retrait souhaitée *</label>
                                <input type="time" id="heure_retrait" name="heure_retrait" required min="12:00" max="01:30">
                            </div>

                            <div class="payment-info" style="margin-top: 15px; margin-bottom: 20px; background: #fdfaf5; padding: 12px; border-radius: 8px;">
                                <p style="font-size: 0.85rem; color: var(--text-dark);">
                                    💳 <strong>Paiement au comptoir lors du retrait :</strong><br>
                                    Carte Bancaire, Espèces, Ticket Restaurant®.
                                </p>
                            </div>

                            <button type="submit" id="btnSubmitOrder" class="btn-primary" style="width: 100%; padding: 12px; font-size: 1rem; cursor: pointer;">
                                Valider ma commande Click & Collect
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
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
        <div class="footer-bottom text-center">
            <p>&copy; <?php echo date('Y'); ?> DJORRO Restaurant. Tous droits réservés. 
               <a href="admin/login.php" style="color: rgba(255,255,255,0.3); text-decoration: none; margin-left: 15px; font-size: 0.8rem;">🔒 Espace Gérant</a>
            </p>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="js/panier.js"></script>
    <script src="js/main.js"></script>
    <script>
        // Si la commande a été soumise avec succès, on vide le panier côté client
        <?php if (!empty($message_succes)): ?>
            localStorage.removeItem('djorro_panier');
            if (typeof updateCartCount === 'function') updateCartCount();
        <?php endif; ?>

        // Charger et afficher le panier dans commande.php
        function renderCartPage() {
            let panier = JSON.parse(localStorage.getItem('djorro_panier')) || [];
            const container = document.getElementById('cartDisplay');
            const totalDisplay = document.getElementById('cartTotalDisplay');
            const inputDetails = document.getElementById('inputDetailsPanier');
            const inputTotal = document.getElementById('inputTotal');
            const btnSubmit = document.getElementById('btnSubmitOrder');

            if (panier.length === 0) {
                container.innerHTML = "<p style='color: var(--text-muted); padding: 20px 0;'>Votre panier est vide pour le moment.</p>";
                totalDisplay.textContent = "0,00 €";
                if (inputDetails) inputDetails.value = "";
                if (inputTotal) inputTotal.value = "0";
                if (btnSubmit) btnSubmit.disabled = true;
                return;
            }

            if (btnSubmit) btnSubmit.disabled = false;

            let html = "";
            let totalGeneral = 0;
            let detailsArray = [];

            panier.forEach((item, index) => {
                let subtotal = item.prix * item.quantite;
                totalGeneral += subtotal;
                detailsArray.push(`${item.quantite}x ${item.nom} (${subtotal.toFixed(2)}€)`);

                html += `
                    <div class="cart-item">
                        <div>
                            <strong>${item.nom}</strong><br>
                            <small style="color: var(--text-muted);">${item.prix.toFixed(2)} € / unité</small>
                        </div>
                        <div class="cart-item-actions">
                            <button type="button" class="btn-qty" onclick="changeQty(${index}, -1)">-</button>
                            <span>${item.quantite}</span>
                            <button type="button" class="btn-qty" onclick="changeQty(${index}, 1)">+</button>
                            <span style="font-weight: 600; margin-left: 8px;">${subtotal.toFixed(2)} €</span>
                            <button type="button" class="btn-delete" onclick="removeItem(${index})">🗑️</button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            totalDisplay.textContent = totalGeneral.toFixed(2).replace('.', ',') + " €";
            
            if (inputDetails) inputDetails.value = detailsArray.join(' | ');
            if (inputTotal) inputTotal.value = totalGeneral.toFixed(2);
        }

        function changeQty(index, delta) {
            let panier = JSON.parse(localStorage.getItem('djorro_panier')) || [];
            if (panier[index]) {
                panier[index].quantite += delta;
                if (panier[index].quantite <= 0) {
                    panier.splice(index, 1);
                }
            }
            localStorage.setItem('djorro_panier', JSON.stringify(panier));
            renderCartPage();
            if (typeof updateCartCount === 'function') updateCartCount();
        }

        function removeItem(index) {
            let panier = JSON.parse(localStorage.getItem('djorro_panier')) || [];
            panier.splice(index, 1);
            localStorage.setItem('djorro_panier', JSON.stringify(panier));
            renderCartPage();
            if (typeof updateCartCount === 'function') updateCartCount();
        }

        function viderPanier() {
            localStorage.removeItem('djorro_panier');
            renderCartPage();
            if (typeof updateCartCount === 'function') updateCartCount();
        }

        document.addEventListener('DOMContentLoaded', renderCartPage);
    </script>
</body>
</html>