<?php

namespace Hoo\ProductFeeds\Domain\Brands\Brand;

use Hoo\WordpressPluginFramework\Collection;

class Id implements Collection\Item\Key\KeyInterface
{
	public function __construct(
		protected int $id,
	) {
	}

	public function __invoke(): int|string
	{
		return $this->id;
	}
}