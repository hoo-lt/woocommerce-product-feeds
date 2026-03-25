<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Repository\Attribute;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure;

class Repository implements Domain\Repository\Attribute\RepositoryInterface
{
	public function __construct(
		protected readonly DatabaseInterface $database,
		protected readonly Infrastructure\Database\Query\Select\Attribute\Query $selectAttributeQuery,
		protected readonly Infrastructure\Mapper\Attribute\Mapper $attributeMapper,
	) {
	}

	public function all(): Domain\Attributes
	{
		return $this->attributeMapper->all($this->database->select($this->selectAttributeQuery));
	}
}