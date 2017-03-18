<?php
declare(strict_types=1);

namespace Demo\Domain;

class FetchFollowersCommand
{
    public static function forToken(string $token): self
    {
        return new static($token);
    }

    public function token(): string
    {
        return $this->token;
    }

    private $token;

    private function __construct(string $token)
    {
        $this->token = $token;
    }
}
