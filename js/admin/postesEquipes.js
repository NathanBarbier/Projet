function afficherInfoEquipe(idEquipe, MinId, MaxId)
{
    for(i = MinId; i <= MaxId; i++)
    {
        var divInfoEquipe = "divInfoEquipe" + i;
        if(i == idEquipe)
        {
            document.getElementById(divInfoEquipe).style.display = "block";
        } else {
            document.getElementById(divInfoEquipe).style.display = "none";
        }
    }
}