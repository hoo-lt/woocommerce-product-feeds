<?php

namespace Hoo\ProductFeeds\Infrastructure\Database;

use Hoo\ProductFeeds\Infrastructure;

class Database implements DatabaseInterface
{
	public function __construct(
		protected readonly Infrastructure\Cache\Cache $cache,
		protected readonly Infrastructure\Database\Database $database,
	) {
	}

	public function select(Queries\Select\QueryInterface $query): ?array
	{
		$key = $this->key($query);

		$value = $this->cache->get($key);
		if ($value) {
			return $value;
		}

		$value = $this->database->select($query);
		if ($value) {
			$this->cache->set($key, $value);
		}

		return $value;
	}

	protected function key(Queries\Select\QueryInterface $query): string
	{
		return md5($query());
	}
}