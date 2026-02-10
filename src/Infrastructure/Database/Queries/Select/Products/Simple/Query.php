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

	public function excludeTermTaxonomies(int ...$ids): self
	{
		$clone = clone $this;
		$clone->excludedTermTaxonomyIds = $ids;

		return $clone;
	}

	public function __invoke(): string
	{
		$where = $this->excludedTermTaxonomyIds ? 'WHERE term_relationships.term_taxonomy_id IN (' . implode(',', array_map(fn() => '%d', $this->excludedTermTaxonomyIds)) . ')' : '';

		$query = <<<SQL
			WITH cte_term_relationships AS (
				SELECT DISTINCT
					term_relationships.object_id

				FROM {$this->wpdb->term_relationships} AS term_relationships

				{$where}
			),

			cte_posts AS (
				SELECT
					posts.ID,
					posts.post_title

				FROM {$this->wpdb->posts} AS posts

				JOIN {$this->wpdb->term_relationships} AS term_relationships
					ON term_relationships.object_id = posts.ID
				JOIN {$this->wpdb->term_taxonomy} AS term_taxonomy
					ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
					AND term_taxonomy.taxonomy = 'product_type'
				JOIN {$this->wpdb->terms} AS terms
					ON terms.term_id = term_taxonomy.term_id
					AND terms.slug = 'simple'

				LEFT JOIN cte_term_relationships
					ON cte_term_relationships.object_id = posts.ID

				WHERE posts.post_type = 'product'
					AND posts.post_status = 'publish'
					AND cte_term_relationships.object_id IS NULL
			),

			cte_woocommerce_attribute_taxonomies AS (
				SELECT
					CONCAT('pa_', woocommerce_attribute_taxonomies.attribute_name) AS attribute_name,
					woocommerce_attribute_taxonomies.attribute_label

				FROM {$this->wpdb->prefix}woocommerce_attribute_taxonomies AS woocommerce_attribute_taxonomies
			),

			cte_terms AS (
				SELECT
					term_relationships.object_id,
					term_taxonomy.taxonomy,
					terms.name

				FROM cte_posts AS posts

				STRAIGHT_JOIN {$this->wpdb->term_relationships} AS term_relationships
					ON term_relationships.object_id = posts.ID
				STRAIGHT_JOIN {$this->wpdb->term_taxonomy} AS term_taxonomy
					ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
				STRAIGHT_JOIN {$this->wpdb->terms} AS terms
					ON terms.term_id = term_taxonomy.term_id

				WHERE term_taxonomy.taxonomy IN (
						'product_brand',
						'product_cat'
					)
			),

			cte_attribute AS (
				SELECT
					term_relationships.object_id,
					term_taxonomy.taxonomy,
					terms.name,
					woocommerce_attribute_taxonomies.attribute_label

				FROM cte_posts AS posts

				STRAIGHT_JOIN {$this->wpdb->term_relationships} AS term_relationships
					ON term_relationships.object_id = posts.ID
				STRAIGHT_JOIN {$this->wpdb->term_taxonomy} AS term_taxonomy
					ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
				STRAIGHT_JOIN {$this->wpdb->terms} AS terms
					ON terms.term_id = term_taxonomy.term_id
				STRAIGHT_JOIN cte_woocommerce_attribute_taxonomies AS woocommerce_attribute_taxonomies
					ON woocommerce_attribute_taxonomies.attribute_name = term_taxonomy.taxonomy
			)

			SELECT
				posts.ID AS id,
				posts.post_title AS name,
				price.meta_value AS price,
				stock.meta_value AS stock,
				ean.meta_value AS ean,
				brand.name AS brand_name,
				category.name AS category_name,
				attribute.attribute_label AS attribute_name,
				attribute.name AS term_name

			FROM cte_posts AS posts

			JOIN {$this->wpdb->postmeta} AS price
				ON price.post_id = posts.ID
				AND price.meta_key = '_price'

			LEFT JOIN {$this->wpdb->postmeta} AS stock
				ON stock.post_id = posts.ID
				AND stock.meta_key = '_stock'
			LEFT JOIN {$this->wpdb->postmeta} AS ean
				ON ean.post_id = posts.ID
				AND ean.meta_key = '_global_unique_id'
			LEFT JOIN cte_terms AS brand
				ON brand.object_id = posts.ID
				AND brand.taxonomy = 'product_brand'
			LEFT JOIN cte_terms AS category
				ON category.object_id = posts.ID
				AND category.taxonomy = 'product_cat'
			LEFT JOIN cte_attribute AS attribute
				ON attribute.object_id = posts.ID
		SQL;

		return $this->wpdb->prepare(
			$query,
			$this->excludedTermTaxonomyIds,
		);
	}
}