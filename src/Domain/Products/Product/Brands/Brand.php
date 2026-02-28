<?php

namespace Hoo\ProductFeeds\Domain\Products\Product\Brands;

use Hoo\WordPressPluginFramework\Collection;

class Brand implements Collection\Item\ItemInterface
{
	public function __construct(
		protected readonly Brand\Id $id,
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