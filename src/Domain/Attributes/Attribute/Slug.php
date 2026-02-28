<?php

namespace Hoo\ProductFeeds\Domain\Attributes\Attribute;

use Hoo\WordPressPluginFramework\Collection;

readonly class Slug implements Collection\Item\Key\KeyInterface
{
	public function __construct(
		protected int $slug,
	) {
	}

	public function __invoke(): int|string
	{
		return $this->slug;
	}
}