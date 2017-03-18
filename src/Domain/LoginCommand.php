<?php
declare(strict_types=1);

namespace Demo\Domain;

class LoginCommand
{
    public static function forUser(string $completeUrl): self
    {
        return new static($completeUrl);
    }

    public function completeUrl(): string
    {
        return $this->completeUrl;
    }

    public function scopes(): array
    {
        return ['user', 'repo'];
    }

    private $completeUrl;

    private function __construct(string $completeUrl)
    {
        $this->completeUrl = $completeUrl;
    }
}
