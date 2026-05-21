<?php
namespace App\Controllers;

use App\Models\Egg;
use Core\View\Engine;
use Core\Http\Request;

class EggController {
    private Egg $model;
    private Engine $view;

    public function __construct(Egg $model, Engine $view) {
        $this->model = $model;
        $this->view = $view;
    }

    public function index(Request $request): void {
        $eggs = $this->model->all();
        $this->view->render('eggs/index', ['eggs' => $eggs]);
    }

    public function show(Request $request): void {
        $id = (int)$request->input('id');
        $egg = $this->model->find($id);
        $this->view->render('eggs/show', ['egg' => $egg]);
    }

    public function create(Request $request): void {
        if ($request->method() === 'POST') {
            $type = trim($request->input('type'));
            $quantity = trim($request->input('quantity'));

            $errors = [];

            if ($type === '') {
                $errors[] = 'Type field cannot be empty.';
            }

            if ($quantity === '' || !is_numeric($quantity) || $quantity <= 0) {
                $errors[] = 'Quantity must be a positive number.';
            }

            if (empty($errors)) {
                $this->model->create([
                    'type' => $type,
                    'quantity' => (int)$quantity
                ]);
                header('Location: /eggs');
                exit;
            }

            $this->view->render('eggs/create', ['errors' => $errors]);
        } else {
            $this->view->render('eggs/create');
        }
    }

    public function edit(Request $request): void {
        $id = (int)$request->input('id');
        if ($request->method() === 'POST') {
            $type = trim($request->input('type'));
            $quantity = trim($request->input('quantity'));

            $errors = [];

            if ($type === '') {
                $errors[] = 'Type field cannot be empty.';
            }

            if ($quantity === '' || !is_numeric($quantity) || $quantity <= 0) {
                $errors[] = 'Quantity must be a positive number.';
            }

            if (empty($errors)) {
                $this->model->update($id, [
                    'type' => $type,
                    'quantity' => (int)$quantity
                ]);
                header('Location: /eggs');
                exit;
            }

            $egg = $this->model->find($id);
            $this->view->render('eggs/edit', ['egg' => $egg, 'errors' => $errors]);
        } else {
            $egg = $this->model->find($id);
            $this->view->render('eggs/edit', ['egg' => $egg]);
        }
    }

    public function delete(Request $request): void {
        $id = (int)$request->input('id');
        $this->model->delete($id);
        header('Location: /eggs');
        exit;
    }
}
