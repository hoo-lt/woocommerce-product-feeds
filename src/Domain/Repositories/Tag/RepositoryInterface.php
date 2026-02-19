<?php

namespace Hoo\ProductFeeds\Domain\Repositories\Tag;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Tags;
}