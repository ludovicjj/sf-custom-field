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