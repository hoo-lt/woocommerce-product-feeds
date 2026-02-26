<?php

namespace Hoo\ProductFeeds\Infrastructure\Repositories\Product;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repositories\Product\RepositoryInterface
{
	public function __construct(
		protected readonly Infrastructure\Database\DatabaseInterface $database,
		protected readonly Infrastructure\Database\Queries\Select\TermRelationships\Excluded\Query $selectExcludedTermRelationshipsQuery,
		protected readonly Infrastructure\Database\Queries\Select\Product\Simple\Query $selectSimpleProductQuery,
		protected readonly Infrastructure\Mappers\TermRelationships\Mapper $termRelationshipsMapper,
		protected readonly Infrastructure\Mappers\Product\Mapper $productMapper,
	) {
	}

	public function all(): Domain\Products
	{
		$excludedTermRelationshipsIds = ($this->termRelationshipsMapper)($this->database->select(
			$this->selectExcludedTermRelationshipsQuery
		));

		return $this->productMapper->all([
			...$this->database->select(
				$this->selectSimpleProductQuery
					->exclude(...$excludedTermRelationshipsIds)
			),
		]);
	}
}