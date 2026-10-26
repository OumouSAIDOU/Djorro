<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Action de suppression d'une réservation
if (isset($_GET['supprimer_resa'])) {
    $id_resa = (int)$_GET['supprimer_resa'];
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = :id");
    $stmt->execute(['id' => $id_resa]);
    header('Location: index.php?msg=resa_supprimee');
    exit();
}

// Actions de changement de statut (Confirmer / Annuler)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_resa = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'confirmer') {
        $stmt = $pdo->prepare("UPDATE reservations SET statut = 'confirmee' WHERE id = ?");
        $stmt->execute([$id_resa]);
        header('Location: index.php?msg=resa_confirmee');
        exit();
    } elseif ($action === 'annuler') {
        $stmt = $pdo->prepare("UPDATE reservations SET statut = 'annulee' WHERE id = ?");
        $stmt->execute([$id_resa]);
        header('Location: index.php?msg=resa_annulee');
        exit();
    }
}

// Récupération des réservations
$stmt_resa = $pdo->query("SELECT * FROM reservations ORDER BY date_resa DESC, heure_resa ASC");
$reservations = $stmt_resa->fetchAll();

// Récupération des commandes
$stmt_cmd = $pdo->query("SELECT * FROM commandes ORDER BY cree_le DESC");
$commandes = $stmt_cmd->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin | DJORRO</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background-color: #f8f1e9; color: var(--text-dark); }
        .admin-header { background-color: var(--deep-wood); color: #fff; padding: 15px 0; border-bottom: 3px solid var(--terracotta); }
        .admin-nav { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: #f5efe9; font-weight: 500; margin-left: 20px; }
        .admin-nav a.active { color: var(--gold); font-weight: 700; }
        .admin-card { background: #ffffff; border-radius: 12px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid var(--border-color); }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .admin-table th, .admin-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; }
        .admin-table th { background-color: #fdfaf5; color: var(--deep-wood); font-weight: 700; }
        
        /* Boutons et badges admin */
        .btn-danger { background-color: #d9534f; color: #fff; padding: 6px 10px; border-radius: 5px; font-size: 0.8rem; text-decoration: none; display: inline-block; }
        .btn-danger:hover { background-color: #c9302c; }
        .btn-confirm { background-color: #28a745; color: #fff; padding: 6px 10px; border-radius: 5px; font-size: 0.8rem; text-decoration: none; display: inline-block; margin-right: 4px; }
        .btn-confirm:hover { background-color: #218838; }
        .btn-cancel { background-color: #ffc107; color: #212529; padding: 6px 10px; border-radius: 5px; font-size: 0.8rem; text-decoration: none; display: inline-block; margin-right: 4px; }
        .btn-cancel:hover { background-color: #e0a800; }

        .badge-status { padding: 5px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 600; display: inline-block; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-danger  { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <!-- NAV ADMIN -->
    <header class="admin-header">
        <div class="container admin-nav">
            <h2 style="font-family: var(--font-heading); color: #fff;">DJORRO <span>Administarteur</span></h2>
            <div>
                <a href="index.php" class="active">Réservations & Commandes</a>
                <a href="menu.php">Gérer la Carte</a>
                <a href="logout.php" style="color: #ff9999;">Déconnexion 🚪</a>
            </div>
        </div>
    </header>

    <section class="section-padding">
        <div class="container">

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success" style="padding: 12px; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px;">
                    <?php 
                        if ($_GET['msg'] === 'resa_supprimee') echo "Réservation supprimée avec succès.";
                        elseif ($_GET['msg'] === 'resa_confirmee') echo "Réservation confirmée avec succès !";
                        elseif ($_GET['msg'] === 'resa_annulee') echo "Réservation marquée comme annulée.";
                    ?>
                </div>
            <?php endif; ?>

            <!-- TABLEAU DES RÉSERVATIONS DE TABLES -->
            <div class="admin-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="color: var(--terracotta);">📅 Réservations de Tables (<?php echo count($reservations); ?>)</h3>
                </div>

                <?php if (empty($reservations)): ?>
                    <p style="margin-top: 15px; color: var(--text-muted);">Aucune réservation enregistrée pour le moment.</p>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Client</th>
                                    <th>Téléphone & Email</th>
                                    <th>Couverts</th>
                                    <th>Notes</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $r): ?>
                                    <tr>
                                        <td><strong><?php echo date('d/m/Y', strtotime($r['date_resa'])); ?></strong> à <?php echo htmlspecialchars($r['heure_resa']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($r['nom']); ?></strong></td>
                                        <td>
                                            <a href="tel:<?php echo htmlspecialchars($r['telephone']); ?>" style="color: var(--terracotta); font-weight: 600;"><?php echo htmlspecialchars($r['telephone']); ?></a><br>
                                            <small><?php echo htmlspecialchars($r['email']); ?></small>
                                        </td>
                                        <td><span style="background: #fff3e0; color: #e65100; padding: 4px 10px; border-radius: 12px; font-weight: 700;"><?php echo $r['couverts']; ?> pers.</span></td>
                                        <td><?php echo !empty($r['remarque']) ? htmlspecialchars($r['remarque']) : '<i>-</i>'; ?></td>
                                        <td>
                                            <?php 
                                                $statut = $r['statut'] ?? 'en_attente';
                                                if ($statut === 'confirmee') {
                                                    echo '<span class="badge-status badge-success">Confirmée</span>';
                                                } elseif ($statut === 'annulee') {
                                                    echo '<span class="badge-status badge-danger">Annulée</span>';
                                                } else {
                                                    echo '<span class="badge-status badge-warning">En attente</span>';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($statut !== 'confirmee'): ?>
                                                <a href="index.php?action=confirmer&id=<?php echo $r['id']; ?>" class="btn-confirm" onclick="return confirm('Confirmer cette réservation ?');">✅ Confirmer</a>
                                            <?php endif; ?>

                                            <?php if ($statut !== 'annulee'): ?>
                                                <a href="index.php?action=annuler&id=<?php echo $r['id']; ?>" class="btn-cancel" onclick="return confirm('Annuler cette réservation ?');">❌ Annuler</a>
                                            <?php endif; ?>

                                            <a href="index.php?supprimer_resa=<?php echo $r['id']; ?>" class="btn-danger" onclick="return confirm('Supprimer définitivement cette réservation ?');">🗑️ Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TABLEAU DES COMMANDES CLICK & COLLECT -->
            <div class="admin-card">
                <h3 style="color: var(--gold);">🛍️ Commandes Click & Collect (<?php echo count($commandes); ?>)</h3>

                <?php if (empty($commandes)): ?>
                    <p style="margin-top: 15px; color: var(--text-muted);">Aucune commande enregistrée pour le moment.</p>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Téléphone</th>
                                    <th>Retrait à</th>
                                    <th>Total</th>
                                    <th>Détail Panier</th>
                                    <th>Reçue le</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($commandes as $c): ?>
                                    <tr>
                                        <td><strong>#<?php echo htmlspecialchars($c['numero_commande']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($c['nom_client']); ?></td>
                                        <td><a href="tel:<?php echo htmlspecialchars($c['telephone']); ?>"><?php echo htmlspecialchars($c['telephone']); ?></a></td>
                                        <td><span style="color: var(--terracotta); font-weight: 700;"><?php echo htmlspecialchars($c['heure_retrait']); ?></span></td>
                                        <td><strong><?php echo number_format($c['total'], 2, ',', ' '); ?> €</strong></td>
                                        <td><small><?php echo htmlspecialchars($c['details_panier']); ?></small></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($c['cree_le'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

</body>
</html>