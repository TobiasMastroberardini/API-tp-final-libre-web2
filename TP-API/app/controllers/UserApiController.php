<?php

require_once "./app/models/UserModel.php";
require_once "./app/helpers/AuthHelper.php";

class UserApiController extends ApiController
{
    private $model;
    private $authHelper;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
        $this->authHelper = new AuthHelper();
    }

    public function getToken()
    {
        $basic = $this->authHelper->getAuthHeaders();

        if (empty($basic)) {
            $this->view->response("No se enviaron los encabezados de autenticación.", 401);
            return;
        }

        $basic = explode(" ", $basic);

        if ($basic[0] != "Basic") {
            $this->view->response("Los encabezados de autenticación son incorrectos.", 401);
            return;
        }

        $userpass = base64_decode($basic[1]);
        $userpass = explode(":", $userpass);

        $user = $userpass[0];
        $pass = $userpass[1];

        $usuario = $this->model->getUserByUsername($user);

        if (empty($usuario)) {
            $this->view->response("El usuario no existe.", 401);
            return;
        }

        if (!password_verify($pass, $usuario->contrasenia)) {
            $this->view->response("La contraseña no es válida.", 401);
            return;
        }

        $token = $this->authHelper->createToken($usuario);
        $this->view->response($token, 200);
    }
}
