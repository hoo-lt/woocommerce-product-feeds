<?php

namespace Hoo\ProductFeeds\Domain\Repositories\Attribute;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Attributes;
}