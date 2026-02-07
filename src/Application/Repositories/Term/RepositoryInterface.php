<?php

namespace Hoo\ProductFeeds\Application\Repositories\Term;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function get(int $id): Domain\Term;
	public function set(int $id, Domain\Term $term): void;
}