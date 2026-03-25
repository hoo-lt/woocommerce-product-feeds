<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Attributes\Attribute\Terms;

use Hoo\WordPressPluginFramework\Collection;

class Term implements Collection\Item\ItemInterface
{
	public function __construct(
		protected readonly Term\Slug $slug,
		public string $name,
	) {
	}

	public function id(): string
	{
		return ($this->slug)();
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->slug;
	}
}