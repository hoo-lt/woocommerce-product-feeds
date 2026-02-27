<?php

namespace Hoo\ProductFeeds\Domain\Repositories\Term;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Terms;
}