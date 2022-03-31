<?php

class Repository 
{
    protected function getBdd()
    {
        // for development environment
        $pdo = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'USER', '3B1433BDD106CA59551C99703DD8D4B083FAF6560E1C68C8FC57F8AC56E57165');
        // for debug
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        
        // for production environment
        // return new PDO('mysql:host=ipssisqstorieshe.mysql.db;dbname=ipssisqstorieshe;charset=UTF8', 'ipssisqstorieshe', 'Ipssi2022storieshelper');
    }
}

?>