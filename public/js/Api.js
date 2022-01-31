window.onload = () =>{

  //todo voir avec le coach pourquoi ma variable app_url_api n'est pas reconnue
    fetch("http://localhost/sortir/public/api/listeVilles", {method:"GET"})
        .then(response=>response.json())
        .then(response=> {
            let options = "";
            response.map(ville =>{
                options += `<option value="${ville.id}">${ville.nom}</option>`;
            })

            document.querySelector('#villes').innerHTML=options;
        } )

    function getLieu(){
        fetch( "http://localhost/sortir/public/api/lieu", {method:"GET"})
            .then(response=>response.json())
            .then(response=>{
                let options = "";
                response.map(lieu=>{
                    options += `<option value="{$lieu.id]"> ${lieu.nom}</option>`;
            })
                document.querySelector('#villes').innerHTML=options;
            })
    }

}