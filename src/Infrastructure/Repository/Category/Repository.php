<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Repository\Category;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\Category\RepositoryInterface
{
	public function __construct(
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\Term\Query $selectTermQuery,
		protected Infrastructure\Mapper\Category\Mapper $categoryMapper,
	) {
	}

	public function all(): Domain\Categories
	{
		return $this->categoryMapper->all($this->database->select($this->selectTermQuery));
	}
}