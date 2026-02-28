<?php

namespace Hoo\ProductFeeds\Infrastructure\Repository\Term;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

class Repository implements Domain\Repository\Term\RepositoryInterface
{
	public function __construct(
		protected readonly DatabaseInterface $database,
		protected readonly Infrastructure\Database\Query\Select\Term\Query $selectTermQuery,
		protected readonly Infrastructure\Mapper\Term\Mapper $termMapper,
	) {
	}

	public function all(): Domain\Terms
	{
		return $this->termMapper->all($this->database->select($this->selectTermQuery));
	}
}