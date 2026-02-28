<?php

namespace Hoo\ProductFeeds\Infrastructure\Middleware\LogExecutionTime;

use Hoo\WordPressPluginFramework\Middleware\MiddlewareInterface;
use Hoo\WordPressPluginFramework\Logger\LoggerInterface;

class Middleware implements MiddlewareInterface
{
	protected const SOURCE = 'product-feeds';

	public function __construct(
		protected readonly LoggerInterface $logger,
	) {
	}

	public function __invoke(object $object, callable $callable): mixed
	{
		$startTime = microtime();

		$result = $callable($object);

		$stopTime = microtime();

		$this->logger->info(sprintf('Object: %s | Execution time: %d ms', $object::class, ($stopTime - $startTime) * 1000));

		return $result;
	}
}