<?php

namespace Hoo\ProductFeeds\Domain\Products\Product;

use Hoo\ProductFeeds\Domain;

class Brands extends Domain\AbstractCollection
{
	public function get(int $id): Brands\Brand
	{
		return parent::get($id);
	}

	public function first(): ?Brands\Brand
	{
		return parent::first();
	}

	public function last(): ?Brands\Brand
	{
		return parent::last();
	}

	public function add(Brands\Brand $brand): void
	{
		if ($this->has($brand->id)) {
			return; //throw domain exception
		}

		$this->items[$brand->id] = $brand;
	}
}