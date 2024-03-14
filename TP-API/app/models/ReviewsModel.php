<?php
require_once "./app/models/Model.php";

class ReviewsModel extends Model
{
    public function getReviews()
    {
        $query = $this->db->prepare("SELECT * FROM reviews");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getReviewById($id)
    {
        $query = $this->db->prepare("SELECT * FROM reviews WHERE reviewId = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function addReview($descripcion, $puntuacion, $usuario, $seguroId)
    {
        $query = $this->db->prepare("INSERT INTO reviews (descripcion, puntuacion, usuario, seguroId) VALUE (?, ?, ?, ?)");
        $query->execute([$descripcion, $puntuacion, $usuario, $seguroId]);
    }

    public function updateReview($descripcion, $puntuacion, $usuario, $seguroId, $id)
    {
        $query = $this->db->prepare("UPDATE reviews SET descripcion = ?, puntuacion = ?, usuario = ?, seguroId = ? WHERE reviewId = ?");
        $query->execute([$descripcion, $puntuacion, $usuario, $seguroId, $id]);
    }

    public function deleteReview($id)
    {
        $query = $this->db->prepare("DELETE FROM reviews WHERE reviewId = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
}
