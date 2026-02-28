<?php

namespace Hoo\ProductFeeds\Domain\Repository\Attribute;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Attributes;
}