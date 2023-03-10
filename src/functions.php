<?php

namespace Bermuda\Stdlib;

function curry(callable $callback, ... $args): Curry
{
    return new Curry($callback, ... $args);
}
