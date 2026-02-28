<?php

namespace Hoo\ProductFeeds\Domain\Products\Product\Attributes\Attribute\Terms;

use Hoo\WordPressPluginFramework\Collection;

class Term implements Collection\Item\ItemInterface
{
	public function __construct(
		public readonly Term\Id $id,
	) {
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}