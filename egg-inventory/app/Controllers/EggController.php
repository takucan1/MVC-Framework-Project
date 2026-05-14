<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\Validator;
use App\Models\EggRepositoryInterface;
use Core\Http\Request;
use Core\Http\Response;
use Core\View\Engine;

/**
 * Handles all egg inventory HTTP actions.
 *
 * DIP : Depends on EggRepositoryInterface, never on a concrete class.
 * SRP : Only handles HTTP in/out and delegates to the repository and view engine.
 *       Never builds SQL or renders raw HTML strings.
 */
class EggController
{
    public function __construct(
        private readonly EggRepositoryInterface $eggs,
        private readonly Engine                  $view,
        private readonly Validator               $validator
    ) {}

    /** GET /eggs — list all eggs with stock summary */
    public function index(Request $request): Response
    {
        $eggs    = $this->eggs->all();
        $summary = $this->eggs->stockSummary();
        $content = $this->view->render('eggs.index', compact('eggs', 'summary'));
        return Response::html($content);
    }

    /** GET /eggs/create — show creation form */
    public function create(Request $request): Response
    {
        $errors = [];
        $old    = [];
        $content = $this->view->render('eggs.create', compact('errors', 'old'));
        return Response::html($content);
    }

    /** POST /eggs — store a new egg batch */
    public function store(Request $request): Response
    {
        $data = [
            'egg_type'    => $request->input('egg_type', ''),
            'batch_label' => $request->input('batch_label', ''),
            'quantity'    => $request->input('quantity', ''),
            'unit_price'  => $request->input('unit_price', ''),
            'notes'       => $request->input('notes', ''),
        ];

        $valid = $this->validator->validate($data, [
            'egg_type'    => 'required|in:quail,white,brown',
            'batch_label' => 'required',
            'quantity'    => 'required|numeric|min:1|max:99999',
            'unit_price'  => 'required|numeric|min:0',
        ]);

        if (!$valid) {
            $errors  = $this->validator->errors();
            $old     = $data;
            $content = $this->view->render('eggs.create', compact('errors', 'old'));
            return Response::html($content, 422);
        }

        $this->eggs->save([
            'egg_type'    => $data['egg_type'],
            'batch_label' => htmlspecialchars($data['batch_label'], ENT_QUOTES),
            'quantity'    => (int)$data['quantity'],
            'unit_price'  => (float)$data['unit_price'],
            'notes'       => htmlspecialchars($data['notes'] ?? '', ENT_QUOTES),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return Response::redirect('/eggs');
    }

    /** GET /eggs/{id} — show a single egg batch */
    public function show(Request $request): Response
    {
        $id  = (int)$request->routeParam('id');
        $egg = $this->eggs->find($id);

        if ($egg === null) {
            return Response::html('<h1>Egg batch not found.</h1>', 404);
        }

        $content = $this->view->render('eggs.show', compact('egg'));
        return Response::html($content);
    }

    /** GET /eggs/{id}/edit — show edit form */
    public function edit(Request $request): Response
    {
        $id  = (int)$request->routeParam('id');
        $egg = $this->eggs->find($id);

        if ($egg === null) {
            return Response::html('<h1>Egg batch not found.</h1>', 404);
        }

        $errors = [];
        $content = $this->view->render('eggs.edit', compact('egg', 'errors'));
        return Response::html($content);
    }

    /** POST /eggs/{id}/update — apply edits */
    public function update(Request $request): Response
    {
        $id  = (int)$request->routeParam('id');
        $egg = $this->eggs->find($id);

        if ($egg === null) {
            return Response::html('<h1>Egg batch not found.</h1>', 404);
        }

        $data = [
            'egg_type'    => $request->input('egg_type', ''),
            'batch_label' => $request->input('batch_label', ''),
            'quantity'    => $request->input('quantity', ''),
            'unit_price'  => $request->input('unit_price', ''),
            'notes'       => $request->input('notes', ''),
        ];

        $valid = $this->validator->validate($data, [
            'egg_type'    => 'required|in:quail,white,brown',
            'batch_label' => 'required',
            'quantity'    => 'required|numeric|min:1|max:99999',
            'unit_price'  => 'required|numeric|min:0',
        ]);

        if (!$valid) {
            $errors  = $this->validator->errors();
            $content = $this->view->render('eggs.edit', compact('egg', 'errors'));
            return Response::html($content, 422);
        }

        $this->eggs->update($id, [
            'egg_type'    => $data['egg_type'],
            'batch_label' => htmlspecialchars($data['batch_label'], ENT_QUOTES),
            'quantity'    => (int)$data['quantity'],
            'unit_price'  => (float)$data['unit_price'],
            'notes'       => htmlspecialchars($data['notes'] ?? '', ENT_QUOTES),
        ]);

        return Response::redirect('/eggs');
    }

    /** POST /eggs/{id}/delete — remove a batch */
    public function destroy(Request $request): Response
    {
        $id = (int)$request->routeParam('id');
        $this->eggs->delete($id);
        return Response::redirect('/eggs');
    }
}
