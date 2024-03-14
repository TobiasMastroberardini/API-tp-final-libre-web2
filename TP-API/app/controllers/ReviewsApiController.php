<?php
require_once "./app/models/ReviewsModel.php";
require_once "./app/models/SegurosModel.php";
require_once "./app/models/UserModel.php";
require_once "./app/controllers/ApiController.php";
require_once "./app/helpers/AuthHelper.php";

class ReviewsApiController extends ApiController
{
    private $model;
    private $segurosModel;
    private $userModel;
    private $authHelper;

    public function __construct()
    {
        parent::__construct();
        $this->model = new ReviewsModel();
        $this->segurosModel = new SegurosModel();
        $this->userModel = new UserModel();
        $this->authHelper = new AuthHelper();
    }

    public function get($params = [])
    {
        if (empty($params[":ID"])) {
            $reviews = $this->model->getReviews();

            $reviews = $this->filter($reviews);

            if (!empty($_GET["sort"])) {
                $reviews = $this->sort($reviews, $_GET["sort"]);
            }

            $this->view->response($reviews, 200);
            return;
        }

        $review = $this->model->getReviewById($params[":ID"]);

        if (empty($review)) {
            $this->view->response("La tarea con el ID " . $params[":ID"] . " no existe.", 404);
            return;
        }

        if (empty($params[":subrecurso"])) {
            $this->view->response($review, 200);
            return;
        }

        switch ($params[":subrecurso"]) {
            case 'descripcion':
                $this->view->response($review->descripcion, 200);
                break;
            case 'puntuacion':
                $this->view->response($review->puntuacion, 200);
                break;
            case 'usuario':
                $this->view->response($review->usuario, 200);
                break;
            case 'seguro':
                $this->view->response($review->seguroId, 200);
                break;
            default:
                $this->view->response("El subrecurso no existe.", 400);
                break;
        }
    }

    public function create()
    {
        if ($this->authHelper->verifyRequest() == false) {
            $this->view->response("No autorizado.", 401);
            return;
        }

        $body = $this->getData();

        if (empty($body->descripcion) || empty($body->puntuacion) || empty($body->usuario) || empty($body->seguroId)) {
            $this->view->response("Algunos datos están vacíos.", 400);
            return;
        }

        $descripcion = $body->descripcion;
        $puntuacion = $body->puntuacion;
        $usuario = $body->usuario;
        $seguroId = $body->seguroId;

        if (empty($this->segurosModel->getSeguroById($seguroId))) {
            $this->view->response("No existe ningún seguro con la id " . $seguroId . ".", 400);
            return;
        }

        if (empty($this->userModel->getUserByUsername($usuario))) {
            $this->view->response("No existe ningún usuario con el nombre " . $usuario . ".", 400);
            return;
        }

        if ($puntuacion > 5 || $puntuacion < 1) {
            $this->view->response("La puntuación debe ser un valor entero entre 1 y 5.", 400);
            return;
        }

        $this->model->addReview($descripcion, $puntuacion, $usuario, $seguroId);
        $this->view->response("Recurso creado con éxito.", 201);
    }

    public function update($params)
    {
        if ($this->authHelper->verifyRequest() == false) {
            $this->view->response("No autorizado.", 401);
            return;
        }

        $reviewId = $params[":ID"];

        if (empty($this->model->getReviewById($reviewId))) {
            $this->view->response("No existe ninguna review con el id " . $reviewId . ".", 400);
            return;
        }

        $body = $this->getData();

        if (empty($body->descripcion) || empty($body->puntuacion) || empty($body->usuario) || empty($body->seguroId)) {
            $this->view->response("Algunos datos están vacíos.", 400);
            return;
        }

        $descripcion = $body->descripcion;
        $puntuacion = $body->puntuacion;
        $usuario = $body->usuario;
        $seguroId = $body->seguroId;

        if (empty($this->segurosModel->getSeguroById($seguroId))) {
            $this->view->response("No existe ningún seguro con la id " . $seguroId . ".", 400);
            return;
        }

        if (empty($this->userModel->getUserByUsername($usuario))) {
            $this->view->response("No existe ningún usuario con el nombre " . $usuario . ".", 400);
            return;
        }

        if ($puntuacion > 5 || $puntuacion < 1) {
            $this->view->response("La puntuación debe ser un valor entero entre 1 y 5.", 400);
            return;
        }

        $this->model->updateReview($descripcion, $puntuacion, $usuario, $seguroId, $reviewId);
        $this->view->response("Recurso actualizado con éxito.", 200);
    }

    public function delete($params)
    {
        if ($this->authHelper->verifyRequest() == false) {
            $this->view->response("No autorizado.", 401);
            return;
        }

        $reviewId = $params[":ID"];

        if (empty($this->model->getReviewById($reviewId))) {
            $this->view->response("No existe ninguna review con el id " . $reviewId . ".", 400);
            return;
        }

        $this->model->deleteReview($reviewId);
        $this->view->response("Recurso eliminado con éxito.", 200);
    }






    private function sort($reviews, $field)
    {
        switch ($field) {
            case 'descripcion':
                usort($reviews, function ($a, $b) {
                    return strcmp($a->descripcion, $b->descripcion);
                });
                break;
            case 'puntuacion':
                usort($reviews, function ($a, $b) {
                    return $a->puntuacion - $b->puntuacion;
                });
                break;
            case 'usuario':
                usort($reviews, function ($a, $b) {
                    return strcmp($a->usuario, $b->usuario);
                });
                break;
            case 'seguro':
                usort($reviews, function ($a, $b) {
                    return $a->seguroId - $b->seguroId;
                });
                break;
            default:
        }

        if (!empty($_GET["order"]) && $_GET["order"] == "desc") {
            $reviews = array_reverse($reviews);
        }

        return $reviews;
    }

    private function filter($reviews)
    {
        if (!empty($_GET["descripcion"])) {
            for ($i = 0; $i < count($reviews); $i++) {
                if ($reviews[$i]->descripcion != $_GET["descripcion"]) {
                    array_splice($reviews, $i, 1);
                    $i--;
                }
            }
        }

        if (!empty($_GET["puntuacion"])) {
            for ($i = 0; $i < count($reviews); $i++) {
                var_dump($i);
                if ($reviews[$i]->puntuacion != $_GET["puntuacion"]) {
                    array_splice($reviews, $i, 1);
                    $i--;
                }
            }
        }

        if (!empty($_GET["usuario"])) {
            for ($i = 0; $i < count($reviews); $i++) {
                if ($reviews[$i]->usuario != $_GET["usuario"]) {
                    array_splice($reviews, $i, 1);
                    $i--;
                }
            }
        }

        if (!empty($_GET["seguro"])) {
            for ($i = 0; $i < count($reviews); $i++) {
                if ($reviews[$i]->seguroId != $_GET["seguro"]) {
                    array_splice($reviews, $i, 1);
                    $i--;
                }
            }
        }

        return $reviews;
    }
}
