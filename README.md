# 📚 E-Commerce - Livres

Bienvenue sur **E-Livres**, un site e-commerce permettant d'acheter des livres en ligne. Ce projet est développé avec **PHP**, **MySQL**, et **WAMP**.

## Fonctionnalités

-  **Catalogue de livres** avec filtre par titre, auteur et prix
-  **Gestion du panier**
-  **Validation de commande** avec suivi
-  **Gestion des utilisateurs** (inscription, connexion, rôles admin/utilisateur)
-  **Interface administrateur** pour gérer les livres et les commandes
-  **Facturation des commandes** (En Développement)

## Technologies utilisées

- **Langage** : PHP 8+
- **Base de données** : MySQL
- **Serveur local** : WAMP (Apache, MySQL, PHPMyAdmin)
- **Front-end** : HTML, CSS (Flexbox, Grid), JavaScript
- **Librairies** : SwiperJS (carrousel), VichUploaderBundle (upload d'images)


## Installation

### 1️⃣ Prérequis
- Installer [WAMP](https://www.wampserver.com/)
- Activer **Apache** et **MySQL**
- Avoir **PHPMyAdmin** pour gérer la base de données

### 2️⃣ Cloner le projet dans le fichier "www" de wamp
```sh
git clone https://github.com/Baltore/E-commerce.git
cd E-commerce
```

### 3️⃣ Configurer la base de données
- Importer `ecommerce_books.sql` dans PHPMyAdmin
- Modifier **config/database.php** avec vos identifiants MySQL

### 4️⃣ Lancer le serveur
```sh
wampmanager.exe  # Démarrer WAMP
```
Accéder au site via : `http://localhost/E-commerce/views/home`

## Tester le site

- Compte User :

Mail : qays@qays.com
Mdp  : testtest

- Compte Admin :

Mail : admin@admin.com
Mdp  : adminadmin


## Améliorations futures
-  Ajout d'un système de notation et de commentaires
-  Intégration d'un système de livraison avancé
-  Ajout d'un tableau de bord admin avec statistiques
-  Facturation des commandes

## 🤝 Auteurs

Qays/Matthis


