// Modification du nom de l'utilisateur
function afficherConfModifNom($idUser)
{
    var cacher = "nom" + $idUser;
    var cacher2 = "divModifNom" + $idUser;
    var afficher = "divConfModifNom" + $idUser;
    var afficher2 = "divInputModifNom" + $idUser;

    document.getElementById(cacher).classList.remove("show");
    document.getElementById(cacher2).classList.remove("show");
    document.getElementById(afficher).classList.add("show");
    document.getElementById(afficher2).classList.add("show");
}

function annulerModifNom($idUser)
{
    var cacher = "divConfModifNom" + $idUser;  
    var cacher2 = "divInputModifNom" + $idUser;  
    var afficher = "nom" + $idUser;
    var afficher2 = "divModifNom" + $idUser;

    document.getElementById(afficher).classList.add("show");
    document.getElementById(afficher2).classList.add("show");
    document.getElementById(cacher).classList.remove("show");
    document.getElementById(cacher2).classList.remove("show");2
}

//Modification du prenom de l'utilisateur
function afficherConfModifPrenom($idUser)
{
    var cacher = "prenom" + $idUser;
    var cacher2 = "divModifPrenom" + $idUser;
    var afficher = "divConfModifPrenom" + $idUser;
    var afficher2 = "divInputModifPrenom" + $idUser;

    document.getElementById(cacher).classList.remove("show");
    document.getElementById(cacher2).classList.remove("show");
    document.getElementById(afficher).classList.add("show");
    document.getElementById(afficher2).classList.add("show");
}

function annulerModifPrenom($idUser)
{
    var cacher = "divConfModifPrenom" + $idUser;  
    var cacher2 = "divInputModifPrenom" + $idUser ;  
    var afficher = "prenom" + $idUser;
    var afficher2 = "divModifPrenom" + $idUser;

    document.getElementById(afficher).classList.add("show");
    document.getElementById(afficher2).classList.add("show");
    document.getElementById(cacher).classList.remove("show");
    document.getElementById(cacher2).classList.remove("show");2
}

// Suppression de l'utilisateur
function afficherConfDelUser($idUser)
{
    var cacher = "divDelUser" + $idUser;
    var afficher = "divConfDelUser" + $idUser;
    document.getElementById(cacher).classList.remove("show");
    document.getElementById(afficher).classList.add("show");
}

function annulerDelUser($idUser)
{
    var cacher = "divConfDelUser" + $idUser  ;  
    var afficher = "divDelUser" + $idUser;
    document.getElementById(afficher).classList.add("show");
    document.getElementById(cacher).classList.remove("show");
}

// Modification de l'équipe de l'utilisateur
function afficherConfModifEquipe($idUser)
{
    var cacher = "divModifEquipe" + $idUser;
    var cacher2 = "divNomEquipe" + $idUser;
    var afficher = "divConfModifEquipe" + $idUser;
    var afficher2 = "divSelectEquipes" + $idUser;

    document.getElementById(cacher).classList.remove("show");
    document.getElementById(cacher2).classList.remove("show");
    document.getElementById(afficher).classList.add("show");
    document.getElementById(afficher2).classList.add("show");
}

function annulerModifEquipe($idUser)
{
    var cacher = "divConfModifEquipe" + $idUser;
    var cacher2 = "divSelectEquipes" + $idUser;
    var afficher = "divModifEquipe" + $idUser;
    var afficher2 = "divNomEquipe" + $idUser;
    
    document.getElementById(afficher).classList.add("show");
    document.getElementById(afficher2).classList.add("show");
    document.getElementById(cacher).classList.remove("show");
    document.getElementById(cacher2).classList.remove("show");
}