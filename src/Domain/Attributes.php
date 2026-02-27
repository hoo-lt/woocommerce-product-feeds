<?php

namespace Hoo\ProductFeeds\Domain;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Attributes implements IteratorAggregate
{
	protected array $attributes = [];

	public function has(string $slug): bool
	{
		return isset($this->attributes[$slug]);
	}

	public function get(string $slug): Attributes\Attribute
	{
		if (!isset($this->attributes[$slug])) {
			//throw domain exception
		}

		return $this->attributes[$slug];
	}

	public function first(): ?Attributes\Attribute
	{
		if (!$this->attributes) {
			return null;
		}

		$firstKey = array_key_first($this->attributes);
		return $this->attributes[$firstKey];
	}

	public function last(): ?Attributes\Attribute
	{
		if (!$this->attributes) {
			return null;
		}

		$lastKey = array_key_last($this->attributes);
		return $this->attributes[$lastKey];
	}

	public function add(Attributes\Attribute $attribute): void
	{
		if (isset($this->attributes[$attribute->slug])) {
			return; //throw domain exception
		}

		$this->attributes[$attribute->slug] = $attribute;
	}

	public function remove(string $slug): void
	{
		if (!isset($this->attributes[$slug])) {
			return; //throw domain exception
		}

		unset($this->attributes[$slug]);
	}

	public function all(): array
	{
		return array_values($this->attributes);
	}

	public function getIterator(): Traversable
	{
		return new ArrayIterator(array_values($this->attributes));
	}
}