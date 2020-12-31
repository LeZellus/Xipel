#Documentation XIPEL Blog


##Lancer serveur SYMFONY :

``symfony server:start``

ou sur un port spécifique :

```symfony server:start --port=3000```

##Compiler les fichiers CSS/JS en fichiers builds pour la prod :

``yarn encore dev``

Ou si vous préférez npm :

``npm run dev``

###Compiler automatiquement :

``yarn encore dev --watch``

Ou si vous préférez npm :

``npm run watch``

##Deployer avec Heroku :

Déployer sur Heroku :

```git push heroku master```

Pour voir les logs Heroku :

```heroku logs --tail```
