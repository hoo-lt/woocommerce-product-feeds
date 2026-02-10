<?php

namespace Hoo\ProductFeeds\Infrastructure\Database;

use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Database implements DatabaseInterface
{
	public function __construct(
		protected readonly wpdb $wpdb
	) {
	}

	public function select(Infrastructure\Database\Queries\Select\QueryInterface $query): array
	{
		return $this->wpdb->get_results($query(), ARRAY_A);
	}
}