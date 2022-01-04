<?php
class Modele 
{
    protected function getBdd()
    {
        // for development environment
        return new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'root');
        
        // for production environment
        // return new PDO('mysql:host=ipssisqstorieshe;dbname=ipssisqstorieshe;charset=UTF8', 'root');
    }
}
?>