<?php

namespace Hoo\ProductFeeds\Infrastructure\Repositories\Term\Meta;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Repository implements Application\Repositories\Term\Meta\RepositoryInterface
{
	public function get(int $id): Domain\Term\Meta
	{
		$value = get_term_meta($id, Domain\Term\Meta::KEY, true);
		if (!$value) {
			return Domain\Term\Meta::Included;
		}

		return Domain\Term\Meta::tryFrom($value) ?? Domain\Term\Meta::Included;
	}

	public function set(int $id, Domain\Term\Meta $meta): void
	{
		update_term_meta($id, Domain\Term\Meta::KEY, $meta->value);
	}
}