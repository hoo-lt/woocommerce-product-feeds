<?php

namespace Hoo\ProductFeeds\Domain\Products\Product\Attributes;

use Hoo\WordPressPluginFramework\Collection;

class Attribute implements Collection\Item\ItemInterface
{
	public Attribute\Terms $terms;

	public function __construct(
		public readonly Attribute\Slug $slug,
	) {
		$this->terms = new Attribute\Terms();
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->slug;
	}
}