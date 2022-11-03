# Select box

## Tom Select

### Exemples pour récupérer des données distantes.

Utilisation de l'API Fetch native de JavaScript dans cet exemple pour récupérer des données 
à partir d'une URl dans le projet.

```javascript
// Selectionne tous les select multiple
const selectFields = Array.from(document.querySelectorAll('select[multiple]'));

selectFields.map(function(select) {
    new TomSelect(select, {
        hideSelected: true,
        closeAfterSelect: true,
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        load: async (query, callback) => {
            const url = `${select.dataset.remote}?name=${encodeURIComponent(query)}`;
            callback(await onLoad(url))
        },
        plugins: {
            remove_button: {title: 'Supprimer cet élément'}
        }
    })
})

async function onLoad(url) {
    const response = await fetch(url, {headers:{Accept: 'application/json'}})
    if (response.status === 204) {
        return null;
    }
    return await response.json();
}
```

Mise en place du controller. Récupération du paramètre ```name``` envoyer par l'appel par le ``fetch``.
Puis récupération des 15 premiers elements à partir du repository. Enfin serialization des données au format JSON.

```php
    #[Route('/api/tags', name: 'api_tag_search', method: ['GET'])]
    public function search(
        Request $request,
        TagRepository $tagRepository,
        SerializerInterface $serializer,
    ): Response
    {
        $tags = $tagRepository->search($request->query->get('name', ''));
        $json = $serializer->serialize(
            $tags,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['posts']]
        );

        return new JsonResponse($json, 200, [], true);
    }
```
Methode ```search``` dans le TagRepository, qui récupère les 15 résultats qui contiennent la valeur du paramètre ``name``.
```php
    public function search(string $name): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.name LIKE :name')
        ->setParameter('name', "%$name%")
        ->setMaxResults(15)
        ->getQuery()
        ->getResult();
    }
```
### Exemples pour ajouter des données à la volée.

Même principe que ci-dessus, envoie de l'élément à rajouter grâce à ``Fetch``.
```javascript
    selectFields.map(function(select) {
        new TomSelect(select, {
            create: async function(input,callback){
                const data = { name: input }
                callback(await onAdd(data))
            },
            hideSelected: true,
            ...
        })
    })

    async function onAdd(data) {
        const response = await fetch('/api/tags/create', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        });
        return await response.json();
    }
```

Traitement des données coté back.

```php
    #[Route('/api/tags/create', name: 'api_tag_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TagRepository $tagRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;

        if ($name) {
            $name = trim(strip_tags($name));
        }

        $tag = $tagRepository->findOneBy(['name' => $name]);

        // If tag not exist into DB, create new tag, persist/flush it
        if (!$tag) {
            $tag = new Tag();
            $tag->setName($data['name']);
            $entityManager->persist($tag);
            $entityManager->flush();
        }

        // Then return json with the new tag or the already exist tag
        return $this->json(['id' => $tag->getId(), 'name' => $tag->getName()]);
    }
```

## Select2

### Exemples pour ajouter des données à la volée. 

Select2 nécessite jQuery. Le traitement des données coté back est identique à celui de Tom Select.

```javascript
    $(document).ready(function() {
    const select = document.querySelector('.js-select-tags');
    const url = select.getAttribute('data-remote');

    $('.js-select-tags').select2({
        tags: true,
        tokenSeparators: [',', ' ', ';']
    }).on('change', function(event) {
        let elements = Array.from(select.querySelectorAll("[data-select2-tag=true]"));
        let elementsValue = elements.map(e => e.value)

        let selectValue = [...select.selectedOptions].map(option => option.value);

        console.log(elementsValue.some(value => selectValue.includes(value)))

        if (elements.length) {
            if (elementsValue.some(value => selectValue.includes(value))) {
                let data = {name: elementsValue[0]};
                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(data)
                }).then(response => {
                    return response.json()
                }).then(data => {
                    const option = document.createElement('option')
                    option.setAttribute('selected', 'selected');
                    option.setAttribute('value', data.id);
                    option.textContent = data.name
                    elements.map(element => element.replaceWith(option))
                })
            }
        }
    })
})
```