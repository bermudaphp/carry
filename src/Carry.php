<?php

namespace Bermuda\Stdlib;

final class Carry
{
    private $callback;
    private array $args;
    private bool $useDefaultValues = false;

    /**
     * @var ReflectionParameter[]
     */
    private ?array $params = null;

    public function __construct(callable $callback, ... $args)
    {
        $this->args = $args;
        $this->callback = $callback;
    }

    /**
     * @param ... $args
     * @return Carry|mixed
     * @throws ReflectionException
     */
    public function __invoke(... $args): mixed
    {
        return $this->call(... $args);
    }

    /**
     * @param ...$args
     * @return Carry|mixed
     * @throws ReflectionException
     */
    public function call(... $args): mixed
    {
        if (!$this->params) $this->params = (new ReflectionFunction($this->callback))->getParameters();
        if (($count = count(($copy = $this->add(... $args))->args)) >= count($copy->params)) {
            return ($copy->callback)(...$copy->args);
        }

        if ($copy->useDefaultValues) {
            foreach (array_slice($copy->params, $count) as $parameter) {
                if ($parameter->isDefaultValueAvailable()) {
                    $copy->args[] = $parameter->getDefaultValue();
                    $count++;
                }
            }
        }

        return $count >= count($copy->params) ? ($copy->callback)(... $copy->args) : $copy;
    }

    /**
     * @param ... $args
     * @return Carry
     */
    public function add(... $args): self
    {
        $copy = clone $this;
        foreach ($args as $arg) $copy->args[] = $arg;

        return $copy;
    }

    public function useDefaultValues(bool $mode): self
    {
        $copy = clone $this;
        $copy->useDefaultValues = $mode;

        return $copy;
    }

    public static function use(callable $callback, ... $args): self
    {
        $self = new self($callback, ... $args);
        $self->useDefaultValues = true;

        return $self;
    }
}
