$(document).ready(function() {
    $('.js-select-tags').select2({
        tags: true,
        tokenSeparators: [',', ' ', ';']
    }).on('change', function(event) {
        let elements = $(this).find("[data-select2-tag=true]");

        if (elements.length && $.inArray(elements.val(), $(this).val()) !== -1) {
            let data = {name: elements.val()};

            fetch("/api/tags/create", {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(data)
            }).then(response => {
                return response.json()
            }).then(data => {
                elements.replaceWith(`<option selected value="${data.id}">${data.name}</option>`)
            })
        }
    })
})