<?php

namespace Hoo\ProductFeeds\Application\Mappers\TermMeta;

use Hoo\ProductFeeds\Domain;

interface MapperInterface
{
	public function label(Domain\TermMeta $termMeta): array;
	public function labels(): array;

	public function icon(Domain\TermMeta $termMeta): array;
	public function icons(): array;
}