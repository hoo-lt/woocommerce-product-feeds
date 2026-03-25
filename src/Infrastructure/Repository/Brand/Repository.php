<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Repository\Brand;

use Hoo\WordPressPluginFramework\Database\DatabaseInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure;

readonly class Repository implements Domain\Repository\Brand\RepositoryInterface
{
	public function __construct(
		protected DatabaseInterface $database,
		protected Infrastructure\Database\Query\Select\Term\Query $selectTermQuery,
		protected Infrastructure\Mapper\Brand\Mapper $brandMapper,
	) {
	}

	public function all(): Domain\Brands
	{
		return $this->brandMapper->all($this->database->select($this->selectTermQuery));
	}
}