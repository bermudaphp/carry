<?php

namespace Bermuda\Stdlib;

function curry(callable $callback, ... $args): Carry
{
    return new Curry($callback, ... $args);
}
