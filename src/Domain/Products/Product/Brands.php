<?php

namespace Hoo\ProductFeeds\Domain\Products\Product;

use Hoo\ProductFeeds\Collection;

class Brands extends Collection\AbstractCollection
{
	public function __construct(
		Brands\Brand ...$brands,
	) {
		$this->items = $brands;
	}

	public function get(Collection\Item\Key\KeyInterface $key): Brands\Brand
	{
		return parent::get($key);
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
		$key = $brand->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $brand;
	}
}