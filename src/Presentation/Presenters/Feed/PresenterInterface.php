<?php

namespace Hoo\ProductFeeds\Presentation\Presenters\Feed;

use Hoo\WordPressPluginFramework\Http;

interface PresenterInterface
{
	public function present(): Http\ResponseInterface;

	public function path(): string;
}
