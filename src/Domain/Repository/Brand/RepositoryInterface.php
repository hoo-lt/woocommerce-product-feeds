<?php

namespace Hoo\ProductFeeds\Domain\Repository\Brand;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Brands;
}