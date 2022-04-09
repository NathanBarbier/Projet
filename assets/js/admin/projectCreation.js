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
