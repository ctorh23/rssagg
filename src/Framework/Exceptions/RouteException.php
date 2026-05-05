<?php

declare(strict_types=1);

namespace RssAgg\Framework\Exceptions;

final class RouteException extends \RuntimeException
{
    /**
     * @var array<string, string>
     */
    private ?array $headers;

    public function __construct(string $message, int $code, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->headers = null;
    }

    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function allHeaders(): ?array
    {
        return $this->headers;
    }

    public static function notFound(): self
    {
        return new self('Not Found', 404);
    }

    public static function methodNotAllowed(): self
    {
        return new self('Method Not Allowed', 405)->addHeader('Allow', 'GET, POST, HEAD');
    }

    public static function unknownError(): self
    {
        return new self('Internal Server Error', 500);
    }
}
