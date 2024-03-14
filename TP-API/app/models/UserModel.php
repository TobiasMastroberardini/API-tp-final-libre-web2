<?php

require_once "./app/models/Model.php";

class UserModel extends Model {

    public function getUserByUsername($usuario) {
        $query = $this->db->prepare('SELECT * FROM usuarios WHERE usuario = ?');
        $query->execute([$usuario]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

}