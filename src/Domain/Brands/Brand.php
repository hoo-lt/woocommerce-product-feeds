<?php

namespace Hoo\ProductFeeds\Domain\Brands;

use Hoo\WordPressPluginFramework\Collection;

class Brand implements Collection\Item\ItemInterface
{
	public function __construct(
		protected readonly Brand\Id $id,
		protected ?Brand\Id $parentId,
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