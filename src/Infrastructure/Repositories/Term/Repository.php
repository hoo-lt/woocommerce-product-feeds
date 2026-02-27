<?php

namespace Hoo\ProductFeeds\Infrastructure\Repositories\Term;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repositories\Term\RepositoryInterface
{
	public function __construct(
		protected readonly Infrastructure\Database\DatabaseInterface $database,
		protected readonly Infrastructure\Database\Queries\Select\Term\Query $selectTermQuery,
		protected readonly Infrastructure\Mappers\Term\Mapper $termMapper,
	) {
	}

	public function all(): Domain\Terms
	{
		return $this->termMapper->all($this->database->select($this->selectTermQuery));
	}
}