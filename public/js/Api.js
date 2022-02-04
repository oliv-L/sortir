window.onload = () => {
    initVilles();

    document.getElementById('sortie_ville').addEventListener("change", function(){

        getLieu();
    })
}

  //todo voir a faire avec une variable déclarer sur une twig {{ app_url_api }}
  function initVilles() {
      fetch(url+"/listeVilles", {method: "GET"})
          .then(response => response.json())
          .then(response => {
              let options =  `<option value="">Choisir une ville</option>`;
              response.map(ville => {
                  options += `<option  value="${ville.id}">${ville.nom}</option>`;
              })

              document.querySelector('#villes').innerHTML = options;
          })
          .catch(e => {
             // alert("impossible de charger les villes")
         })
  }

  function getLieu()
  {
      console.log("entrée dans la fonction getlieu")
      fetch(url+"/lieu?id="+document.getElementById('sortie_ville').value, {method: "GET"})
      .then(response =>response.json())
      .then(response => {
      let options = "";
      //todo comment inserer un lien path() dans la twig avec javascript?
     let href="/sortir/public/lieu/create/"+ document.getElementById('sortie_ville').value;

      response.map(lieu => {
      options += `<option value="${lieu.id}">${lieu.nom}</option>`;
                     })

      document.querySelector('#sortie_lieu').innerHTML = options;
      document.getElementById('lieu_create').setAttribute('href', href);


          document.querySelector('#sortie_lieu').addEventListener("change", function (){
              getCoordonnees();
                  })
                  })
          .catch(e => {
              alert("impossible de charger les lieux de rencontres")
          })
  }

  function getCoordonnees()
  {
      console.log("entrée dans la fonction getCoordonnees")
      fetch(url+"/adresse?id="+document.querySelector('#sortie_lieu').value, {method: "GET"})
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
          .catch(e => {
              alert("impossible de recuperer les informations sur le lieu de rencontre")
          })
  }

