<?php

namespace Hoo\ProductFeeds\Infrastructure\Queries\TermTaxonomy\Excluded;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Query implements Infrastructure\Queries\QueryInterface
{
	public function __construct(
		protected readonly wpdb $wpdb
	) {
	}

	public function __invoke(): string
	{
		$taxonomies = implode(', ', array_map(fn($taxonomy) => "'{$taxonomy->value}'", Domain\Taxonomy::cases()));
		$metaValue = Domain\Term::Exclude->value;

		return $this->wpdb->prepare("WITH RECURSIVE excluded_tree AS
			(
				SELECT
					term_taxonomy.term_taxonomy_id,
					term_taxonomy.term_id
				FROM {$this->wpdb->term_taxonomy} AS term_taxonomy
				INNER JOIN {$this->wpdb->termmeta} AS term_meta
					ON term_taxonomy.term_id = term_meta.term_id
				WHERE term_taxonomy.taxonomy IN ({$taxonomies})
					AND term_meta.meta_key = 'product_feeds'
					AND term_meta.meta_value = '{$metaValue}'

				UNION

				SELECT
					term_taxonomy.term_taxonomy_id,
					term_taxonomy.term_id
				FROM {$this->wpdb->term_taxonomy} AS term_taxonomy
				INNER JOIN excluded_tree
					ON term_taxonomy.parent = excluded_tree.term_id
				WHERE term_taxonomy.taxonomy IN ({$taxonomies})
			)
			SELECT
				term_taxonomy_id
			FROM excluded_tree");
	}
}