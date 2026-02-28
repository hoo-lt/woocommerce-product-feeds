<?php

namespace Hoo\ProductFeeds\Domain\Brands;

use Hoo\WordpressPluginFramework\Collection;

class Brand implements Collection\Item\ItemInterface
{
	public function __construct(
		public Brand\Id $id,
		public string $name,
		public string $url,
	) {
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}