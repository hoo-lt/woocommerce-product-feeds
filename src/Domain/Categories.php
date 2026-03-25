<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain;

use Hoo\WordPressPluginFramework\Collection;

class Categories extends Collection\AbstractCollection
{
	public function __construct(
		Categories\Category ...$items,
	) {
		$this->items = $items;
	}

	public function get(Collection\Item\Key\KeyInterface $key): ?Categories\Category
	{
		return parent::get($key);
	}

	public function first(): ?Categories\Category
	{
		return parent::first();
	}

	public function last(): ?Categories\Category
	{
		return parent::last();
	}

	public function add(Categories\Category $item): void
	{
		$key = $item->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $item;
	}
}