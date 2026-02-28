<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Query\Select\TermRelationship;

use Hoo\ProductFeeds\Domain;
use Hoo\WordPressPluginFramework\Database\Query\Select\QueryInterface;

use wpdb;

class Query implements QueryInterface
{
	protected readonly string $query;

	public function __construct(
		protected readonly wpdb $wpdb,
		protected readonly string $path = __DIR__,
	) {
		$this->initialize();
	}

	public function __invoke(): string
	{
		return $this->wpdb->prepare($this->query, [
			Domain\TermMeta::KEY,
			Domain\TermMeta::Excluded->value,
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
			':termmeta' => $this->wpdb->termmeta,
			':term_relationships' => $this->wpdb->term_relationships,
		]);
	}
}