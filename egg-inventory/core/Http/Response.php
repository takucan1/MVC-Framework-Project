<?php

declare(strict_types=1);

namespace Core\Http;

/**
 * Wraps HTTP response output.
 * SRP: Only responsible for sending HTTP responses.
 */
class Response
{
    private int    $statusCode = 200;
    private string $body       = '';
    private array  $headers    = [];

    public function setStatus(int $code): static
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader(string $name, string $value): static
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->body;
    }

    public static function redirect(string $url, int $status = 302): static
    {
        $response = new static();
        $response->setStatus($status);
        $response->setHeader('Location', $url);
        return $response;
    }

    public static function html(string $content, int $status = 200): static
    {
        $response = new static();
        $response->setStatus($status);
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->setBody($content);
        return $response;
    }
}
