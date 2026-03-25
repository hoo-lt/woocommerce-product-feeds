<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Products\Product\AttributeSlugs\AttributeSlug\TermSlugs;

use Hoo\WordPressPluginFramework\Collection;

readonly class TermSlug implements Collection\Item\ItemInterface, Collection\Item\Key\KeyInterface
{
	public function __construct(
		protected string $slug,
	) {
	}

	public function __invoke(): string
	{
		return $this->slug;
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this;
	}
}