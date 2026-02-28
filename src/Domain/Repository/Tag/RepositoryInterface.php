<?php

namespace Hoo\ProductFeeds\Domain\Repository\Tag;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Tags;
}