<?php

namespace Hoo\ProductFeeds\Domain\Repositories\Brand;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Brands;
}