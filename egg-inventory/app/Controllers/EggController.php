<?php
namespace App\Controllers;

use App\Models\Egg;
use Core\View\Engine;
use Core\Http\Request;

class EggController {
    private Egg $egg;
    private Engine $view;

    public function __construct(Egg $egg, Engine $view) {
        $this->egg = $egg;
        $this->view = $view;
    }

    public function index(): void {
        $eggs = $this->egg->all();
        $this->view->render('eggs/index', ['eggs' => $eggs]);
    }

    public function show(Request $request): void {
        $id = (int)$request->input('id');
        $egg = $this->egg->find($id);
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

        if ($quantity === '' || !is_numeric($quantity) || (int)$quantity <= 0) {
            $errors[] = 'Quantity must be a positive number.';
        }

        if (empty($errors)) {
            $this->egg->create([
                'type' => $type,
                'quantity' => (int)$quantity
            ]);
            header('Location: /eggs');
            exit;
        }

        // Render form again with errors
        $this->view->render('eggs/create', ['errors' => $errors]);
    } else {
        $this->view->render('eggs/create');
    }
    }


    public function edit(Request $request): void {
        $id = (int)$request->input('id');
        if ($request->method() === 'POST') {
            $this->egg->update($id, ['type' => $request->input('type'), 'quantity' => $request->input('quantity')]);
            header("Location: /eggs");
            exit;
        } else {
            $egg = $this->egg->find($id);
            $this->view->render('eggs/edit', ['egg' => $egg]);
        }
    }

    public function delete(Request $request): void {
        $id = (int)$request->input('id');
        $this->egg->delete($id);
        header("Location: /eggs");
        exit;
    }
}
