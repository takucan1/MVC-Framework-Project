<?php
namespace Core\Http;

class Response {
    public function send(string $content): void {
        echo $content;
    }
}
