<?php

class Repository 
{
    protected function getBdd()
    {
        // for development environment
        $pdo = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'root');
        // for debug
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        
        // for production environment
        // return new PDO('mysql:host=ipssisqstorieshe.mysql.db;dbname=ipssisqstorieshe;charset=UTF8', 'ipssisqstorieshe', 'Ipssi2022storieshelper');
    }
}

?>