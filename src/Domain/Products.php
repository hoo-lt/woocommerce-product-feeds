<?php

namespace Hoo\ProductFeeds\Domain;

use Hoo\WordPressPluginFramework\Collection;

class Products extends Collection\AbstractCollection
{
	public function __construct(
		Products\Product ...$products,
	) {
		$this->items = $products;
	}

	public function get(Collection\Item\Key\KeyInterface $key): Products\Product
	{
		return parent::get($key);
	}

	public function first(): ?Products\Product
	{
		return parent::first();
	}

	public function last(): ?Products\Product
	{
		return parent::last();
	}

	public function add(Products\Product $product): void
	{
		$key = $product->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $product;
	}
}