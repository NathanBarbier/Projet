    // On cache les div de chaque option
    divDelOrg.style.visibility = "collapse";
    divDelOrg.classList.add("collapse");
    divDelOrg.style.opacity = "0%";

    divOption2.style.visibility = "collapse";
    divOption2.classList.add("collapse");
    divOption2.style.opacity = "0%";

    divOption3.style.visibility = "collapse";
    divOption3.classList.add("collapse");
    divOption3.style.opacity = "0%";

    // On ajoute la fonction d'affichage aux boutons d'options
  
    boutonDelOrg.addEventListener("click", function(){afficherOption(divDelOrg, boutonDelOrg)});
    boutonOption2.addEventListener("click", function(){afficherOption(divOption2, boutonOption2)});
    boutonOption3.addEventListener("click", function(){afficherOption(divOption3, boutonOption3)});

    // On ajoute la fonction masquage au bouton "non" de l'option Supprimer Organisation

    boutonRefusDel.addEventListener("click", function(){cacherOption(divDelOrg, boutonDelOrg)});
    boutonCacheOption2.addEventListener("click", function(){cacherOption(divOption2, boutonOption2)});
    boutonCacheOption3.addEventListener("click", function(){cacherOption(divOption3, boutonOption3)});

    // On modifie la durée de transition de l'opacité de chaque bouton d'option
    boutonDelOrg.style.transition = "opacity 1s ease-in-out";
    boutonOption2.style.transition = "opacity 1s ease-in-out";
    boutonOption3.style.transition = "opacity 1s ease-in-out";


    function afficherOption(divOption, boutonOption) 
    {
        boutonOption.removeEventListener("click", afficherOption);

        divOption.style.visibility = "visible";
        divOption.classList.remove("collapse");
        divOption.style.transition = "opacity 1s ease-in-out";
        divOption.style.opacity = "100%";
        boutonOption.style.opacity = "0%";

    }

    function cacherOption(divOption, boutonOption)
    {
        divOption.style.opacity = "0%";
        boutonOption.style.visibility = "visible";
        boutonOption.style.transition = "opacity 1s ease-in-out";
        boutonOption.style.opacity = "100%";
        boutonOption.classList.add("disabled")
        setTimeout(function(){
            
            boutonOption.addEventListener("click", afficherOption );
            boutonOption.classList.remove("disabled");
            divOption.style.visibility = "collapse";
            divOption.classList.add("collapse");

        }, 1000)

    }