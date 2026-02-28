<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\Attribute;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

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