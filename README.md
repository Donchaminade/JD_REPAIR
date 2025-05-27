# Gestionnaire de Réparations

Ce projet est une application web de gestion des réparations, factures, utilisateurs et traitements, destinée à faciliter le suivi et l'administration des interventions techniques.

## Fonctionnalités

- Gestion des demandes de réparation (création, modification, suppression)
- Gestion des factures associées aux réparations
- Suivi des traitements des réparations
- Administration des utilisateurs
- Tableau de bord pour la visualisation des statistiques

## Structure du projet

```
/
├── index.html
├── index.php
├── logout.php
├── reparationdb (1).sql
├── admin/
│   ├── dashboard.php
│   ├── demande/
│   ├── facture/
│   ├── reparation/
│   ├── traitement/
│   └── utilisateurs/
├── config/
│   └── db.php
├── includes/
│   ├── footer.php
│   ├── header.php
│   └── sidebar.php
├── public/
│   └── index.html
```

## Installation

1. **Cloner le dépôt**
   ```sh
   git clone <url-du-depot>
   ```

2. **Configurer la base de données**
   - Importer le fichier `reparationdb (1).sql` dans votre serveur MySQL/MariaDB.
   - Modifier les paramètres de connexion dans [`config/db.php`](config/db.php) selon votre environnement.

3. **Lancer l'application**
   - Placer le projet dans le dossier web de votre serveur (ex: `htdocs` pour XAMPP).
   - Accéder à `http://localhost/nom_du_projet/index.php` via votre navigateur.

## Dépendances

- PHP >= 7.0
- MySQL/MariaDB
- Serveur web (Apache, Nginx, etc.)

## Utilisation

- Connectez-vous avec vos identifiants.
- Naviguez via le menu latéral pour accéder aux différentes sections : demandes, réparations, factures, traitements, utilisateurs.
- Utilisez les formulaires pour ajouter, modifier ou supprimer des entrées.

## Structure des dossiers

- [`admin/`](admin/) : Contient les modules principaux (demandes, factures, réparations, traitements, utilisateurs)
- [`config/`](config/) : Fichiers de configuration (connexion à la base de données)
- [`includes/`](includes/) : Fichiers inclus (header, footer, sidebar)
- [`public/`](public/) : Fichiers publics accessibles directement

## Contribution

Les contributions sont les bienvenues ! Merci de soumettre vos pull requests ou d’ouvrir une issue pour toute suggestion ou bug.

## Licence

Ce projet est sous licence MIT.

---

© 2024 - Gestionnaire de Réparations
