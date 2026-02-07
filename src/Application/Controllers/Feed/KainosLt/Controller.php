<?php

namespace Hoo\ProductFeeds\Application\Controllers\Feed\KainosLt;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Controller implements Application\Controllers\Feed\ControllerInterface
{
	public function __construct(
	) {
	}

	public function path(): string
	{
		return 'kainos-lt.xml';
	}

	public function __invoke(): string
	{
		return '';
	}
}
