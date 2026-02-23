<?php

namespace Hoo\ProductFeeds\Infrastructure\Database;

interface DatabaseInterface
{
	public function select(Queries\Select\QueryInterface $query): ?array;
}