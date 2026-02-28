<?php

namespace Hoo\ProductFeeds\Domain\Categories;

use Hoo\WordPressPluginFramework\Collection;

class Category implements Collection\Item\ItemInterface
{
	public function __construct(
		public readonly Category\Id $id,
		public ?Category\Id $parentId,
		public string $name,
		public string $url,
	) {
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}