<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\TermMeta;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Mapper implements Application\Mappers\TermMeta\MapperInterface
{
	public function label(Domain\TermMeta $termMeta): array
	{
		return match ($termMeta) {
			Domain\TermMeta::Included => [
				'value' => Domain\TermMeta::Included->value,
				'label' => __('Included', 'woocommerce-product-feeds'),
			],
			Domain\TermMeta::Excluded => [
				'value' => Domain\TermMeta::Excluded->value,
				'label' => __('Excluded', 'woocommerce-product-feeds')
			],
		};
	}

	public function labels(): array
	{
		foreach (Domain\TermMeta::cases() as $termMeta) {
			$labels[] = $this->label($termMeta);
		}
		return $labels;
	}

	public function icon(Domain\TermMeta $termMeta): array
	{
		return match ($termMeta) {
			Domain\TermMeta::Included => [
				'value' => Domain\TermMeta::Included->value,
				'icon' => 'status-completed',
			],
			Domain\TermMeta::Excluded => [
				'value' => Domain\TermMeta::Excluded->value,
				'icon' => 'status-cancelled,'
			],
		};
	}

	public function icons(): array
	{
		foreach (Domain\TermMeta::cases() as $termMeta) {
			$icons[] = $this->icon($termMeta);
		}
		return $icons;
	}
}