<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Repository\TermMeta;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

interface RepositoryInterface
{
	public function get(int $id): Domain\TermMeta;
	public function set(int $id, Domain\TermMeta $termMeta): void;
}