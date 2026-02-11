<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Queries\Select\TermTaxonomy\Excluded;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Query implements Infrastructure\Database\Queries\Select\QueryInterface
{
	protected readonly string $query;

	public function __construct(
		protected readonly wpdb $wpdb,
		protected readonly string $filename = __DIR__ . '/Query.sql',
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
		if (!file_exists($this->filename)) {
			//throw exception
		}

		$this->query = strtr(file_get_contents($this->filename), [
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':termmeta' => $this->wpdb->termmeta,
		]);
	}
}