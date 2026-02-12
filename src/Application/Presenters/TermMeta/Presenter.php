<?php

namespace Hoo\ProductFeeds\Application\Presenters\TermMeta;

use Hoo\ProductFeeds\Domain;

interface PresenterInterface
{
	public function label(Domain\TermMeta $termMeta): array;
	public function labels(): array;

	public function icon(Domain\TermMeta $termMeta): array;
	public function icons(): array;
}