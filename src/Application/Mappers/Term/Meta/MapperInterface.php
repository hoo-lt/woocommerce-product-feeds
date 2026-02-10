<?php

namespace Hoo\ProductFeeds\Application\Mappers\Term\Meta;

use Hoo\ProductFeeds\Domain;

interface MapperInterface
{
	public function label(Domain\Term\Meta $meta): array;
	public function labels(): array;

	public function icon(Domain\Term\Meta $meta): array;
	public function icons(): array;
}