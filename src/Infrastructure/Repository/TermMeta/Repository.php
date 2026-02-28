<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\TermMeta;

use Hoo\ProductFeeds\Domain;

class Repository implements Domain\Repository\TermMeta\RepositoryInterface
{
	public function get(int $id): Domain\TermMeta
	{
		$value = get_term_meta($id, Domain\TermMeta::KEY, true);
		if (!$value) {
			return Domain\TermMeta::Included;
		}

		return Domain\TermMeta::tryFrom($value) ?? Domain\TermMeta::Included;
	}

	public function set(int $id, Domain\TermMeta $termMeta): void
	{
		update_term_meta($id, Domain\TermMeta::KEY, $termMeta->value);
	}
}