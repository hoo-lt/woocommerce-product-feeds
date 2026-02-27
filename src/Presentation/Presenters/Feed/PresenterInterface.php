<?php

namespace Hoo\ProductFeeds\Presentation\Presenters\Feed;

use Hoo\ProductFeeds\Infrastructure;

interface PresenterInterface
{
	public function present(): Infrastructure\Http\Response;

	public function path(): string;
}
