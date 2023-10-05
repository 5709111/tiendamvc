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
        $data=[
            'title' => 'Administracion - Inicio',
            'menu' => false,
            'admin'=>true,
            'data'=>[],

        ];
        $this->view('admin/index2');
    }
}