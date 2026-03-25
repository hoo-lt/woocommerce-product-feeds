<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Database\Query\Select\Product\Variation;

use Hoo\WordPressPluginFramework\Database\Query\Select\QueryInterface;
use Hoo\WordPressPluginFramework\Database\Query\QueryException;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use wpdb;

readonly class Query implements QueryInterface
{
	protected string $query;

	public function __construct(
		protected wpdb $wpdb,
		protected array $ids = [],
		protected array $statuses = [],
		protected array $parentIds = [],
		protected array $parentStatuses = [],
	) {
		$this->query = $this->query(
			$this->path(),
		);
	}

	public function withIds(int ...$ids): self
	{
		return new self(
			$this->wpdb,
			$ids,
			$this->statuses,
			$this->parentIds,
			$this->parentStatuses
		);
	}

	public function withStatuses(Domain\Post\Status ...$statuses): self
	{
		return new self(
			$this->wpdb,
			$this->ids,
			$statuses,
			$this->parentIds,
			$this->parentStatuses
		);
	}

	public function withParentIds(int ...$parentIds): self
	{
		return new self(
			$this->wpdb,
			$this->ids,
			$this->statuses,
			$parentIds,
			$this->parentStatuses
		);
	}

	public function withParentStatuses(Domain\Post\Status ...$parentStatuses): self
	{
		return new self(
			$this->wpdb,
			$this->ids,
			$this->statuses,
			$this->parentIds,
			$parentStatuses
		);
	}

	public function __invoke(): string
	{
		return $this->wpdb->prepare(strtr($this->query, [
			':AND posts.ID' => $this->ids ? 'AND posts.ID IN (' . implode(',', array_map(fn() => '%d', $this->ids)) . ')' : '',
			':AND posts.post_status' => $this->statuses ? 'AND posts.post_status IN (' . implode(',', array_map(fn() => '%s', $this->statuses)) . ')' : '',
			':AND parent_posts.ID' => $this->parentIds ? 'AND parent_posts.ID IN (' . implode(',', array_map(fn() => '%d', $this->parentIds)) . ')' : '',
			':AND parent_posts.post_status' => $this->parentStatuses ? 'AND parent_posts.post_status IN (' . implode(',', array_map(fn() => '%s', $this->parentStatuses)) . ')' : '',
		]), [
			...$this->ids,
			...array_map(fn($status) => $status->value, $this->statuses),
			...$this->parentIds,
			...array_map(fn($parentStatus) => $parentStatus->value, $this->parentStatuses),
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
			':term_relationships' => $this->wpdb->term_relationships,
			':posts' => $this->wpdb->posts,
			':term_taxonomy' => $this->wpdb->term_taxonomy,
			':terms' => $this->wpdb->terms,
			':postmeta' => $this->wpdb->postmeta,
		]);
	}
}