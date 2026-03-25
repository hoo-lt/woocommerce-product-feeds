<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Attributes\Attribute;

use Hoo\WordPressPluginFramework\Collection;

readonly class Slug implements Collection\Item\Key\KeyInterface
{
	public function __construct(
		protected string $slug,
	) {
	}

	public function __invoke(): string
	{
		return $this->slug;
	}
}