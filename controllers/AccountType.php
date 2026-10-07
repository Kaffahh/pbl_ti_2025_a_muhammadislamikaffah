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

        return $errors;
    }

    public function index()
    {
        $accounttype = $this->model->getAll();
        $this->load->view('views/accounttype/index.php', [
            'accounttype' => $accounttype,
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
        ]);

        header('Location: ' . BASE_URL . '/accounttype');
        exit;
    }

    public function edit($id)
    {
        $accounttype = $this->model->getById($id);

        if ($accounttype === null) {
            http_response_code(404);
            echo '404 - AccountType not found';
            return;
        }

        $isEdit = true;
        $this->load->view('views/accounttype/form.php', [
            'isEdit'    => $isEdit,
            'id'        => $id,
            'accounttype' => $accounttype,
        ]);
    }

    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - AccountType not found';
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = true;
            $accounttype = $this->model->getById($id);
            $this->load->view('views/accounttype/form.php', [
                'isEdit'    => $isEdit,
                'id'        => $id,
                'accounttype' => $accounttype,
                'errors'    => $errors,
            ]);
            return;
        }

        $this->model->update($id, [
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/accounttype');
        exit;
    }

    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - AccountType not found';
            return;
        }

        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/accounttype');
        exit;
    }
}
