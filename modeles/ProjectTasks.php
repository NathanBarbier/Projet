<?php
class ProjectTasks extends Modele
{
    private $projectId;
    private $taskIds = array();

    function __construct($projectId = null)
    {
        if($projectId != null)
        {
            $sql = "SELECT fk_project, fk_task";
            $sql .= " FROM project_tasks";
            $sql .= " WHERE fk_project = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$projectId]);

            $lines = $requete->fetchAll(PDO::FETCH_OBJ);
            
            foreach($lines as $line)
            {
                $this->taskIds[] = $line->fk_task;
            }
            $this->projectId = $projectId;

        }
    }

    // GETTER

    public function getTaskIds()
    {
        return $this->taskIds;
    }

}