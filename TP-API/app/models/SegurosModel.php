<?php

require_once "./app/models/Model.php";

class SegurosModel extends Model
{

    public function getSeguroById($id)
    {
        $query = $this->db->prepare('SELECT * FROM seguros WHERE seguroId = ?');
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
}
