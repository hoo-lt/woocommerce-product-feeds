<?php

namespace Hoo\ProductFeeds\Domain;

class Tags extends AbstractCollection
{
	public function get(int $id): Tags\Tag
	{
		return parent::get($id);
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
		if ($this->has($tag->id)) {
			return; //throw domain exception
		}

		$this->items[$tag->id] = $tag;
	}
}