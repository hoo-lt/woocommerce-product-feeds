<?php

namespace Hoo\ProductFeeds\Domain\Products\Product;

use Hoo\WordPressPluginFramework\Collection;

class Categories extends Collection\AbstractCollection
{
	public function __construct(
		Categories\Category ...$categories,
	) {
		$this->items = $categories;
	}

	public function get(Collection\Item\Key\KeyInterface $key): Categories\Category
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

	public function add(Categories\Category $categories): void
	{
		$key = $categories->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $categories;
	}
}