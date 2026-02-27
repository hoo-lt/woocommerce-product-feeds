<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Queries\Select\Product\Simple;

use Hoo\ProductFeeds\Infrastructure;

use wpdb;

class Query implements Infrastructure\Database\Queries\Select\QueryInterface
{
	protected readonly string $query;

	protected array $postIds = [];

	public function __construct(
		protected readonly wpdb $wpdb,
		protected readonly string $path = __DIR__,
	) {
		$this->initialize();
	}

	public function postIds(int ...$postIds): self
	{
		$clone = clone $this;
		$clone->postIds = $postIds;

		return $clone;
	}

	public function __invoke(): string
	{
		return $this->wpdb->prepare(strtr($this->query, [
			':AND posts.ID IN ()' => $this->postIds ? 'AND posts.ID IN (' . implode(',', array_map(fn() => '%d', $this->postIds)) . ')' : '',
		]), [
			...$this->postIds,
		]);
	}

	protected function initialize(): void
	{
		$path = "{$this->path}/Query.sql";
		if (!file_exists($path)) {
			//throw exception
		}

		$this->query = strtr(file_get_contents($path), [
			':term_relationships' => $this->wpdb->term_relationships,
			':posts' => $this->wpdb->posts,
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':terms' => $this->wpdb->terms,
			':postmeta' => $this->wpdb->postmeta,
		]);
	}
}