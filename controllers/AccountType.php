<?php

require_once __DIR__ . '/../models/AccountTypeModel.php';

class AccountType
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new AccountTypeModel();
        $this->load  = new Loader();
    }

    private function validate($data)
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Name is required.';
        }

        if (empty(trim($data['description'] ?? ''))) {
            $errors['description'] = 'Description is required.';
        }

        return $errors;
    }

    public function index()
    {
        $account_type = $this->model->getAll();
        $this->load->view('views/accounttype/index.php', [
            'account_type' => $account_type,
        ]);
    }

    public function create()
    {
        $isEdit = false;
        $this->load->view('views/accounttype/form.php', [
            'isEdit' => $isEdit,
        ]);
    }

    public function store()
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            $this->load->view('views/accounttype/form.php', [
                'isEdit' => $isEdit,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->create([
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
        ]);

        header('Location: ' . BASE_URL . '/account_type');
        exit;
    }

    public function edit($id)
    {
        $account_type = $this->model->getById($id);

        if ($account_type === null) {
            http_response_code(404);
            echo '404 - account_type not found';
            return;
        }

        $isEdit = true;
        $this->load->view('views/accounttype/form.php', [
            'isEdit'    => $isEdit,
            'id'        => $id,
            'account_type' => $account_type,
        ]);
    }

    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - account_type not found';
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = true;
            $account_type = $this->model->getById($id);
            $this->load->view('views/accounttype/form.php', [
                'isEdit'    => $isEdit,
                'id'        => $id,
                'account_type' => $account_type,
                'errors'    => $errors,
            ]);
            return;
        }

        $this->model->update($id, [
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
        ]);

        header('Location: ' . BASE_URL . '/account_type');
        exit;
    }

    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - account_type not found';
            return;
        }

        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/account_type');
        exit;
    }
}
