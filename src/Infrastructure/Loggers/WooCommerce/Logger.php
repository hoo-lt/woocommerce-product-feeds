<?php

namespace Hoo\ProductFeeds\Infrastructure\Loggers\WooCommerce;

use WC_Logger_Interface;

class Logger
{
	protected const SOURCE = 'product-feeds';

	public function __construct(
		protected readonly WC_Logger_Interface $wcLogger,
	) {
	}

	public function info(string $message): void
	{
		$this->wcLogger->info($message, [
			'source' => self::SOURCE . '-infos',
		]);
	}
}