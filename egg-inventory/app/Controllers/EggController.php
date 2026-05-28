<?php
namespace App\Controllers;

use App\Models\Egg;
use Core\View\Engine;
use Core\Http\Request;

enum EggType: string {
    case Brown = 'Brown Egg';
    case White = 'White Egg';
    case Quail = 'Quail Egg';
}

class EggController {
    public readonly Egg $egg;
    public readonly Engine $view;

    public function __construct(Egg $egg, Engine $view) {
        $this->egg = $egg;
        $this->view = $view;
    }

    public function index(): void {
        $eggs = $this->egg->all();
        $this->view->render('eggs/index', ['eggs' => $eggs]);
    }

    public function create(Request $request): void {
        if ($request->method() === 'POST') {
            $type = trim($request->input('type'));
            $quantity = trim($request->input('quantity'));

            $errors = match (true) {
                $type === '' => ['Type field cannot be empty.'],
                $quantity === '' || !is_numeric($quantity) || (int)$quantity <= 0 => ['Quantity must be a positive number.'],
                default => [],
            };

            if (empty($errors)) {
                $validType = EggType::tryFrom($type);
                if (!$validType) {
                    $errors[] = 'Invalid egg type.';
                } else {
                    $this->egg->create([
                        'type' => $validType->value,
                        'quantity' => (int)$quantity
                    ]);
                    header('Location: /eggs');
                    exit;
                }
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

        $errors = match (true) {
            $type === '' => ['Type field cannot be empty.'],
            $quantity === '' || !is_numeric($quantity) || (int)$quantity <= 0 => ['Quantity must be a positive number.'],
            default => [],
        };

        if (empty($errors)) {
            $validType = EggType::tryFrom($type);
            if (!$validType) {
                $errors[] = 'Invalid egg type.';
            } else {
                $this->egg->update($id, [
                    'type' => $validType->value,
                    'quantity' => (int)$quantity
                ]);
                header('Location: /eggs');
                exit;
            }
        }

        $egg = $this->egg->find($id);
        $this->view->render('eggs/edit', ['egg' => $egg, 'errors' => $errors]);
    } else {
        $egg = $this->egg->find($id);
        $this->view->render('eggs/edit', ['egg' => $egg]);
    }
    }

    public function show(Request $request): void {
        $id = (int)$request->input('id');
        $egg = $this->egg->find($id);

        if ($egg) {
            $this->view->render('eggs/show', ['egg' => $egg]);
        } else {
            $this->view->render('errors/404', ['message' => 'Egg not found']);
        }
    }

    public function delete(Request $request): void {
        $id = (int)$request->input('id');
        $this->egg->delete($id);

        header('Location: /eggs');
        exit;
    }
}
