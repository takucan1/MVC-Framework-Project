<?php
/**
 * Wraps $content in the shared layout and returns the full HTML page.
 * Called from every view template.
 */
function layout(string $content, string $title = 'Egg Inventory'): string
{
    ob_start();
    require __DIR__ . '/app.php';
    return ob_get_clean();
}
