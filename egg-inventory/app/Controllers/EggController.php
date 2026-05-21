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
            $this->egg->create(['type' => $request->input('type'), 'quantity' => $request->input('quantity')]);
            header("Location: /eggs");
            exit;
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
