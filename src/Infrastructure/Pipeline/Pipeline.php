<?php

namespace Hoo\ProductFeeds\Infrastructure\Pipeline;

use Hoo\ProductFeeds\Infrastructure;
use Psr\Container\ContainerInterface;

class Pipeline
{
	protected array $middlewares = [];

	protected object $object;

	public function __construct(
		protected readonly ContainerInterface $container,
	) {
	}

	public function object(object $object): self
	{
		$this->object = $object;
		return $this;
	}

	public function middleware(string $middleware): self
	{
		$this->add($middleware);
		return $this;
	}

	public function middlewares(string ...$middlewares): self
	{
		foreach ($middlewares as $middleware) {
			$this->add($middleware);
		}
		return $this;
	}

	public function __invoke(callable $callable): mixed
	{
		return array_reduce(array_reverse($this->middlewares), fn($callable, $middleware) => fn($object) => $middleware($object, $callable), $callable)($this->object);
	}

	protected function add(string $middleware): void
	{
		$middleware = $this->container->get($middleware);
		if (!$middleware instanceof Infrastructure\Middlewares\MiddlewareInterface) {
			//throw there
		}

		$this->middlewares[] = $middleware;
	}
}