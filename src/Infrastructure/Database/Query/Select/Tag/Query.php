<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Query\Select\Tag;

use Hoo\WordPressPluginFramework\Database\Query\Select\QueryInterface;

use wpdb;

class Query implements QueryInterface
{
	protected readonly string $query;

	public function __construct(
		protected readonly wpdb $wpdb,
		protected readonly string $homeUrl,
		protected readonly string $permalink,
		protected readonly string $path = __DIR__,
	) {
		$this->initialize();
	}

	public function __invoke(): string
	{
		return $this->wpdb->prepare($this->query, [
			$this->homeUrl,
			$this->permalink,
		]);
	}

	protected function initialize(): void
	{
		$path = "{$this->path}/Query.sql";
		if (!file_exists($path)) {
			//throw exception
		}

		$this->query = strtr(file_get_contents($path), [
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':terms' => $this->wpdb->terms,
		]);
	}
}