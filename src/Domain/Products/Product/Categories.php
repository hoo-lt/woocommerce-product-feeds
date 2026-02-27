<?php

namespace Hoo\ProductFeeds\Domain\Products\Product;

use Hoo\ProductFeeds\Domain;

class Categories extends Domain\AbstractCollection
{
	public function get(int $id): Categories\Category
	{
		return parent::get($id);
	}

	public function first(): ?Categories\Category
	{
		return parent::first();
	}

	public function last(): ?Categories\Category
	{
		return parent::last();
	}

	public function add(Categories\Category $category): void
	{
		if ($this->has($category->id)) {
			return; //throw domain exception
		}

		$this->items[$category->id] = $category;
	}
}