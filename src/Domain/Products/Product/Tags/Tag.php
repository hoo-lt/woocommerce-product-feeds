<?php

namespace Hoo\ProductFeeds\Domain\Products\Product\Tags;

use Hoo\WordPressPluginFramework\Collection;

class Tag implements Collection\Item\ItemInterface
{
	public function __construct(
		public readonly Tag\Id $id,
	) {
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}