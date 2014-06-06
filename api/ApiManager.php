<?php

class ApiManager
{
    private $__dbh;

    public function __construct(\PDO $dbh)
    {
        $this->__dbh = $dbh;
    }

    private function __getDbh()
    {
        return $this->__dbh;
    }

    public function get($action, $params)
    {
        return $this->$action($params);
    }

    private function getNodesAndEdges()
    {
        $nodes = $this->getNodes();
        $edges = $this->getEdges();

        return compact('nodes', 'edges');
    }

    private function getEdges()
    {
        $stmt = $this->__getDbh()->query("SELECT * FROM `edges`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getNodes()
    {
        $stmt = $this->__getDbh()->query("SELECT * FROM `nodes`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getEnemies()
    {
        $stmt = $this->__getDbh()->query("SELECT e.node, et.name, count(e.enemy_type) as mcount FROM `enemies` e INNER JOIN `enemy_types` et ON et.id = e.enemy_type GROUP BY e.node, e.enemy_type ORDER BY e.node");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMachines()
    {
        $stmt = $this->__getDbh()->query("SELECT m.node, mt.name, count(m.machine_type) as mcount FROM `machines` m INNER JOIN `machine_types` mt ON mt.id = m.machine_type GROUP BY m.node, m.machine_type ORDER BY m.node");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function addMachine($params)
    {
        $sql = "INSERT INTO `im`.`machines` (`id`, `machine_type`, `node`) VALUES (NULL, :type, :node)";
        // $stmt = $this->__getDbh()->query($sql);
        $query = $this->__getDbh()->prepare($sql);
        while($params['count']--) {
            $re = $query->execute(array(':type'=> $params['machine'],
                        ':node'=>$params['location']));
            if (!$re) {
                return false;
            }
        }
        return true;
    }

    private function removeMachine($id, $dbh)
    {
        $sql = "DELETE FROM `machines` WHERE `id` = :id";
        $query = $this->__getDbh()->prepare($sql);
        return $query->execute(array(':id'=> $id));
    }

    private function moveMachine($id, $toNode, $dbh)
    {
        $sql = "UPDATE `machines` SET `node`= :node WHERE id = :id";
        $query = $this->__getDbh->prepare($sql);
        return $query->execute(array(':node'=> $toNode,
                        ':id'=>$id));
    }
}
