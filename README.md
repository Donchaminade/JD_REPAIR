# Gestionnaire de Réparations

Ce projet est une application web de gestion des réparations, factures, utilisateurs et traitements, destinée à faciliter le suivi et l'administration des interventions techniques pour une entreprise ou un atelier de réparation.

---

## Fonctionnalités

- **Gestion des demandes de réparation** : création, modification, suppression et suivi des demandes clients.
- **Gestion des réparations** : suivi de l’état d’avancement, affectation des techniciens, historique des interventions.
- **Gestion des factures** : génération, édition, suppression et suivi du paiement des factures liées aux réparations.
- **Gestion des traitements** : suivi des différentes étapes de traitement d’une réparation.
- **Gestion des utilisateurs** : administration des comptes utilisateurs (ajout, modification, suppression, gestion des rôles).
- **Tableau de bord** : visualisation synthétique des statistiques (nombre de réparations, factures, paiements, etc.).
- **Export PDF** : génération de factures ou rapports au format PDF via la librairie FPDF.
- **Sécurité** : gestion de l’authentification et des sessions utilisateurs.
- **Interface intuitive** : navigation par menu latéral, formulaires clairs, feedback utilisateur.

---

## Structure du projet

```
/
├── index.php                # Page d'accueil ou de redirection
├── logout.php               # Déconnexion utilisateur
├── README.md                # Ce fichier
├── reparationdb.sql         # Script SQL pour la base de données
├── admin/
│   ├── dashboard.php        # Tableau de bord administrateur
│   ├── demande/             # Gestion des demandes de réparation
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── index.php
│   │   ├── update.php
│   ├── facture/             # Gestion des factures
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── index.php
│   │   ├── update.php
│   ├── reparation/          # Gestion des réparations
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── index.php
│   │   ├── save_facture.php
│   │   ├── update.php
│   ├── traitement/          # Gestion des traitements
│   │   ├── create.php
│   │   ├── creattttteeee.php
│   ├── utilisateurs/        # Gestion des utilisateurs
├── config/
│   └── db.php               # Connexion à la base de données
├── includes/
│   ├── footer.php           # Pied de page commun
│   ├── header.php           # En-tête commun
│   └── sidebar.php          # Menu latéral
├── libs/
│   └── fpdf/                # Librairie FPDF pour l’export PDF
├── public/
│   ├── index.php            # Page publique
│   ├── jd.png               # Images utilisées dans l’interface
│   ├── rep.png
│   ├── rep1.png
│   ├── rep2.png
```

---

## Installation

### 1. Cloner le dépôt

```sh
git clone <url-du-depot>
```

### 2. Configurer la base de données

- Importer le fichier [`reparationdb.sql`](reparationdb.sql) dans votre serveur MySQL/MariaDB via phpMyAdmin ou en ligne de commande.
- Vérifier et adapter les paramètres de connexion dans [`config/db.php`](config/db.php) :

```php
$host = 'localhost';
$dbname = 'reparationdb';
$user = 'root';
$pass = '';
```

### 3. Lancer l’application

- Placer le dossier du projet dans le répertoire web de votre serveur local (ex : `htdocs` pour XAMPP).
- Démarrer Apache et MySQL via XAMPP.
- Accéder à l’application via :  
  `http://localhost/JD_REPAIR/index.php`

---

## Dépendances

- **PHP** >= 7.0
- **MySQL/MariaDB**
- **Serveur web** (Apache, Nginx, etc.)
- **Librairie FPDF** (déjà incluse dans [`libs/fpdf/`](libs/fpdf/))
- **jsPDF** (chargé via CDN pour l’export PDF côté client)

---

## Utilisation

1. **Connexion**  
   Accédez à la page de connexion et entrez vos identifiants.

2. **Navigation**  
   Utilisez le menu latéral pour accéder aux différentes sections :  
   - Demandes
   - Réparations
   - Factures
   - Traitements
   - Utilisateurs
   - Tableau de bord

3. **Gestion des données**  
   - Ajoutez, modifiez ou supprimez des entrées via les formulaires dédiés.
   - Générez et téléchargez des factures au format PDF.
   - Suivez l’état des réparations et des paiements.

4. **Déconnexion**  
   Cliquez sur « Déconnexion » pour terminer votre session.

---

## Structure des dossiers

- [`admin/`](admin/) : Modules principaux (demandes, factures, réparations, traitements, utilisateurs)
- [`config/`](config/) : Fichiers de configuration (connexion à la base de données)
- [`includes/`](includes/) : Fichiers inclus (header, footer, sidebar)
- [`libs/fpdf/`](libs/fpdf/) : Librairie FPDF pour l’export PDF
- [`public/`](public/) : Fichiers publics et ressources (images, page d’accueil)

---

## Contribution

Les contributions sont les bienvenues !  
Pour proposer une amélioration ou signaler un bug :

1. Forkez le projet.
2. Créez une branche dédiée (`feature/ma-fonctionnalite`).
3. Commitez vos modifications.
4. Ouvrez une Pull Request.

N’hésitez pas à ouvrir une issue pour toute question ou suggestion.

---

## Sécurité

- Les accès sont protégés par une authentification.
- Les requêtes SQL utilisent PDO avec des requêtes préparées pour éviter les injections SQL.
- Les sessions sont utilisées pour la gestion des utilisateurs connectés.

---

## Licence

Ce projet est sous licence MIT.  
Vous pouvez l’utiliser, le modifier et le distribuer librement.

---

© 2024 - Gestionnaire de Réparations
