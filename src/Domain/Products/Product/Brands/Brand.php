<?php

namespace Hoo\ProductFeeds\Domain\Products\Product\Brands;

use Hoo\ProductFeeds\Collection;

class Brand implements Collection\Item\ItemInterface
{
	public function __construct(
		public Brand\Id $id,
	) {
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}