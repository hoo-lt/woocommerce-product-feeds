<?php

namespace Hoo\ProductFeeds\Domain;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

abstract class AbstractCollection implements IteratorAggregate
{
	protected array $items = [];

	public function has(int $id): bool
	{
		return isset($this->items[$id]);
	}

	public function get(int $id): mixed
	{
		if (!$this->has($id)) {
			//throw domain exception
		}

		return $this->items[$id];
	}

	public function first(): mixed
	{
		if (!$this->items) {
			return null;
		}

		$firstKey = array_key_first($this->items);
		return $this->items[$firstKey];
	}

	public function last(): mixed
	{
		if (!$this->items) {
			return null;
		}

		$lastKey = array_key_last($this->items);
		return $this->items[$lastKey];
	}

	public function remove(int $id): void
	{
		if (!$this->has($id)) {
			return;  //throw domain exception
		}

		unset($this->items[$id]);
	}

	public function all(): array
	{
		return array_values($this->items);
	}

	public function getIterator(): Traversable
	{
		return new ArrayIterator(array_values($this->items));
	}
}