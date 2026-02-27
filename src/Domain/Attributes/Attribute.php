<?php

namespace Hoo\ProductFeeds\Domain\Attributes;

class Attribute
{
	public function __construct(
		public string $name,
		public string $slug,
	) {
	}
}