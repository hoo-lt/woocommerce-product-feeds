<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Repository\Product;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\Product\RepositoryInterface
{
	public function __construct(
		protected Domain\Repository\TermRelationship\RepositoryInterface $termRelationshipRepository,
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\Product\Simple\Query $selectSimpleProductQuery,
		protected Infrastructure\Database\Query\Select\Product\Variation\Query $selectVariationProductQuery,
		protected Infrastructure\Mapper\Product\Mapper $productMapper,
	) {
	}

	public function all(): Domain\Products
	{
		$objectIds = $this->termRelationshipRepository->objectIds();

		return $this->productMapper->all([
			...$this->database->select(
				$this->selectSimpleProductQuery
					->withIds(...$objectIds)
					->withStatuses(Domain\Post\Status::Publish)

			),
			...$this->database->select(
				$this->selectVariationProductQuery
					->withIds(...$objectIds)
					->withStatuses(Domain\Post\Status::Publish)
					->withParentStatuses(Domain\Post\Status::Publish)
			),
		]);
	}
}