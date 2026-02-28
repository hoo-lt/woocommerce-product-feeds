<?php

namespace Hoo\ProductFeeds\Domain\Tags;

use Hoo\WordPressPluginFramework\Collection;

class Tag implements Collection\Item\ItemInterface
{
	public function __construct(
		public readonly Tag\Id $id,
		public ?Tag\Id $parentId,
		public string $name,
		public string $url,
	) {
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}