<?php
Class BannedIp extends Modele
{
    protected ?int $rowid = null;
    protected ?string $ip = null;

    function __construct(string $ip = null)
    {
        if($ip != null)
        {
            $this->fetch($ip);
        }
    }

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function fetch(string $ip)
    {
        $sql = "SELECT * FROM storieshelper_banned_ip";
        $sql .= " WHERE ip = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$ip]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid    = $obj->rowid;
            $this->ip       = $obj->ip;
        }
    }


}
?>