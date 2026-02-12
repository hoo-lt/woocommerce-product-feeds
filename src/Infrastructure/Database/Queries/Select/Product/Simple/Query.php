<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Queries\Select\Product\Simple;

use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Query implements Infrastructure\Database\Queries\Select\QueryInterface
{
	protected readonly string $query;

	protected array $excludedTermTaxonomyIds = [];

	public function __construct(
		protected readonly wpdb $wpdb,
		protected readonly string $path = __DIR__,
	) {
		$this->initializeQuery();
	}

	public function exclude(int ...$termTaxonomyIds): self
	{
		$clone = clone $this;
		$clone->excludedTermTaxonomyIds = $termTaxonomyIds;

		return $clone;
	}

	public function __invoke(): string
	{
		return $this->wpdb->prepare(strtr($this->query, [
			':WHERE' => $this->excludedTermTaxonomyIds ? 'WHERE term_relationships.term_taxonomy_id IN (' . implode(',', array_map(fn() => '%d', $this->excludedTermTaxonomyIds)) . ')' : '',
		]), [
			...$this->excludedTermTaxonomyIds,
		]);
	}

	protected function initializeQuery(): void
	{
		$path = "{$this->path}/Query.sql";
		if (!file_exists($path)) {
			//throw exception
		}

		$this->query = strtr(file_get_contents($path), [
			':term_relationships' => $this->wpdb->term_relationships,
			':posts' => $this->wpdb->posts,
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':terms' => $this->wpdb->terms,
			':woocommerce_attribute_taxonomies' => $this->wpdb->prefix . 'woocommerce_attribute_taxonomies',
			':postmeta' => $this->wpdb->postmeta,
		]);
	}
}