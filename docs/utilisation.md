# Utilisation

Ce que l'extension change pour les personnes qui utilisent SuiteCRM, sans détail
technique.

## Ce qui change

Dans le module **Contacts**, le nom de famille est mis en majuscules au moment où le contact
est enregistré, quelle que soit la façon dont il a été saisi :

- sur la fiche : Marie crée le contact « Marie dupont », l'enregistre, et la fiche affiche
  « Marie DUPONT » ;
- en modifiant un contact : « lefèvre » s'enregistre « LEFÈVRE » (accents conservés) ;
- par une importation de fichier : « jean martin » devient « jean MARTIN » ;
- par une intégration, un workflow ou une mise à jour en masse.

Le **prénom n'est pas modifié**. Les noms à particule et les noms composés passent
entièrement en majuscules : « de la Fontaine » devient « DE LA FONTAINE »,
« martin-durand » devient « MARTIN-DURAND ».

La règle vaut pour **tous les utilisateurs**. Les prospects (Leads), les cibles et les
utilisateurs ne sont pas concernés.

## À l'installation

Les contacts **déjà présents** sont convertis une fois : un contact « durand » s'affiche
« DURAND » dès l'installation, sans que personne n'ait à le rouvrir. Leur date de
modification ne change pas.

## Réglages

Aucun.

## Données de démo

Aucune.

## Limites

- Seuls les contacts sont concernés.
- Les accents sont gardés : impossible d'obtenir « LEFEVRE » au lieu de « LEFÈVRE ».
- Désinstaller l'extension ne remet pas les noms en minuscules : SuiteCRM ne garde pas leur
  ancienne écriture.
- Un nom modifié directement dans la base de données, sans passer par SuiteCRM, n'est mis
  en majuscules qu'au prochain enregistrement du contact.
