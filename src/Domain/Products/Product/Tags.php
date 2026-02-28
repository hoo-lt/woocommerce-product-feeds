<?php

namespace Hoo\ProductFeeds\Domain\Products\Product;

use Hoo\WordPressPluginFramework\Collection;

class Tags extends Collection\AbstractCollection
{
	public function __construct(
		Tags\Tag ...$tags,
	) {
		$this->items = $tags;
	}

	public function get(Collection\Item\Key\KeyInterface $key): Tags\Tag
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

	public function add(Tags\Tag $tag): void
	{
		$key = $tag->key();
		if ($this->has($key)) {
			return;
		}

		$this->items[$key()] = $tag;
	}
}