<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Repository\Brand;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Brands;
}