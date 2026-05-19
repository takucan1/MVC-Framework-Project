<?php
namespace Core\View;

class Engine {
    public function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . '/../../app/Views/' . $view . '.php';
    }
}
