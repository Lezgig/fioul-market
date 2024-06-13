installation
========

1. docker-compose up
2. dans un terminal faire : "make connect" ou si vous n'avez pas makefile : "docker exec -it apache sh"
3. composer i


test-dev
========

Un stagiaire à créer le code contenu dans le fichier src/Controller/Home.php

Celui permet de récupérer des urls via un flux RSS ou un appel à l’API NewsApi. 
Celles ci sont filtrées (si contient une image) et dé doublonnées. 
Enfin, il faut récupérer une image sur chacune de ces pages.

Le lead dev n'est pas très satisfait du résultat, il va falloir améliorer le code.

Pratique : 
1. Revoir complètement la conception du code (découper le code afin de pouvoir ajouter de nouveaux flux simplement) 

Questions théoriques : 
1. Que mettriez-vous en place afin d'améliorer les temps de réponses du script
    -> réponse : j'y ai ajouté un système de cache peu élaboré qui permet de diminuer le temps de réponse.
2. Comment aborderiez-vous le fait de rendre scalable le script (plusieurs milliers de sources et images)
    -> réponse : on peut plancher sur plusieurs solutions (certaines sont excluantes): 
        - une architecture micro-service avec un une api-gateway + load balancer pour répartir la charge
        - servir des résultats déjà en cache et utiliser un système de file d'attente pour gérer de manière asynchrone les tâches visant à actualiser le cache coté backend
        - optimisation des requêtes (compression...)
        - utilisation d'un flux en streaming (kafka) pour consommer les données en temps réel
