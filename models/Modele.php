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
        $pdo = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'root');
        // for debug
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        
        // for production environment
        // return new PDO('mysql:host=ipssisqstorieshe.mysql.db;dbname=ipssisqstorieshe;charset=UTF8', 'ipssisqstorieshe', 'Ipssi2022storieshelper');
    }
}
?>