<?php

namespace Hoo\ProductFeeds\Infrastructure\Repositories\Product;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repositories\Product\RepositoryInterface
{
	public function __construct(
		protected readonly Infrastructure\Database\DatabaseInterface $database,
		protected readonly Infrastructure\Database\Queries\Select\TermRelationship\Query $selectTermRelationshipQuery,
		protected readonly Infrastructure\Database\Queries\Select\Product\Simple\Query $selectSimpleProductQuery,
		protected readonly Infrastructure\Mappers\TermRelationship\Mapper $termRelationshipMapper,
		protected readonly Infrastructure\Mappers\Product\Mapper $productMapper,
	) {
	}

	public function all(): Domain\Products
	{
		$termRelationshipObjectIds = $this->termRelationshipMapper->objectIds($this->database->select(
			$this->selectTermRelationshipQuery
		));

		return $this->productMapper->all([
			...$this->database->select(
				$this->selectSimpleProductQuery
					->postIds(...$termRelationshipObjectIds)
			),
		]);
	}
}