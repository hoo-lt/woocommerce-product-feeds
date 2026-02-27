<?php

namespace Hoo\ProductFeeds\Infrastructure\Pipeline;

use Hoo\ProductFeeds\Infrastructure;
use Psr\Container\ContainerInterface;

class Pipeline
{
	protected object $object;

	protected array $middlewares = [];

	public function __construct(
		protected readonly ContainerInterface $container,
	) {
	}

	public function object(object $object): self
	{
		$clone = clone $this;
		$clone->object = $object;

		return $clone;
	}

	public function middlewares(string ...$middlewares): self
	{
		$clone = clone $this;

		foreach ($middlewares as $middleware) {
			$middleware = $this->container->get($middleware);
			if (!$middleware instanceof Infrastructure\Middlewares\MiddlewareInterface) {
				//throw there
			}

			$clone->middlewares[] = $middleware;
		}

		return $clone;
	}

	public function __invoke(callable $callable): mixed
	{
		return array_reduce(array_reverse($this->middlewares), fn($callable, $middleware) => fn($object) => $middleware($object, $callable), $callable)($this->object);
	}
}