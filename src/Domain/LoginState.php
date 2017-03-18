<?php
declare(strict_types=1);

namespace Demo\Domain;

class LoginState
{
    private $url;
    private $state;

    public function __construct(string $url, string $state)
    {
        $this->url = $url;
        $this->state = $state;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function url(): string
    {
        return $this->url;
    }
}
