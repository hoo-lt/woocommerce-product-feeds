<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Repository\Product;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Products;
}