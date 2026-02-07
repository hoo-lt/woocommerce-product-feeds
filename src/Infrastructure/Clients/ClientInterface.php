<?php

namespace Hoo\ProductFeeds\Infrastructure\Clients;

use Hoo\ProductFeeds\Infrastructure;

interface ClientInterface
{
	public function select(Infrastructure\Queries\QueryInterface $query): array;
}