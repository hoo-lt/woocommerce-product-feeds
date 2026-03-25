<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Products\Product\AttributeSlugs;

use Hoo\WordPressPluginFramework\Collection;

readonly class AttributeSlug implements Collection\Item\ItemInterface, Collection\Item\Key\KeyInterface
{
	public AttributeSlug\TermSlugs $termSlugs;

	public function __construct(
		protected string $slug,
	) {
		$this->termSlugs = new AttributeSlug\TermSlugs();
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