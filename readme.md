# Workshop Tech Lead Groupe 2

## Pré-requis
Cet environnement nécessite d'avoir Docker d'installé sur la machine et qu'il soit lancé

## Etape 1 :
- Se placer à la racine du dossier et exécuter la commande suivante :
```
docker-compose up -d --build
```
Le but de cette commande est d'installer les dépendance PHP / MYSQL / NGINX pour faire tourner le projet

## Etape 2 : 
Modifier le vhost pour le nom de domaine en local :
- Sur mac, le fichier est /etc/hosts

Il faut ajouter :
```
127.0.0.1 workshop-cnrs.docker
::1 workshop-cnrs.docker
```

## Etape 3 : 
Vérifier la bon fonctionnement en connexion 
