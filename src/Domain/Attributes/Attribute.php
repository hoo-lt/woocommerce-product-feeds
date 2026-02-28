<?php

namespace Hoo\ProductFeeds\Domain\Attributes;

use Hoo\WordPressPluginFramework\Collection;

class Attribute implements Collection\Item\ItemInterface
{
	public function __construct(
		protected readonly Attribute\Slug $slug,
		public string $name,
	) {
	}

	public function slug(): string
	{
		return ($this->slug)();
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->slug;
	}
}