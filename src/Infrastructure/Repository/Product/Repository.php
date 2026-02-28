<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\Product;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repository\Product\RepositoryInterface
{
	public function __construct(
		protected readonly DatabaseInterface $database,
		protected readonly Infrastructure\Database\Query\Select\TermRelationship\Query $selectTermRelationshipQuery,
		protected readonly Infrastructure\Database\Query\Select\Product\Simple\Query $selectSimpleProductQuery,
		protected readonly Infrastructure\Database\Query\Select\Product\Variable\Query $selectVariableProductQuery,
		protected readonly Infrastructure\Mapper\TermRelationship\Mapper $termRelationshipMapper,
		protected readonly Infrastructure\Mapper\Product\Mapper $productMapper,
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
			...$this->database->select(
				$this->selectVariableProductQuery
					->postIds(...$termRelationshipObjectIds)
			),
		]);
	}
}