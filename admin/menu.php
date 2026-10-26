<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$message = "";

// 1. Action : Ajouter un plat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_plat') {
    $nom          = htmlspecialchars(trim($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $description  = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
    $prix         = floatval($_POST['prix']);
    $categorie_id = (int)$_POST['categorie_id'];
    $populaire    = isset($_POST['populaire']) ? 1 : 0;

    if (!empty($nom) && $prix > 0 && $categorie_id > 0) {
        $stmt = $pdo->prepare("INSERT INTO plats (nom, description, prix, categorie_id, disponible, populaire) VALUES (:nom, :description, :prix, :cat, 1, :populaire)");
        $stmt->execute([
            'nom'         => $nom,
            'description' => $description,
            'prix'        => $prix,
            'cat'         => $categorie_id,
            'populaire'   => $populaire
        ]);
        $message = "Le plat « $nom » a été ajouté au menu !";
    }
}

// 2. Action : Changer le statut disponible / indisponible
if (isset($_GET['toggle_dispo'])) {
    $id_plat = (int)$_GET['toggle_dispo'];
    $stmt = $pdo->prepare("UPDATE plats SET disponible = NOT disponible WHERE id = :id");
    $stmt->execute(['id' => $id_plat]);
    header('Location: menu.php');
    exit();
}

// 3. Action : Supprimer un plat
if (isset($_GET['supprimer_plat'])) {
    $id_plat = (int)$_GET['supprimer_plat'];
    $stmt = $pdo->prepare("DELETE FROM plats WHERE id = :id");
    $stmt->execute(['id' => $id_plat]);
    header('Location: menu.php?msg=plat_supprime');
    exit();
}

// Récupérer la liste des catégories et des plats
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC")->fetchAll();
$plats = $pdo->query("SELECT p.*, c.nom as categorie_nom FROM plats p JOIN categories c ON p.categorie_id = c.id ORDER BY c.nom ASC, p.nom ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Carte | Admin DJORRO</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background-color: #f8f1e9; color: var(--text-dark); }
        .admin-header { background-color: var(--deep-wood); color: #fff; padding: 15px 0; border-bottom: 3px solid var(--terracotta); }
        .admin-nav { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: #f5efe9; font-weight: 500; margin-left: 20px; }
        .admin-nav a.active { color: var(--gold); font-weight: 700; }
        .admin-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        .admin-card { background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid var(--border-color); }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .admin-table th, .admin-table td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; }
        .badge-dispo { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 10px; font-weight: 600; font-size: 0.75rem; }
        .badge-rupture { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 10px; font-weight: 600; font-size: 0.75rem; }
    </style>
</head>
<body>

    <header class="admin-header">
        <div class="container admin-nav">
            <h2 style="font-family: var(--font-heading); color: #fff;">DJORRO <span>Panel Admin</span></h2>
            <div>
                <a href="index.php">Réservations & Commandes</a>
                <a href="menu.php" class="active">Gérer la Carte</a>
                <a href="logout.php" style="color: #ff9999;">Déconnexion 🚪</a>
            </div>
        </div>
    </header>

    <section class="section-padding">
        <div class="container">

            <?php if (!empty($message)): ?>
                <div class="alert alert-success" style="padding: 12px; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="admin-grid">

                <!-- FORMULAIRE AJOUTER UN PLAT -->
                <div class="admin-card">
                    <h3 style="color: var(--terracotta); margin-bottom: 15px;">➕ Ajouter un plat</h3>
                    
                    <form action="menu.php" method="POST">
                        <input type="hidden" name="action" value="ajouter_plat">

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display:block; font-weight:600;">Nom du plat *</label>
                            <input type="text" name="nom" required placeholder="Ex: Capitaine Braisé Grand Format" style="width:100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display:block; font-weight:600;">Catégorie *</label>
                            <select name="categorie_id" required style="width:100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Choisir une catégorie...</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display:block; font-weight:600;">Prix (€) *</label>
                            <input type="number" step="0.50" name="prix" required placeholder="18.00" style="width:100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="display:block; font-weight:600;">Description</label>
                            <textarea name="description" rows="3" placeholder="Ingrédients, accompagnements..." style="width:100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label><input type="checkbox" name="populaire" value="1"> Marquer comme "⭐ Best-seller"</label>
                        </div>

                        <button type="submit" class="btn-primary" style="width:100%; padding: 12px; border:none; border-radius: 6px; cursor:pointer;">Ajouter la spécialité</button>
                    </form>
                </div>

                <!-- TABLEAU DE LA CARTE ACTUELLE -->
                <div class="admin-card">
                    <h3 style="color: var(--deep-wood);">📖 Carte Actuelle (<?php echo count($plats); ?> plats)</h3>

                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Prix</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plats as $p): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($p['nom']); ?></strong></td>
                                        <td><small><?php echo htmlspecialchars($p['categorie_nom']); ?></small></td>
                                        <td><strong><?php echo number_format($p['prix'], 2, ',', ' '); ?> €</strong></td>
                                        <td>
                                            <?php if ($p['disponible'] == 1): ?>
                                                <span class="badge-dispo">Disponible</span>
                                            <?php else: ?>
                                                <span class="badge-rupture">En Rupture</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="menu.php?toggle_dispo=<?php echo $p['id']; ?>" style="font-size: 0.8rem; margin-right: 8px;">
                                                <?php echo $p['disponible'] == 1 ? 'Marquer Rupture' : 'Rendre Dispo'; ?>
                                            </a>
                                            <a href="menu.php?supprimer_plat=<?php echo $p['id']; ?>" style="color:red; font-size: 0.8rem;" onclick="return confirm('Supprimer ce plat ?');">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </section>

</body>
</html>