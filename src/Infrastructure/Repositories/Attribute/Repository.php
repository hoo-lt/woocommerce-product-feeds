<?php

namespace Hoo\ProductFeeds\Infrastructure\Repositories\Attribute;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repositories\Attribute\RepositoryInterface
{
	public function __construct(
		protected readonly Infrastructure\Database\DatabaseInterface $database,
		protected readonly Infrastructure\Database\Queries\Select\Attribute\Query $selectAttributeQuery,
		protected readonly Infrastructure\Mappers\Attribute\Mapper $attributeMapper,
	) {
	}

	public function all(): Domain\Attributes
	{
		return $this->attributeMapper->all($this->database->select($this->selectAttributeQuery));
	}
}