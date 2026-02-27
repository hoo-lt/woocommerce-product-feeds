<?php

namespace Hoo\ProductFeeds\Infrastructure\Middlewares\Log;

use Hoo\ProductFeeds\Infrastructure;

class Middleware implements Infrastructure\Middlewares\MiddlewareInterface
{
	public function __invoke(object $object, callable $next): mixed
	{
		$startTime = microtime(true);

		$result = $next($object);

		$executionTime = microtime(true) - $startTime;

		// 3. Логируем через стандартный механизм WooCommerce
		$logger = wc_get_logger();
		$logger->info(
			sprintf(
				'Object: %s | Execution Time: %f seconds',
					$object::class,
				$executionTime
			),
			[
				'source' => 'product-feeds', // Это создаст файл product-feeds-YYYY-MM-DD-hash.log
			]
		);

		return $result;
	}
}