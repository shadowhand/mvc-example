<?php
declare(strict_types=1);

namespace Cove;

interface Resolver
{
    /**
     * Resolve a class name into an object
     *
     * The object may be new or existing, depending on the underlying implementation.
     *
     * @param string $class
     *
     * @return object
     */
    public function get($class);
}
