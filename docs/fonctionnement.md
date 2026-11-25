# Fonctionnement

Comment l'extension est faite, pour la personne qui la relit ou la fait évoluer.

## Fichiers

| Fichier | Rôle |
|---|---|
| `legacy/Extension/modules/Contacts/Ext/LogicHooks/contact-lastname-uppercase.php` | déclare le hook `before_save` des contacts |
| `legacy/include/contact-lastname-uppercase/ContactSave.php` | le hook : met `last_name` en majuscules avant l'enregistrement |
| `legacy/include/contact-lastname-uppercase/LastName.php` | la règle : le nom décodé, puis en majuscules, accents gardés |
| `database/install.php` | à l'installation : les noms des contacts existants mis en majuscules |
| `tests/LastNameTest.php`, `tests/ContactSaveTest.php` | les tests unitaires |
| `tests/fixtures/SugarBean.php` | un `SugarBean` minimal, pour les tests seulement |

La logique vit dans des classes testées sans SuiteCRM (`legacy/include/<slug>/`) ; la
déclaration faite à SuiteCRM ne fait que les appeler.

## Le hook

`before_save` de **Contacts** : SuiteCRM l'appelle à chaque `save()` d'un contact, donc
depuis la fiche, l'importation, l'API, les workflows et la mise à jour en masse. Seul
`last_name` change ; un nom vide ou absent est laissé tel quel.

Au moment du `before_save`, SuiteCRM a déjà encodé les valeurs en HTML (`purify_html`,
dans `cleanBean()` de `SugarBean::save`), parfois deux fois : « lefèvre » vaut
`lef&egrave;vre`, « O'Brien » `O&#039;Brien`, « & » `&amp;amp;`. Il les décode en écrivant la
ligne (`from_html`) : la base stocke les noms en clair. `LastName::upper` décode donc le nom
(jusqu'à trois fois), puis le met en majuscules, et rend du texte en clair. Mettre en
majuscules sans décoder laisserait « è » en minuscule (`&egrave;`) et abîmerait « & »
(`&AMP;`), ce qu'a montré l'essai dans SuiteCRM. Le script d'installation, qui lit les
noms en clair dans la base, applique la même règle.

La mise en majuscules utilise `mb_strtoupper` (UTF-8) : les accents sont gardés
(« è » → « È »), et « ß » devient « SS ».

## Données en base

Aucun champ ni aucune table créés. L'extension modifie la colonne `last_name` de la table
`contacts`.

## Installation et désinstallation

`database/install.php`, une seule fois, à la première installation : lit tous les contacts
dont le nom n'est pas vide, contacts supprimés compris (restaurés, ils reviennent en
majuscules), et écrit le nom en majuscules directement dans la table, seulement s'il
change. Passer par la table et non par `save()` laisse intacts la date de modification,
l'historique (audit) et les workflows des contacts. Le script renvoie le nombre de noms
modifiés, que SuiteCRM note dans son journal.

Pas de `database/uninstall.php` : il n'y a rien à défaire, l'ancienne écriture des noms
n'étant pas gardée. Après désinstallation, les noms restent en majuscules et les nouveaux
contacts s'enregistrent comme ils sont tapés.

## Points d'attention

- Le scanner du Chargeur de module refuse les fonctions à callback
  (`preg_replace_callback`, `array_map`…) : s'en passer.
- SuiteCRM ne charge que le fichier déclaré dans le hook : `ContactSave.php` charge
  lui-même `LastName.php`.
