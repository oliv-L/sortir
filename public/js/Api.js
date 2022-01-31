window.onload = () => {
    initVilles();
    document.getElementById('villes').addEventListener("change", function(){
        console.log("valeur changée")
        getLieu();
    })
}
  //todo voir avec le coach pourquoi ma variable app_url_api n'est pas reconnue
  function initVilles() {
      fetch("http://localhost/sortir/public/api/listeVilles", {method: "GET"})
          .then(response => response.json())
          .then(response => {
              let options = "";
              response.map(ville => {
                  options += `<option value="${ville.id}">${ville.nom}</option>`;
              })

              document.querySelector('#villes').innerHTML = options;
          })
  }

      function getLieu() {
          console.log("entrée dans la fonction getlieu")
          fetch("http://localhost/sortir/public/api/lieu?id="+document.getElementById('villes').value, {method: "GET"})
              .then(response =>response.json())
              .then(response => {

                  let options = "";

                  response.map(lieu => {
                      options += `<option value="${lieu.id}">${lieu.nom}</option>`;

                  })

                  document.querySelector('#sortie_lieu').innerHTML = options;
              })
      }

