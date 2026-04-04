<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Products\Product;

use Hoo\WordPressPluginFramework\Collection;

class ImageIds extends Collection\AbstractCollection
{
	public function __construct(
		ImageIds\ImageId ...$items,
	) {
		$this->items = $items;
	}

	public function get(Collection\Item\Key\KeyInterface $key): ?ImageIds\ImageId
	{
		return parent::get($key);
	}

	public function first(): ?ImageIds\ImageId
	{
		return parent::first();
	}

	public function last(): ?ImageIds\ImageId
	{
		return parent::last();
	}

	public function add(ImageIds\ImageId $item): void
	{
		$key = $item->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $item;
	}
}