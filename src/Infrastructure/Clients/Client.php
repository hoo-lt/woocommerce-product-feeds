<?php

namespace Hoo\ProductFeeds\Infrastructure\Clients;

use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Client implements ClientInterface
{
	public function __construct(
		protected readonly wpdb $wpdb
	) {
	}

	public function select(Infrastructure\Queries\QueryInterface $query): array
	{
		return $this->wpdb->get_results($query(), ARRAY_A);
	}
}