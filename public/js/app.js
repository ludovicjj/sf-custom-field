const selectFields = Array.from(document.querySelectorAll('select[multiple]'));

async function onLoad(url) {
    const response = await fetch(url, {headers:{Accept: 'application/json'}})
    if (response.status === 204) {
        return null;
    }
    return await response.json();
}

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

selectFields.map(function(select) {
    select.classList.remove('form-select');
    new TomSelect(select, {
        create: async function(input,callback){
            const data = { name: input }
            callback(await onAdd(data))
        },
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