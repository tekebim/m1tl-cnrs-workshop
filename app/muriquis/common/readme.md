# Pour modifier le css et le js commun

## Etape 1 - Installer les dependances de package.json
- Se rendre à la racine du dossier ./common/, puis effecter la commande
```
npm install
# ou
yarn
```

## Etape 2 - Effectuer les modifications dans les fichiers css ou js
- Les fichiers se trouvent dans :
  `src/scripts`
  `src/styles`
  
## Etape 3 - Lancer la commander pour compiler
```
npm run build
yarn build
```
```
# independemment juste pour les styles
npm run build:css
# ou
yarn build:css

# independemment juste pour les scripts
npm run build-js
# ou
yarn build-js
```
