<?php

namespace Hoo\ProductFeeds\Infrastructure\Services\Product;

use Hoo\ProductFeeds\Infrastructure;

class Service
{
	public function __construct(
		protected readonly Infrastructure\Database\Database $database,
		protected readonly Infrastructure\Database\Queries\Select\TermTaxonomies\Excluded\Query $selectExcludedTermTaxonomiesQuery,
		protected readonly Infrastructure\Database\Queries\Select\Products\Simple\Query $selectSimpleProductsQuery,
	) {
	}

	public function __invoke()
	{
		$excludedTermTaxonomies = $this->database->select(
			$this->selectExcludedTermTaxonomiesQuery
		);

		$excludedTermTaxonomies = array_map(fn($excludedTermTaxonomy) => (int) $excludedTermTaxonomy['term_taxonomy_id'], $excludedTermTaxonomies);

		$simpleProducts = $this->database->select(
			$this->selectSimpleProductsQuery
				->excludeTermTaxonomyIds(...$excludedTermTaxonomies)
		);

		return $simpleProducts;
	}
}