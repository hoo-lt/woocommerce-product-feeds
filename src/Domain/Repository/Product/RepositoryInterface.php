<?php

namespace Hoo\ProductFeeds\Domain\Repository\Product;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Products;
}