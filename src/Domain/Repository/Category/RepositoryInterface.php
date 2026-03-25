<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Repository\Category;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Categories;
}