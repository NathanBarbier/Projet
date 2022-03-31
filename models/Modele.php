<?php
class Modele 
{
    public function object_to_array($data)
    {
        if(is_array($data) || is_object($data))
        {
            $result = array();
     
            foreach($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
    
            return $result;
        }
     
        return $data;
    }


    protected function getBdd()
    {
        // for development environment
        $pdo = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'USER','3B1433BDD106CA59551C99703DD8D4B083FAF6560E1C68C8FC57F8AC56E57165');
        // for debug
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        
        // for production environment
        // return new PDO('mysql:host=ipssisqstorieshe.mysql.db;dbname=ipssisqstorieshe;charset=UTF8', 'ipssisqstorieshe', 'Ipssi2022storieshelper');
    }

    protected function getBddSafe()
    {
        // for development environment
        $pdo = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'USER_SAFE','26CD847229CD2FCE6ADC7352FDB5383734BECA90EAFE3BAB511AC36171F4C57F');
        // for debug
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
?>