<?php

namespace Hoo\ProductFeeds\Domain\Brands;

use Hoo\WordPressPluginFramework\Collection;

class Brand implements Collection\Item\ItemInterface
{
	public function __construct(
		public readonly Brand\Id $id,
		public ?Brand\Id $parentId,
		public string $name,
		public string $url,
	) {
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}