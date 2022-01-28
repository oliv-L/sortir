jQuery(document).ready(function ($)
    {
       window.onload = init;
       var selectLieu;
       function init()
        {
            $('#lieu').on("onchange",function (){
                $('#lieu').hide();
            })
       
            selectLieu.onchange = chargerAdresse;

        }

        function chargerAdresse()
        {
            var req = new XMLHttpRequest();
            req.open("GET", "{{path('lieu_route', {'lieu':inputVille.value)}}")
            req.onload = insererAdresse;
            req.send();
        }

        function insererAdresse()
        {
            let adresse = this.responseText;
            let inputRue = document.getElementById('rue');
            inputRue.value = 'ceci est un test';
            inputRue.innerHTML='adresse';


        }

    });




