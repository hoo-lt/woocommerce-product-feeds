<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Products\Product\AttributeSlugs\AttributeSlug;

use Hoo\WordPressPluginFramework\Collection;

class TermSlugs extends Collection\AbstractCollection
{
	public function __construct(
		TermSlugs\TermSlug ...$items,
	) {
		$this->items = $items;
	}

	public function get(Collection\Item\Key\KeyInterface $key): ?TermSlugs\TermSlug
	{
		return parent::get($key);
	}

	public function first(): ?TermSlugs\TermSlug
	{
		return parent::first();
	}

	public function last(): ?TermSlugs\TermSlug
	{
		return parent::last();
	}

	public function add(TermSlugs\TermSlug $item): void
	{
		$key = $item->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $item;
	}
}