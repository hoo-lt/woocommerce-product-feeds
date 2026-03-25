<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Repository\Tag;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\Tag\RepositoryInterface
{
	public function __construct(
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\Term\Query $selectTermQuery,
		protected Infrastructure\Mapper\Tag\Mapper $tagMapper,
	) {
	}

	public function all(): Domain\Tags
	{
		return $this->tagMapper->all($this->database->select($this->selectTermQuery));
	}
}