<?php

namespace Hoo\ProductFeeds\Infrastructure\Term;

use Hoo\ProductFeeds\Application\Term\{
	Enum,
	RepositoryInterface,
};

class Repository implements RepositoryInterface
{
	protected const KEY = 'product_feeds';

	public function get(int $id): Enum
	{
		$value = get_term_meta($id, self::KEY, true);

		return Enum::tryFrom($value) ?? Enum::Included;
	}

	public function set(int $id, Enum $enum): void
	{
		update_term_meta($id, self::KEY, $enum->value);
	}
}