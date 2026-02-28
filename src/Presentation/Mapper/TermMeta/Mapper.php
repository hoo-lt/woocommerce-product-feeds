<?php

namespace Hoo\ProductFeeds\Presentation\Mapper\TermMeta;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function option(Domain\TermMeta $termMeta): array
	{
		return [
			'value' => $this->value($termMeta),
			'label' => $this->label($termMeta),
		];
	}

	public function options(): array
	{
		return array_map($this->option(...), Domain\TermMeta::cases());
	}

	public function value(Domain\TermMeta $termMeta): string
	{
		return $termMeta->value;
	}

	public function icon(Domain\TermMeta $termMeta): string
	{
		return match ($termMeta) {
			Domain\TermMeta::Included => 'status-completed',
			Domain\TermMeta::Excluded => 'status-cancelled',
		};
	}

	public function label(Domain\TermMeta $termMeta): string
	{
		return match ($termMeta) {
			Domain\TermMeta::Included => __('Included', 'woocommerce-product-feeds'),
			Domain\TermMeta::Excluded => __('Excluded', 'woocommerce-product-feeds'),
		};
	}
}