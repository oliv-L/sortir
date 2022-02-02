window.onload = () => {
    initVilles();

    document.getElementById('villes').addEventListener("change", function(){

        getLieu();
    })
}
  //todo voir avec le coach pourquoi ma variable app_url_api n'est pas reconnue
  function initVilles() {
      fetch("http://localhost/sortir/public/api/listeVilles", {method: "GET"})
          .then(response => response.json())
          .then(response => {
              let options =  `<option value="">Choisir une ville</option>`;
              response.map(ville => {
                  options += `<option  value="${ville.id}">${ville.nom}</option>`;
              })

              document.querySelector('#villes').innerHTML = options;
          })
  }

  function getLieu()
  {
      console.log("entrée dans la fonction getlieu")
      fetch("http://localhost/sortir/public/api/lieu?id="+document.getElementById('villes').value, {method: "GET"})
      .then(response =>response.json())
      .then(response => {
      let options = "";
      response.map(lieu => {
      options += `<option value="${lieu.id}">${lieu.nom}</option>`;
                     })

      document.querySelector('#sortie_lieu').innerHTML = options;
          document.querySelector('#sortie_lieu').addEventListener("change", function (){
              getCoordonnees();
                  })
  })}

  function getCoordonnees()
  {
      console.log("entrée dans la fonction getCoordonnees")
      fetch("http://localhost/sortir/public/api/adresse?id="+document.querySelector('#sortie_lieu').value, {method: "GET"})
          .then(response =>response.json()
            )
          .then(response => {

              response.map(lieu => {
                  options = `<div> Rue : ${lieu.rue}</div>
                             <div> Code Postal : ${lieu.ville.codePostal}</div>
                             <div>latitude : ${lieu.latitude}</div>
                             <div> longitude : ${lieu.longitude}</div>`;
              })


              document.getElementById('coordonnees').innerHTML = options;
          })
  }

