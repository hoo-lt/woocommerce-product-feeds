<?php

namespace Hoo\ProductFeeds\Infrastructure\Middlewares\WooCommerce\Logger;

use Hoo\ProductFeeds\Infrastructure;
use WC_Logger_Interface;

class Middleware implements Infrastructure\Middlewares\MiddlewareInterface
{
	protected const SOURCE = 'product-feeds';

	public function __construct(
		protected readonly WC_Logger_Interface $wcLogger,
	) {
	}

	public function __invoke(object $object, callable $callable): mixed
	{
		$startTime = microtime();

		$result = $callable($object);

		$stopTime = microtime();

		$this->info(sprintf('Object: %s | Execution time: %d ms', $object::class, ($stopTime - $startTime) * 1000));

		return $result;
	}

	protected function info(string $message): void
	{
		$this->wcLogger->info($message, [
			'source' => self::SOURCE . '-infos',
		]);
	}
}