<?php

namespace Hoo\ProductFeeds\Infrastructure\Presenters\TermMeta;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Presenter implements Application\Presenters\TermMeta\PresenterInterface
{
	public function label(Domain\TermMeta $termMeta): array
	{
		return match ($termMeta) {
			Domain\TermMeta::Included => [
				'value' => $termMeta->value,
				'label' => __('Included', 'woocommerce-product-feeds'),
			],
			Domain\TermMeta::Excluded => [
				'value' => $termMeta->value,
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
				'value' => $termMeta->value,
				'icon' => 'status-completed',
			],
			Domain\TermMeta::Excluded => [
				'value' => $termMeta->value,
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