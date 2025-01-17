# ScrutinApp - Plateforme de Vote en Ligne

ScrutinApp est une application web permettant de créer, gérer, et participer à des scrutins en ligne. Ce projet met en œuvre plusieurs systèmes de vote (majoritaire, à deux tours, Borda, Condorcet) pour répondre à des besoins variés en matière de consultation.

## Fonctionnalités Principales

### 1. Page d'accueil

- Liste des scrutins ouverts.
- Affichage des 10 derniers scrutins actifs.
- Texte de présentation personnalisé pour l'utilisateur connecté.

### 2. Création de consultations

- Formulaire permettant de créer une nouvelle consultation avec :
  - Question.
  - Dates d'ouverture et de fermeture.
  - Type de vote (majoritaire, à deux tours, Borda, Condorcet).
  - Options associées.
- Sauvegarde des données dans la base.
- Redirection vers la page d'accueil avec un récapitulatif.

### 3. Vote

- Formulaire dynamique permettant de voter pour un scrutin ouvert.
- Validation des votes (vérification d’un vote existant).
- Enregistrement du vote dans la base de données.
- Message de confirmation ou d'erreur affiché après le vote.

### 4. Affichage des résultats

- Lecture des votes dans la base de données.
- Calcul des résultats en fonction du système de vote choisi.
- Affichage des résultats sous forme de liste et des scores.

### 5. Gestion des utilisateurs

- Inscription avec pseudonyme et rôle.
- Connexion sécurisée avec validation des identifiants.

### 6. Profil utilisateur

- Affichage des informations personnelles (nom, email, pseudonyme, rôle).
- Liste des consultations auxquelles l’utilisateur a participé.

## Architecture du Projet

### Structure des Dossiers

- **`public`** : Contient le point d'entrée principal (`index.php`).
- **`templates`** : Contient les vues (écrans utilisateur comme `home.php`, `profile.php`, `createElection.php`).
- **`src`** : Logique métier (à travers des classes comme `ElectionSystem.php`).
- **`vendor`** : Dépendances gérées par Composer.
- **`composer.json`** : Fichier de configuration des dépendances.

### Base de Données

- **Tables principales :**
  - `users` : Informations des utilisateurs.
  - `elections` : Informations des scrutins.
  - `options` : Options associées à chaque scrutin.
  - `votes` : Enregistrement des votes des utilisateurs.

## Prérequis

- PHP ≥7.4
- Serveur web (Apache ou Nginx).
- MySQL/MariaDB.
- Composer pour la gestion des dépendances.

## Installation

1. Clonez le dépôt :
   ```bash
   git clone <url_du_dépôt>
   ```
2. Naviguez dans le répertoire du projet :
   ```bash
   cd scrutin_app
   ```
3. Installez les dépendances :
   ```bash
   composer install
   ```
4. Configurez la base de données dans `templates/db_connect.php` :
   ```php
   $dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
   $username = 'root';
   $password = '';
   ```
5. Importez le fichier SQL de la base de données (fichier `scrutin.sql` si fourni).
6. Lancez le serveur local :
   ```bash
   php -S localhost:8000 -t public
   ```

## Utilisation

1. Accédez à l’application dans votre navigateur :
   ```
   http://localhost:8000
   ```
2. Inscrivez-vous ou connectez-vous pour commencer.
3. Créez des scrutins, votez et visualisez les résultats.

## Fonctionnalités Futures

- Implémentation d'un tableau de bord administrateur.
- Ajout d'une API REST pour une intégration avec des applications tierces.
- Analyse des tendances de vote avec des graphiques interactifs.

## Contributeurs

- Abiola Akobi et Defarge Émilie
- Toute contribution est la bienvenue. Veuillez soumettre vos suggestions via des pull requests.

---

Merci d'utiliser ScrutinApp !

Parfait

