<?php

namespace Hoo\ProductFeeds\Application\Controllers\ProductFeed\Kaina24Lt;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Controller implements Application\Controllers\ProductFeed\ControllerInterface
{
	public function __construct(
	) {
	}

	public function path(): string
	{
		return 'kaina24-lt.xml';
	}

	public function __invoke(): string
	{
		return '';
	}
}
