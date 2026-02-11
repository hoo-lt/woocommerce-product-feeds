<?php

namespace Hoo\ProductFeeds\Application\Controllers\ProductFeed;

interface ControllerInterface
{
	public function __invoke(): string;

	public function path(): string;
}
