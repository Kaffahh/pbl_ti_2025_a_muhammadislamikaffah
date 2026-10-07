<?php

require_once __DIR__ . '/../models/ActionsModel.php';

class Actions
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new ActionsModel();
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
        $actions = $this->model->getAll();
        $this->load->view('views/actions/index.php', [
            'actions' => $actions,
        ]);
    }

    public function create()
    {
        $isEdit = false;
        $this->load->view('views/actions/form.php', [
            'isEdit' => $isEdit,
        ]);
    }

    public function store()
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            $this->load->view('views/actions/form.php', [
                'isEdit' => $isEdit,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->create([
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/actions');
        exit;
    }

    public function edit($id)
    {
        $actions = $this->model->getById($id);

        if ($actions === null) {
            http_response_code(404);
            echo '404 - Actions not found';
            return;
        }

        $isEdit = true;
        $this->load->view('views/actions/form.php', [
            'isEdit'    => $isEdit,
            'id'        => $id,
            'actions' => $actions,
        ]);
    }

    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Actions not found';
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = true;
            $actions = $this->model->getById($id);
            $this->load->view('views/actions/form.php', [
                'isEdit'    => $isEdit,
                'id'        => $id,
                'actions' => $actions,
                'errors'    => $errors,
            ]);
            return;
        }

        $this->model->update($id, [
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/actions');
        exit;
    }

    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Actions not found';
            return;
        }

        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/actions');
        exit;
    }
}
