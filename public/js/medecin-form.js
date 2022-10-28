const ids = [
    'medecin_region',
   'medecin_departement'
];

document.addEventListener('change', function(e) {

   if (ids.includes(e.target.id)) {
      let field = e.target;
      let form = field.closest('form');

      let target = '#' + field.id.replace('departement', 'ville').replace('region', 'departement');
      let url = form.getAttribute('action')

      let data = {};
      data[field.getAttribute('name')] = field.value

      fetch(url, {
         method: 'POST',
         body: new FormData(form)
      }).then(response => {
         return response.text()
      }).then(htmlText => {
         let parser = new DOMParser();
         let html = parser.parseFromString(htmlText, 'text/html');

         let newInput = html.querySelector(target);

         // field ville is copied with class "is-invalid"
         if (newInput.classList.contains('is-invalid')) {
            newInput.classList.remove('is-invalid');
         }

         form.querySelector(target).replaceWith(newInput)

         if (!form.querySelector('#medecin_departement').value) {
            let villeInput = form.querySelector('#medecin_ville').cloneNode(false);
            let option = document.createElement("option");
            option.setAttribute("value", "")
            option.innerText = "Choose a town";
            villeInput.appendChild(option);
            form.querySelector('#medecin_ville').replaceWith(villeInput)
         }
      })
   }
})

