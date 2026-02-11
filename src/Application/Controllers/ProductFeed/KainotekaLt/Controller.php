<?php

namespace Hoo\ProductFeeds\Application\Controllers\ProductFeed\KainotekaLt;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Controller implements Application\Controllers\ProductFeed\ControllerInterface
{
	public function __construct(
	) {
	}

	public function path(): string
	{
		return 'kainoteka-lt.xml';
	}

	public function __invoke(): string
	{
		return '';
	}
}
