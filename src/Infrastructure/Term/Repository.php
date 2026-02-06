<?php

namespace Hoo\ProductFeeds\Infrastructure\Term;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Repository implements Application\Term\RepositoryInterface
{
	protected const KEY = 'product_feeds';

	public function get(int $id): Domain\Term
	{
		$value = get_term_meta($id, self::KEY, true);
		if (!$value) {
			return Domain\Term::Included;
		}

		return Domain\Term::from($value);
	}

	public function set(int $id, Domain\Term $term): void
	{
		update_term_meta($id, self::KEY, $term->value);
	}
}