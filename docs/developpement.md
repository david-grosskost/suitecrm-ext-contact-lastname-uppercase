# Développement

Les commandes `make`, la CI et le format des extensions viennent de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation), à la
version de `composer.json`. Cette page ne décrit que ce qui est propre à l'extension.

## Vérifier

```bash
make extension@validate   # format : manifeste, composer.json, place des fichiers
make quality@lint         # style du code, scripts
make quality@stan         # analyse statique (PHPStan, niveau max)
make test@unit            # tests unitaires
make package@build        # paquet du Chargeur de module, dans build/
make package@scan         # le paquet passe le scanner de SuiteCRM
```

La CI relance les mêmes contrôles sur chaque pull request.

## Tests

- `tests/LastNameTest.php` : la règle, sur les exemples de l'entretien (minuscules,
  accents, particules, noms composés) et les cas limites (vide, espaces, chiffres,
  noms encodés tels que SuiteCRM les tient avant l'enregistrement) ;
- `tests/ContactSaveTest.php` : le hook appelé comme SuiteCRM l'appelle (`$bean`,
  `'before_save'`, les arguments de `save()` ou `null`), nom encodé par SuiteCRM, prénom inchangé, nom vide laissé
  tel quel.

SuiteCRM n'est pas installé pour les tests : `tests/fixtures/SugarBean.php` en donne une
version minimale.

## Tester sur une instance locale

1. Dans suitecrm-core, créer d'abord un contact « durand » (en minuscules), extension
   désinstallée.
2. `make package@build` : le paquet est dans
   `build/contact-lastname-uppercase-<version>.zip`.
3. Sur l'instance locale de suitecrm-core, **Administration → Chargeur de module** :
   **Choisir un fichier**, **Télécharger**, puis **Installer**.
4. Refaire les exemples de [Utilisation](utilisation.md) : « durand » s'affiche
   « DURAND » ; créer « Marie dupont » ; modifier un nom en « lefèvre » ; importer
   « jean martin ». Puis lancer le test de régression dans suitecrm-core :
   `make test@regression`.

Avant un essai qui touche aux données : `make db@dump` dans suitecrm-core, pour revenir en
arrière avec `make db@restore`.

## Publier une version

Pull request relue, puis merge (squash) : release-please ouvre la pull request
« chore(main): release x.y.z ». La merger crée le tag, la release GitHub et son paquet.
L'image de suitecrm-core la récupère avec
`make composer@update PACKAGES="david-grosskost/suitecrm-ext-contact-lastname-uppercase"`.

Mettre à jour `README.md` et `docs/` dans la même pull request que le changement qu'ils
décrivent.
