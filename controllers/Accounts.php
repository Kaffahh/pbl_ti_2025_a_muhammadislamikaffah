<?php

require_once __DIR__ . '/../models/AccountsModel.php';

class Accounts
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new AccountsModel();
        $this->load  = new Loader();
    }

    private function validate($data)
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Name is required.';
        }

        return $errors;
    }

    public function index()
    {
        $accounts = $this->model->getAll();
        $this->load->view('views/accounts/index.php', [
            'accounts' => $accounts,
        ]);
    }

    public function create()
    {
        $isEdit = false;
        $this->load->view('views/accounts/form.php', [
            'isEdit' => $isEdit,
        ]);
    }

    public function store()
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            $this->load->view('views/accounts/form.php', [
                'isEdit' => $isEdit,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->create([
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/accounts');
        exit;
    }

    public function edit($id)
    {
        $accounts = $this->model->getById($id);

        if ($accounts === null) {
            http_response_code(404);
            echo '404 - Accounts not found';
            return;
        }

        $isEdit = true;
        $this->load->view('views/accounts/form.php', [
            'isEdit'    => $isEdit,
            'id'        => $id,
            'accounts' => $accounts,
        ]);
    }

    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Accounts not found';
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = true;
            $accounts = $this->model->getById($id);
            $this->load->view('views/accounts/form.php', [
                'isEdit'    => $isEdit,
                'id'        => $id,
                'accounts' => $accounts,
                'errors'    => $errors,
            ]);
            return;
        }

        $this->model->update($id, [
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/accounts');
        exit;
    }

    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Accounts not found';
            return;
        }

        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/accounts');
        exit;
    }
}
