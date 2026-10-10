# L'Atelier des couleurs – exercice TP

Exercices pour dossier professionnel – Site vitrine fictif d'atelier artistique.

Page d'accueil responsive et dynamique, avec un espace administrateur sécurisé permettant de modifier les horaires d'ouverture.

## Fonctionnalités

**Site public**
- Page d'accueil responsive (mobile-first) : présentation, offres, informations pratiques
- Horaires chargés depuis un fichier JSON avec `fetch`
- Badge « Ouvert maintenant » / « Fermé » calculé selon l'heure actuelle
- Message d'erreur affiché au visiteur si les horaires ne peuvent pas être chargés
- Année du pied de page mise à jour automatiquement

**Espace administrateur**
- Connexion par mot de passe (stocké sous forme d'empreinte, jamais en clair)
- Pages d'administration protégées par session
- Modification des horaires via un formulaire, avec message de confirmation
- Déconnexion

## Technologies

- HTML5, CSS3 (variables, Flexbox, Grid, media queries)
- JavaScript (fetch, async / await, manipulation du DOM)
- PHP natif (sessions, password_hash / password_verify)
- Stockage des données dans un fichier JSON
- Git / GitHub

## Structure du projet

```
├── index.html          Page d'accueil
├── style.css           Styles du site et de l'administration
├── script.js           Chargement des horaires, badge, année du footer
├── horaires.json       Horaires d'ouverture (lus par le site, modifiés par l'admin)
├── config.php          Empreinte du mot de passe admin (non versionné)
├── Images/             Logo et visuels
└── admin/
    ├── connexion.php   Formulaire de connexion
    ├── horaires.php    Modification des horaires (page protégée)
    └── deconnexion.php Fermeture de la session
```

## Installation

1. Installer [XAMPP](https://www.apachefriends.org/) et démarrer **Apache**.
2. Cloner le dépôt dans le dossier `htdocs` :
   ```bash
   git clone https://github.com/LessTC/Atelier-des-couleurs-exercice-TP.git
   ```
3. Générer l'empreinte du mot de passe administrateur (remplacer `mot_de_passe` par le mot de passe choisi) :
   ```bash
   C:\xampp\php\php.exe -r "echo password_hash('mot_de_passe', PASSWORD_DEFAULT);"
   ```
4. Créer un fichier `config.php` à la racine du projet, et y coller l'empreinte **entre guillemets simples** :
   ```php
   <?php
   define('ADMIN_HASH', 'coller_ici_l_empreinte');
   ```
5. Ouvrir le site dans le navigateur : `http://localhost/<nom-du-dossier>/`

> Le site doit être ouvert via `http://localhost`, et non en double-cliquant sur `index.html` : sinon, `fetch` est bloqué et le PHP n'est pas exécuté.

## Utilisation de l'espace administrateur

- Accès : lien « Espace administrateur » en bas de page, ou `admin/connexion.php`
- Après connexion, modifier les horaires puis cliquer sur « Enregistrer »
- Les nouveaux horaires apparaissent sur la page d'accueil au rechargement

## Sécurité

- Mot de passe stocké sous forme d'empreinte (`password_hash`), vérifié avec `password_verify`
- Identifiant de session régénéré à la connexion (protection contre la fixation de session)
- Jeton CSRF sur le formulaire de modification
- Validation des horaires côté serveur (format et cohérence)
- Échappement des affichages : `textContent` en JavaScript, `htmlspecialchars` en PHP
- Fichier `config.php` exclu du dépôt via `.gitignore`

## Pistes d'amélioration

- Modification des informations de contact
- Stockage des données dans une base de données relationnelle
- Ajout d'un identifiant et limitation des tentatives de connexion
- Menu de navigation adapté au mobile

## Auteur

Leslie Thiercelin – projet réalisé dans le cadre de la formation Développeur Web et Web Mobile (Studi).

## Licence

Voir le fichier [LICENSE](LICENSE).
