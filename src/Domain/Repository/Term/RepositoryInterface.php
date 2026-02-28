<?php

namespace Hoo\ProductFeeds\Domain\Repository\Term;

use Hoo\ProductFeeds\Domain;

interface RepositoryInterface
{
	public function all(): Domain\Terms;
}