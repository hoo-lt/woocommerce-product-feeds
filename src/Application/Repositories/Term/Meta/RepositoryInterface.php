<?php

namespace Hoo\ProductFeeds\Application\Repositories\Term\Meta;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function get(int $id): Domain\Term\Meta;
	public function set(int $id, Domain\Term\Meta $meta): void;
}