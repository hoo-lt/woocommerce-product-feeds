<?php

namespace Hoo\ProductFeeds\Domain;

class Terms extends AbstractCollection
{
	public function get(int $id): Terms\Term
	{
		return parent::get($id);
	}

	public function first(): ?Terms\Term
	{
		return parent::first();
	}

	public function last(): ?Terms\Term
	{
		return parent::last();
	}

	public function add(Terms\Term $term): void
	{
		if ($this->has($term->id)) {
			return; //throw domain exception
		}

		$this->items[$term->id] = $term;
	}
}