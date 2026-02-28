<?php

namespace Hoo\ProductFeeds\Domain\Categories;

use Hoo\WordPressPluginFramework\Collection;

class Category implements Collection\Item\ItemInterface
{
	public function __construct(
		protected readonly Category\Id $id,
		protected ?Category\Id $parentId,
		public string $name,
		public string $url,
	) {
	}

	public function id(): int
	{
		return ($this->id)();
	}

	public function parentId(): ?int
	{
		return $this->parentId ? ($this->parentId)() : null;
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}