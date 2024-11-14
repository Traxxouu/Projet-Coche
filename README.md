# Projet Coche

**<a href="https://coche.rf.gd/?i=1">Projet Coche</a>** est un site de gestion de tâches permettant aux utilisateurs de créer, suivre et gérer leurs listes de tâches de manière intuitive et interactive. Ce projet est conçu pour être utilisé en local avec WampServer pour faciliter le développement et le test.
![Accueil](https://github.com/Traxxouu/Projet-Coche/blob/main/image/accueil_image.png)

## Fonctionnalités

- **Gestion des utilisateurs** : Enregistrement et connexion sécurisés, mot de passe Hasher.
- **Création de listes de tâches** : Les utilisateurs peuvent créer plusieurs listes de tâches, visualiser leur progression et organiser les tâches par statut (en cours, terminées).
![Liste de tâches](https://github.com/Traxxouu/Projet-Coche/blob/main/image/tache_image.png)

- **Tableau de bord personnalisé** : Affichage des informations utilisateur.
![Tableau de bord](https://github.com/Traxxouu/Projet-Coche/blob/main/image/dashboard_image.png)

- **Export/Import des listes** : Possibilité d'exporter et d'importer des listes sous forme de fichier CSV pour faciliter la gestion.
- **Interface interactive** : Application d'un design moderne, interactif et responsive, adapté à différents types d'appareils.
<p align="center">
  <img src="https://github.com/Traxxouu/Projet-Coche/blob/main/image/vue_mobile_app.jpg" alt="Vue Mobile" width="300"/>
</p>

Le site du projet: https://coche.rf.gd/

## Installation et configuration

1. **Cloner le repository** :

   ```bash
   git clone https://github.com/Traxxouu/Projet-Coche.git
   cd Projet-Coche

## Configuration de la base de données

Ce projet utilise une base de données MySQL. Les identifiants pour la connexion locale sont les suivants :

```php
// Connexion à la BDD en local sur WampServer
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";
```
- Créez une base de données nommée ``db_coche`` sur votre serveur MySQL local.
- Importez les tables et données nécessaires dans cette base de données en utilisant le script SQL [db_coche.sql](https://github.com/Traxxouu/Projet-Coche/blob/main/db_coche.sql) fournis ou en créant les tables selon les spécifications du projet.

## Configurer WampServer

1. Téléchargez et installez [WampServer](https://www.wampserver.com/).

2. Placez le projet dans le dossier `www` de WampServer (par défaut, généralement situé dans `C:\wamp64\www\`).

3. Lancez WampServer et assurez-vous que les services Apache et MySQL sont actifs (icône verte dans la barre d'état système).

4. Accédez au projet via votre navigateur en entrant l'URL suivante :

   ```http
   http://localhost/Projet-Coche

## Utilisation

- **Page d'accueil** : Affiche une introduction au site avec des options pour se connecter ou s'inscrire.
- **Connexion / Inscription** : Les utilisateurs peuvent créer un compte ou se connecter pour accéder à leurs listes de tâches.
- **Tableau de bord** : Une fois connectés, les utilisateurs accèdent à leur tableau de bord personnalisé avec la possibilité d'exporter ou d'importer des données et de changer le background de l'accueil.
- **Gestion des tâches** : Les utilisateurs peuvent créer, éditer, marquer comme complètes ou supprimer des tâches dans leurs listes.

## Technologies utilisées

- **Front-end** : HTML, CSS, JavaScript
- **Back-end** : PHP, MySQL
- **Serveur local** : WampServer pour l'exécution en local

## Contribution

Les contributions sont les bienvenues ! Pour proposer des améliorations, n'hésitez pas à forker le projet, puis soumettre une pull request.




