<?php

namespace Hoo\ProductFeeds\Infrastructure\Database;

use wpdb;

class Database implements DatabaseInterface
{
	public function __construct(
		protected readonly wpdb $wpdb
	) {
	}

	public function select(Queries\Select\QueryInterface $query): ?array
	{
		print_r($this->wpdb->get_results($query(), ARRAY_A));

		return $this->wpdb->get_results($query(), ARRAY_A);
	}
}