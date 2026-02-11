<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Queries\Select\TermTaxonomies\Excluded;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Query implements Infrastructure\Database\Queries\Select\QueryInterface
{
	public function __construct(
		protected readonly wpdb $wpdb
	) {
	}

	public function __invoke(): string
	{
		$query = strtr(file_get_contents(__DIR__ . '/Query.sql'), [
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':termmeta' => $this->wpdb->termmeta,
		]);

		return $this->wpdb->prepare($query, [
			Domain\Term\Meta::KEY,
			Domain\Term\Meta::Excluded->value,
		]);
	}
}