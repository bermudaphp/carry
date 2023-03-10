<?php

namespace Bermuda\Stdlib;

function carry(callable $callback, ... $args): Carry
{
    return new Carry($callback, ... $args);
}
