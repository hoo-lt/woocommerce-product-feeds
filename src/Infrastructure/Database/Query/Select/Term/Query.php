<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Database\Query\Select\Term;

use Hoo\WordPressPluginFramework\Database\Query\Select\QueryInterface;
use Hoo\WordPressPluginFramework\Database\Query\QueryException;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use wpdb;

readonly class Query implements QueryInterface
{
	protected string $query;

	public function __construct(
		protected wpdb $wpdb,
		protected Domain\Taxonomy $taxonomy,
	) {
		$this->query = $this->query(
			$this->path(),
		);
	}

	public function __invoke(): string
	{
		return $this->wpdb->prepare($this->query, [
			$this->taxonomy->value,
		]);
	}

	protected function path(): string
	{
		$path = __DIR__ . '/Query.sql';
		if (!file_exists($path)) {
			throw new QueryException('.sql file not found');
		}

		return $path;
	}

	protected function query(string $path): string
	{
		return strtr(file_get_contents($path), [
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':terms' => $this->wpdb->terms,
		]);
	}
}