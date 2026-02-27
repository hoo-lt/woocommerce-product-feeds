<?php

namespace Hoo\ProductFeeds\Domain;

class Products extends AbstractCollection
{
	public function get(int $id): Products\Product
	{
		return parent::get($id);
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
		if ($this->has($product->id)) {
			return; //throw domain exception
		}

		$this->items[$product->id] = $product;
	}
}