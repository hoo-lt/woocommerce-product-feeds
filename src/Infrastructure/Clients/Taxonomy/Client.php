<?php

namespace Hoo\ProductFeeds\Infrastructure\Clients\Taxonomy;

use Hoo\ProductFeeds\Domain;

use wpdb;

class Client
{
	protected const KEY = 'product_feeds';

	protected wpdb $wpdb;

	public function __construct()
	{
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	public function getExcludedIds(): array
	{
		return array_unique($this->wpdb->get_col($this->wpdb->prepare(
			"WITH RECURSIVE term_excluded AS (
				SELECT term_taxonomy.term_taxonomy_id, term_taxonomy.term_id, term_taxonomy.parent
				FROM {$this->wpdb->termmeta} termmeta
				INNER JOIN {$this->wpdb->term_taxonomy} term_taxonomy ON termmeta.term_id = term_taxonomy.term_id
				WHERE termmeta.meta_key = %s AND termmeta.meta_value = %s

				UNION ALL

				SELECT term_taxonomy.term_taxonomy_id, term_taxonomy.term_id, term_taxonomy.parent
				FROM {$this->wpdb->term_taxonomy} term_taxonomy
				INNER JOIN term_excluded ON term_taxonomy.parent = term_excluded.term_id
			)
			SELECT term_taxonomy_id FROM term_excluded",
			self::KEY,
			Domain\Term::Exclude->value
		)));
	}
}