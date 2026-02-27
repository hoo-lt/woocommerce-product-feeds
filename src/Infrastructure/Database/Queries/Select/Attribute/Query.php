<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Queries\Select\Attribute;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Query implements Infrastructure\Database\Queries\Select\QueryInterface
{
	protected readonly string $query;

	public function __construct(
		protected readonly wpdb $wpdb,
		protected readonly string $path = __DIR__,
	) {
		$this->initializeQuery();
	}

	public function __invoke(): string
	{
		return $this->wpdb->prepare($this->query, [
			Domain\TermMeta::KEY,
			Domain\TermMeta::Excluded->value,
		]);
	}

	protected function initializeQuery(): void
	{
		$path = "{$this->path}/Query.sql";
		if (!file_exists($path)) {
			//throw exception
		}

		$this->query = strtr(file_get_contents($path), [
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':termmeta' => $this->wpdb->termmeta,
			':term_relationships' => $this->wpdb->term_relationships,
		]);
	}
}