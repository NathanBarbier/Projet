<?php 

Class AllowedIp extends Modele 
{
    protected ?int $fk_user = null;
    protected ?string $ip = null;

    function __construct(string $ip = null)
    {
        if($ip != null) 
        {
            $this->fetch($ip);
        }
    }

    public function getFk_user()
    {
        return $this->fk_user;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function fetch(string $ip)
    {
        $sql = "SELECT * FROM storieshelper_allowed_ips";
        $sql .= " WHERE ip = ?";


        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$ip]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->fk_user  = $obj->fk_user;
            $this->ip       = $obj->ip;
        }
    }
}

?>