<?php

namespace Hoo\ProductFeeds\Domain\Repositories\Category;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Categories;
}