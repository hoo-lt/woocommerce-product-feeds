<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Repository\Tag;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Tags;
}