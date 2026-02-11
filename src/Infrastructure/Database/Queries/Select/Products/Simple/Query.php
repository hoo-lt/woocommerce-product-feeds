<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Queries\Select\Products\Simple;

use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Query implements Infrastructure\Database\Queries\Select\QueryInterface
{
	protected array $excludedTermTaxonomyIds = [];

	public function __construct(
		protected readonly wpdb $wpdb,
	) {
	}

	public function excludeTermTaxonomyIds(int ...$termTaxonomyIds): self
	{
		$clone = clone $this;
		$clone->excludedTermTaxonomyIds = $termTaxonomyIds;

		return $clone;
	}

	public function __invoke(): string
	{
		$query = strtr(file_get_contents(__DIR__ . '/Query.sql'), [
			':term_relationships' => $this->wpdb->term_relationships,
			':posts' => $this->wpdb->posts,
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':terms' => $this->wpdb->terms,
			':woocommerce_attribute_taxonomies' => $this->wpdb->prefix . 'woocommerce_attribute_taxonomies',
			':postmeta' => $this->wpdb->postmeta,
			':WHERE term_relationships.term_taxonomy_id IN ()' => $this->excludedTermTaxonomyIds ? 'WHERE term_relationships.term_taxonomy_id IN (' . implode(',', array_map(fn() => '%d', $this->excludedTermTaxonomyIds)) . ')' : '',
		]);

		return $this->wpdb->prepare($query, [
			$this->excludedTermTaxonomyIds,
		]);
	}
}