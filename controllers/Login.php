<?php

require_once __DIR__ . '/../models/LoginModel.php';

class Login
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new LoginModel();
        $this->load = new Loader();
    }

    public function index()
    {
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '/accounts');
            exit;
        }

        $this->load->view('views/login/index.php');
    }

    public function authenticate()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }
        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        if (empty($errors)) {
            $result = $this->model->authenticate($email, $password);
            if ($result['account'] !== null) {
                Auth::login($result['account']);
                header('Location: ' . BASE_URL . '/accounts');
                exit;
            }
            $errors['login'] = $result['error'];
        }

        $this->load->view('views/login/index.php', [
            'email' => $email,
            'errors' => $errors,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
