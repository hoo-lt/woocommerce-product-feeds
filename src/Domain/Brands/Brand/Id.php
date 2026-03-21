<?php

namespace Hoo\ProductFeeds\Domain\Brands\Brand;

use Hoo\WordPressPluginFramework\Collection;

readonly class Id implements Collection\Item\Key\KeyInterface
{
	public function __construct(
		protected int $id,
	) {
	}

	public function __invoke(): int
	{
		return $this->id;
	}
}