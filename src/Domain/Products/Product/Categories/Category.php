<?php

namespace Hoo\ProductFeeds\Domain\Products\Product\Categories;

use Hoo\WordPressPluginFramework\Collection;

class Category implements Collection\Item\ItemInterface
{
	public function __construct(
		protected readonly Category\Id $id,
	) {
	}

	public function id(): int
	{
		return ($this->id)();
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}