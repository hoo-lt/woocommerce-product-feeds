<?php

namespace Hoo\ProductFeeds\Domain\Attributes\Attribute\Terms\Term;

use Hoo\WordPressPluginFramework\Collection;

readonly class Id implements Collection\Item\Key\KeyInterface
{
	public function __construct(
		protected int $id,
	) {
	}

	public function __invoke(): int
	{
		return $this->id;
	}
}