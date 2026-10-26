# 🍽️ DJORRO Restaurant — Site Web & Click & Collect

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-Custom-1572B6?style=for-the-badge&logo=css3&logoColor=white)

Plateforme web moderne et intuitive développée pour le restaurant **DJORRO** (Paris 17e), combinant la mise en valeur des spécialités culinaires africaines, la réservation en ligne et un système complet de **Click & Collect** sans commission.

---

## 🚀 Fonctionnalités principales

### 📱 Côté Client
- **Catalogue & Menu Dynamique :** Filtrage par catégories (Entrées, Plats, Desserts, Boissons) avec sélecteurs de quantité interactifs (`-` / `+`).
- **Panier en Temps Réel :** Gestion du panier côté client via `localStorage` pour une expérience fluide et un maintien du panier lors de la navigation.
- **Double Modèle de Commande :**
  - **Click & Collect direct :** Prise de commande fluide sur le site avec retrait et paiement sur place au restaurant.
  - **Redirection Livraison :** Intégration transparente vers la plateforme partenaire (**Uber Eats**).
- **Avis Clients Google :** Intégration dynamique des avis vérifiés et de la note Google pour renforcer la preuve sociale.
- **Design Responsive & Soigné :** Interface adaptée au mobile et respectant la charte graphique chaleureuse du restaurant.

### 🛠️ Côté Administration (`/admin`)
- **Tableau de Bord :** Suivi et gestion en temps réel des commandes Click & Collect reçues et des demandes de réservation.
- **Gestion des Statuts :** Modification de l'état des commandes et réservations.

---

## 🛠️ Stack Technique

- **Back-end :** PHP (Architecture légère sans framework lourd)
- **Front-end :** HTML5, CSS3 sur-mesure (variables CSS, Flexbox, CSS Grid), JavaScript Vanille (ES6+)
- **Base de données :** MySQL
- **Gestion d'état :** `localStorage` (Persistance du panier)

---

## 📂 Structure du Projet

```text
├── admin/                  # Espace d'administration (Back-office)
│   └── index.php           # Tableau de bord gérant
├── css/                    # Feuilles de styles CSS
├── img/                    # Images des plats, logos et visuels
├── js/
│   └── panier.js           # Logique JS du panier et localStorage
├── config.php              # Connexion à la base de données MySQL
├── index.php               # Page d'accueil & Avis clients
├── menu.php                # Catalogue interactif des menus
├── commande.php            # Récapitulatif du panier & Checkout
└── reservation.php         # Formulaire de réservation de table
```

---

## ⚙️ Installation & Configuration

1. **Cloner le dépôt :**
   ```bash
   git clone https://github.com/votre-compte/djorro-restaurant.git
   cd djorro-restaurant
   ```

2. **Configurer la base de données :**
   - Créez une base de données MySQL (ex: `djorro_db`).
   - Importez le fichier SQL de structure (si fourni).
   - Configurez vos identifiants dans le fichier `config.php` :
     ```php
     $host = "localhost";
     $db   = "djorro_db";
     $user = "votre_utilisateur";
     $pass = "votre_mot_de_passe";
     ```

3. **Lancer le serveur local :**
   - Utilisez un serveur local type XAMPP, WAMP, MAMP ou PHP CLI :
     ```bash
     php -S localhost:8000
     ```

---

## 👨‍💻 Développeur

Projet conçu et développé avec soin pour accompagner la digitalisation des restaurants.

- **GitHub :** [@votre-pseudo](https://github.com/votre-pseudo)
- **Contact :** [Votre Email / Téléphone]