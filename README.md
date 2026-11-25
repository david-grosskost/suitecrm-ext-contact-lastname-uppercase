# Nom de famille des contacts en majuscules (`contact-lastname-uppercase`)

Le nom de famille des contacts s'enregistre toujours en majuscules, même tapé en
minuscules : « Marie dupont » devient « Marie DUPONT ». Cela vaut pour tous les
utilisateurs et toutes les façons de créer ou modifier un contact (fiche, importation,
intégration). Le prénom n'est pas modifié et les accents sont conservés (« LEFÈVRE »). À
l'installation, les contacts déjà présents sont convertis une fois.

| Fiche | |
|---|---|
| Version de SuiteCRM | 8.10 (`>=8.10 <9`) |
| Types | `logic-hook` |
| Dépend de | — |
| Installée d'office | non |

## Installer

Sur une instance, par **Administration → Chargeur de module** : l'extension y figure dans
la liste des paquets disponibles, il suffit de cliquer sur **Installer**. Depuis le
terminal, dans suitecrm-core :

```bash
make extensions@install SLUGS="contact-lastname-uppercase"
```

La désinstallation se fait au même endroit. Les noms déjà mis en majuscules le restent.

## Documentation

| Page | Contenu |
|---|---|
| [Utilisation](docs/utilisation.md) | ce que voient les utilisateurs, ce qui se passe à l'installation, limites |
| [Fonctionnement](docs/fonctionnement.md) | fichiers, hook, données en base, installation et désinstallation |
| [Développement](docs/developpement.md) | vérifier, tests, essayer en local, publier une version |

Les commandes, la CI et le format des extensions sont décrits dans la documentation de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation).
