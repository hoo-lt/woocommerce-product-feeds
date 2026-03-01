<?php

namespace Hoo\ProductFeeds\Domain\Products;

use Hoo\WordPressPluginFramework\Collection;
use Hoo\WordPressPluginFramework\Http;

class Product implements Collection\Item\ItemInterface
{
	public Product\Attributes $attributes;
	public Product\Brands $brands;
	public Product\Categories $categories;
	public Product\Tags $tags;

	public function __construct(
		protected readonly Product\Id $id,
		public string $name,
		public Http\UrlInterface $url,
		public float $price,
		public ?int $stock,
		public ?string $gtin,
	) {
		$this->attributes = new Product\Attributes();
		$this->brands = new Product\Brands();
		$this->categories = new Product\Categories();
		$this->tags = new Product\Tags();
	}

	public function id(): int
	{
		return ($this->id)();
	}

	public function key(): Collection\Item\Key\KeyInterface
	{
		return $this->id;
	}
}