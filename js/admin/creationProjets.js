    var imgCheckClient = document.getElementById('imgCheckClient');
    var checkClient = document.getElementById('checkClient');

    var equipesAjoutees = [];

    function ajouterEquipe(idEquipe)
    {
        equipesAjoutees.push(idEquipe);
        document.getElementById('inputEquipesAjoutees').value = equipesAjoutees;
        var identifiantEquipe = "equipe" + idEquipe;
        var identifiantEquipeProjet = "equipeProjet" + idEquipe;
        document.getElementById(identifiantEquipe).classList.remove("show");
        document.getElementById(identifiantEquipeProjet).classList.add("show");
    }

    function retirerEquipe(idEquipe)
    {
        equipesAjoutees.splice(equipesAjoutees.indexOf(idEquipe), 1);
        document.getElementById('inputEquipesAjoutees').value = equipesAjoutees;
        var identifiantEquipe = "equipe" + idEquipe;
        var identifiantEquipeProjet = "equipeProjet" + idEquipe;
        document.getElementById(identifiantEquipe).classList.add("show");
        document.getElementById(identifiantEquipeProjet).classList.remove("show");
    }

    function verifClient(clients, checkClient, imgCheckClient)
    {
        var inputClient = document.getElementById('client');
        for(i = 0; i < clients.length; i++)
        {
            if(clients[i]['nom'] == inputClient.value)
            {
                inputClient.style.borderColor = "green";
            } else {
                inputClient.style.borderColor = "red";
                document.getElementById('confirmClient').innerHTML = "<div id='divConfClient' class='alert alert-danger text-center mt-2 collapse show' style='padding:0;padding-top:3vh; padding-bottom:3vh'><p>Le client n'est pas présent dans la bdd. <br> Souhaitez vous l'ajouter?</p></div'><div class='mx-auto row'><div class='col-3'></div><div class='col-3'><a onclick='acceptClient(checkClient, imgCheckClient)' class='btn btn-success'>oui</a></div><div class='col-3'><a onclick='refusCLient()' class='btn btn-warning'>non</a></div></div>";
                imgCheckClient.src = '../images/cancel.png';
                checkClient.classList.remove('btn-outline-success');
                checkClient.classList.add('btn-outline-danger', 'disabled');
            }
        }
    }

    function refusCLient()
    {
        document.getElementById('divConfClient').classList.remove('show');
    }
    
    function acceptClient(checkClient,imgCheckClient)
    {
        document.getElementById('divConfClient').classList.remove('show');
        imgCheckClient.src = '../images/check.png';
        document.getElementById('client').style.borderColor = 'green';
        checkClient.classList.remove('btn-outline-danger');
        checkClient.classList.add('btn-outline-success');
        document.getElementById('client').addEventListener('change', function(){
            document.getElementById('checkClient').classList.remove('disabled');
            document.getElementById('checkClient').removeEventListener('change', function(){
                document.getElementById('checkClient').classList.remove('disabled');
            });
        document.getElementById('client').style.borderColor = '#ced4da';
        })
    }
