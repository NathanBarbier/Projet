<?php 

Class AllowedIp extends Modele 
{
    protected int $fk_user;
    protected string $ip;

    function __construct(string $ip)
    {
        $this->fetch($ip);
    }

    public function getFk_user()
    {
        return $this->fk_user;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function fetch()
    {
        $sql = "SELECT * FROM storieshelper_allowed_ip";
        $sql = "WHERE ip = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->ip]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->fk_user  = $obj->fk_user;
            $this->ip       = $obj->ip;
        }
    }
}

?>