<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Repository\Attribute;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Attributes;
}