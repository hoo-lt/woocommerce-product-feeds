<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\Term\Meta;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;

class Mapper implements Application\Mappers\Term\Meta\MapperInterface
{
	public function label(Domain\Term\Meta $meta): array
	{
		return match ($meta) {
			Domain\Term\Meta::Included => [
				'value' => Domain\Term\Meta::Included->value,
				'label' => __('Included', 'woocommerce-product-feeds'),
			],
			Domain\Term\Meta::Excluded => [
				'value' => Domain\Term\Meta::Excluded->value,
				'label' => __('Excluded', 'woocommerce-product-feeds')
			],
		};
	}

	public function labels(): array
	{
		foreach (Domain\Term\Meta::cases() as $meta) {
			$labels[] = $this->label($meta);
		}
		return $labels;
	}

	public function icon(Domain\Term\Meta $meta): array
	{
		return match ($meta) {
			Domain\Term\Meta::Included => [
				'value' => Domain\Term\Meta::Included->value,
				'icon' => 'status-completed',
			],
			Domain\Term\Meta::Excluded => [
				'value' => Domain\Term\Meta::Excluded->value,
				'icon' => 'status-cancelled,'
			],
		};
	}

	public function icons(): array
	{
		foreach (Domain\Term\Meta::cases() as $meta) {
			$icons[] = $this->icon($meta);
		}
		return $icons;
	}
}