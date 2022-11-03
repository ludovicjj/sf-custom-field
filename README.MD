# Champs personnalisés et évènement de formulaire avec Symfony

## projet
- php : 8.1
- symfony : 6.1
- bdd : PostgreSQL (docker)

## Champs personnalisés

Création d'un champ personnalisé utilisant le rendu du choice type.[ Voir étude de cas](./docs/CUSTOM_FIELD.md).

## Champs Imbriqués

Utilisation des événements de formulaire ```FormEvents::POST_SET_DATA``` et ```FormEvents::POST_SUBMIT```
pour ajouter des champs ou préremplir certains champs avec des valeurs données.[ Voir étude de cas](./docs/CHAMPS_IMBRIQUE.MD).

## Select boxes 

Utilisation de [Tom Select](https://tom-select.js.org/) et [Select2](https://select2.org/) pour créer des
```select``` dynamique avec une prise en charge de la recherche.[ Voir étude de cas](./docs/CUSTOM_FIELD.md).
