<?php
declare(strict_types=1);

namespace Demo\Domain;

class FetchTokenCommand
{
    public static function forCode(string $code, string $state): self
    {
        return new static($code, $state);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function state(): string
    {
        return $this->state;
    }

    private $code;
    private $state;

    private function __construct(string $code, string $state)
    {
        $this->code = $code;
        $this->state = $state;
    }
}
