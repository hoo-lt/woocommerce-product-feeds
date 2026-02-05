<?php

namespace Hoo\ProductFeeds\Application\Term;

use Hoo\ProductFeeds\Application\Term\{
	Enum,
};

interface RepositoryInterface
{
	public function get(int $id): Enum;
	public function set(int $id, Enum $enum): void;
}