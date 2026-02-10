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
		$query = <<<SQL
			WITH RECURSIVE cte_term_taxonomy AS (
				SELECT
					term_taxonomy.term_taxonomy_id,
					term_taxonomy.term_id

				FROM {$this->wpdb->term_taxonomy} AS term_taxonomy

				JOIN {$this->wpdb->termmeta} AS termmeta
					ON termmeta.term_id = term_taxonomy.term_id

				WHERE termmeta.meta_key = %s
					AND termmeta.meta_value = %s

				UNION ALL

				SELECT
					term_taxonomy.term_taxonomy_id,
					term_taxonomy.term_id

				FROM {$this->wpdb->term_taxonomy} AS term_taxonomy

				JOIN cte_term_taxonomy
					ON cte_term_taxonomy.term_id = term_taxonomy.parent
			)

			SELECT DISTINCT
				term_taxonomy.term_taxonomy_id

			FROM cte_term_taxonomy AS term_taxonomy
		SQL;

		return $this->wpdb->prepare(
			$query,
			Domain\Term\Meta::KEY,
			Domain\Term\Meta::Excluded->value,
		);
	}
}