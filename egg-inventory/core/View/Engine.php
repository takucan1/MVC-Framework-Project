<?php

declare(strict_types=1);

namespace Core\View;

use RuntimeException;

/**
 * Renders PHP view templates.
 * SRP: Only responsible for rendering views — not routing, not data fetching.
 */
class Engine
{
    public function __construct(private readonly string $viewsPath) {}

    /**
     * Render a view file with provided data.
     * The view name uses dot notation: 'eggs.index' → views/eggs/index.php
     */
    public function render(string $view, array $data = []): string
    {
        $file = $this->viewsPath . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("View [{$view}] not found at {$file}.");
        }

        // Extract data into local scope for the template
        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        return ob_get_clean() ?: '';
    }
}
