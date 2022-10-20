const selectFields = Array.from(document.querySelectorAll('select[multiple]'));

async function onLoad(url) {
    const response = await fetch(url, {headers:{Accept: 'application/json'}})
    if (response.status === 204) {
        return null;
    }
    return await response.json();
}

selectFields.map(function(select) {
    select.classList.remove('form-select');
    //select.classList.add('form-select-lg');

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