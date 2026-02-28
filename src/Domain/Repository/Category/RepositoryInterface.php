<?php

namespace Hoo\ProductFeeds\Domain\Repository\Category;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Categories;
}