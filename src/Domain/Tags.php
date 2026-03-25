<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain;

use Hoo\WordPressPluginFramework\Collection;

class Tags extends Collection\AbstractCollection
{
	public function __construct(
		Tags\Tag ...$items,
	) {
		$this->items = $items;
	}

	public function get(Collection\Item\Key\KeyInterface $key): ?Tags\Tag
	{
		return parent::get($key);
	}

	public function first(): ?Tags\Tag
	{
		return parent::first();
	}

	public function last(): ?Tags\Tag
	{
		return parent::last();
	}

	public function add(Tags\Tag $item): void
	{
		$key = $item->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $item;
	}
}