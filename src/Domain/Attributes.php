<?php

namespace Hoo\ProductFeeds\Domain;

use Hoo\WordPressPluginFramework\Collection;

class Attributes extends Collection\AbstractCollection
{
	public function __construct(
		Attributes\Attribute ...$items,
	) {
		$this->items = $items;
	}

	public function get(Collection\Item\Key\KeyInterface $key): ?Attributes\Attribute
	{
		return parent::get($key);
	}

	public function first(): ?Attributes\Attribute
	{
		return parent::first();
	}

	public function last(): ?Attributes\Attribute
	{
		return parent::last();
	}

	public function add(Attributes\Attribute $item): void
	{
		$key = $item->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $item;
	}
}