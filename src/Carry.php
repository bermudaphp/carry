<?php

namespace Bermuda\Stdlib;

use Bermuda\Reflection\TypeMatcher;

final class Carry
{
    private $callback;
    private array $arguments = [];
    private int $argumentsCount = 0;
    private bool $useDefaultValues = false;

    private readonly TypeMatcher $typeMatcher;

    /**
     * @var ReflectionParameter[]
     */
    private ?array $params;

    /**
     * @throws ReflectionException
     */
    public function __construct(callable $callback, ... $arguments)
    {
        $this->callback = $callback;
        $this->typeMatcher = new TypeMatcher();
        $this->params = (new \ReflectionFunction($this->callback))->getParameters();
        $this->addArguments($arguments);
    }

    /**
     * @param ... $args
     * @return Carry|mixed
     * @throws ReflectionException
     */
    public function __invoke(... $arguments): mixed
    {
        return $this->call(... $arguments);
    }

    /**
     * @param ...$arguments
     * @return mixed
     * @throws ReflectionException
     */
    public function call(... $arguments): mixed
    {
        if (($count = count(($copy = $this->add(... $arguments))->arguments)) >= count($copy->params)) {
            return ($copy->callback)(...$copy->arguments);
        }

        if ($copy->useDefaultValues) {
            foreach (array_slice($copy->params, $count) as $parameter) {
                if ($parameter->isDefaultValueAvailable()) {
                    $copy->arguments[] = $parameter->getDefaultValue();
                    $count++;
                }
            }
        }

        return $count >= count($copy->params) ? ($copy->callback)(... $copy->arguments) : $copy;
    }

    /**
     * @param ...$args
     * @return self
     */
    public function add(... $args): self
    {
        $copy = clone $this;
        $copy->addArguments($args);

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

    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    private function addArguments(array $arguments): void
    {
        if ($this->params === [] || count($this->params) === $this->argumentsCount) return;

        foreach ($arguments as $argument) {
            $type = ($param = $this->params[$this->argumentsCount])->getType();
            if ($type !== null && !$this->typeMatcher->match($type, $argument)) {
                throw new InvalidArgumentException(
                    sprintf(
                        "Argument #%s ($%s) must be of type %s. %s given.",
                        $this->argumentsCount + 1, $param->getName(),
                        (string) $type, is_object($argument) ? $argument::class : gettype($argument)
                    )
                );
            }

            $this->arguments[$this->argumentsCount++] = $argument;
        }
    }
}
