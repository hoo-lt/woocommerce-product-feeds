<?php

namespace Hoo\ProductFeeds\Domain\Terms;

class Term
{
	public function __construct(
		public int $id,
		public string $name,
	) {
	}
}