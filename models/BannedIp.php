<?php
Class BannedIp extends Modele
{
    protected int $rowid;
    protected string $ip;

    function __construct(string $ip)
    {
        $this->fetch($ip);
    }

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function fetch()
    {
        $sql = "SELECT * FROM storieshelper_banned_ip";
        $sql = "WHERE ip = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->ip]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_ASSOC);

            $this->rowid    = $obj->rowid;
            $this->ip       = $obj->ip;
        }
    }


}
?>