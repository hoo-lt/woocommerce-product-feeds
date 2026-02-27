<?php

namespace Hoo\ProductFeeds\Infrastructure\Middlewares;

interface MiddlewareInterface
{
	public function __invoke(object $object, callable $callable): mixed;
}