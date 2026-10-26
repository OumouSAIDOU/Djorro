<?php
/**
 * Helper d'envoi d'e-mails de notification pour DJORRO Restaurant
 */

// Votre adresse e-mail qui recevra toutes les réservations et commandes
define('RESTAURANT_EMAIL', 'oumouwonkoy@yahoo.com');
define('RESTAURANT_NAME', 'DJORRO Restaurant');

/**
 * Envoie un mail de notification de Réservation
 */
function envoyer_notification_reservation($nom, $email, $telephone, $couverts, $date, $heure, $remarque) {
    $to = RESTAURANT_EMAIL;
    $sujet = "=?UTF-8?B?" . base64_encode("📅 Nouvelle Réservation Table - " . $nom) . "?=";

    // En-têtes optimisés pour éviter le dossier spam (Yahoo / Gmail)
    $headers   = array();
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/html; charset=UTF-8";
    $headers[] = "From: DJORRO Restaurant <no-reply@djorro-restaurant.fr>";
    $headers[] = "Reply-To: $nom <$email>";
    $headers[] = "X-Mailer: PHP/" . phpversion();

    $contenu_html = '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Nouvelle Réservation</title>
    </head>
    <body style="font-family: Arial, sans-serif; background-color: #fdfaf5; padding: 20px; color: #221a15;">
        <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border: 2px solid #d48b16; border-radius: 12px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h2 style="color: #c85a32; margin-top: 0; border-bottom: 2px solid #f8f1e9; padding-bottom: 10px;">📅 Nouvelle Demande de Réservation</h2>
            <p style="font-size: 15px; color: #6b5c52;">Vous avez reçu une nouvelle réservation depuis le site web :</p>
            
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr style="background: #f8f1e9;"><td style="padding: 12px; font-weight: bold; width: 35%;">Client :</td><td style="padding: 12px;">' . $nom . '</td></tr>
                <tr><td style="padding: 12px; font-weight: bold;">Téléphone :</td><td style="padding: 12px;"><a href="tel:' . $telephone . '" style="color: #c85a32; font-weight: bold;">' . $telephone . '</a></td></tr>
                <tr style="background: #f8f1e9;"><td style="padding: 12px; font-weight: bold;">E-mail client :</td><td style="padding: 12px;">' . $email . '</td></tr>
                <tr><td style="padding: 12px; font-weight: bold;">Nombre de convives :</td><td style="padding: 12px; font-size: 16px; font-weight: bold;">' . $couverts . ' personne(s)</td></tr>
                <tr style="background: #f8f1e9;"><td style="padding: 12px; font-weight: bold;">Date & Heure :</td><td style="padding: 12px; color: #c85a32; font-weight: bold;">Le ' . $date . ' à ' . $heure . '</td></tr>
                <tr><td style="padding: 12px; font-weight: bold;">Note / Demande :</td><td style="padding: 12px;">' . (!empty($remarque) ? nl2br($remarque) : '<i>Aucune demande particulière</i>') . '</td></tr>
            </table>

            <div style="background-color: #f8f1e9; padding: 15px; border-radius: 8px; text-align: center; margin-top: 20px;">
                <p style="margin: 0; font-size: 13px; color: #2b1d14;">💡 <strong>Conseil :</strong> Contactez le client au <strong>' . $telephone . '</strong> pour lui confirmer sa réservation.</p>
            </div>
        </div>
    </body>
    </html>
    ';

    return mail($to, $sujet, $contenu_html, implode("\r\n", $headers));
}

/**
 * Envoie un mail de notification de Commande Click & Collect
 */
function envoyer_notification_commande($numero_commande, $nom, $telephone, $heure_retrait, $panier, $total, $notes) {
    $to = RESTAURANT_EMAIL;
    $sujet = "=?UTF-8?B?" . base64_encode("🛍️ Commande Click & Collect N° " . $numero_commande) . "?=";

    $headers   = array();
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/html; charset=UTF-8";
    $headers[] = "From: DJORRO Restaurant <no-reply@djorro-restaurant.fr>";
    $headers[] = "X-Mailer: PHP/" . phpversion();

    $liste_plats = "";
    foreach ($panier as $item) {
        $liste_plats .= '<tr>
            <td style="padding: 10px; border-bottom: 1px solid #e8ded2;">' . $item['nom'] . '</td>
            <td style="padding: 10px; border-bottom: 1px solid #e8ded2; text-align: right; font-weight: bold; color: #c85a32;">' . number_format($item['prix'], 2, ',', ' ') . ' €</td>
        </tr>';
    }

    $contenu_html = '
    <!DOCTYPE html>
    <html lang="fr">
    <head><meta charset="utf-8"></head>
    <body style="font-family: Arial, sans-serif; background-color: #fdfaf5; padding: 20px; color: #221a15;">
        <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border: 2px solid #d48b16; border-radius: 12px; padding: 25px;">
            <h2 style="color: #c85a32; margin-top: 0; border-bottom: 2px solid #f8f1e9; padding-bottom: 10px;">🛍️ Commande N° ' . $numero_commande . '</h2>
            
            <div style="background: #f8f1e9; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>Client :</strong> ' . $nom . '</p>
                <p style="margin: 5px 0;"><strong>Téléphone :</strong> <a href="tel:' . $telephone . '" style="color: #c85a32;">' . $telephone . '</a></p>
                <p style="margin: 5px 0; color: #c85a32; font-size: 16px;"><strong>Heure de retrait :</strong> ' . $heure_retrait . '</p>
                <p style="margin: 5px 0;"><strong>Remarque cuisine :</strong> ' . (!empty($notes) ? nl2br($notes) : '<i>Aucune</i>') . '</p>
            </div>

            <h3 style="color: #2b1d14;">Détail des plats à préparer :</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                <thead>
                    <tr style="background: #2b1d14; color: #ffffff;">
                        <th style="padding: 10px; text-align: left;">Plat</th>
                        <th style="padding: 10px; text-align: right;">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $liste_plats . '
                </tbody>
            </table>

            <div style="text-align: right; font-size: 18px; color: #c85a32; font-weight: bold; padding-top: 10px; border-top: 2px solid #2b1d14;">
                Total à encaisser : ' . number_format($total, 2, ',', ' ') . ' €
            </div>
        </div>
    </body>
    </html>
    ';

    return mail($to, $sujet, $contenu_html, implode("\r\n", $headers));
}