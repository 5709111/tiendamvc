<?php

class AdminController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Admin');
    }

    public function index()
    {
        $data = [
            'title' => 'Administración',
            'menu' => false,
            'data' => [],
        ];

        $this->view('admin/index', $data);
    }

    public function verifyUser()
    {
        $errors = [];
        $dataForm = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $user = $_POST['user'] ?? '';
            $password = $_POST['password'] ?? '';

            $dataForm = [
                'user' => $user,
                'password' => $password,
            ];

            if (empty($user)) {
                array_push($errors, 'El usuario es requerido');
            }
            if (empty($password)) {
                array_push($errors, 'La contraseña es requerida');
            }

            if (count($errors) == 0) {
                $errors = $this->model->verifyUser($dataForm);

                if (empty($errors)) {

                    $data = $this->model->getUserByEmail($user);

                    $session = new Session();
                    $session->login($data);

                    header('location:' . ROOT . 'AdminShop');

                }
            }
        }

        $data = [
            'title' => 'Administración - Inicio',
            'menu' => false,
            'admin' => true,
            'errors' => $errors,
            'data' => $dataForm,
        ];

        $this->view('admin/index', $data);
    }
    public function logout()
    {
        $session = new Session();
        $session->logout();
        header('location:' . ROOT . "Admin");
    }
}