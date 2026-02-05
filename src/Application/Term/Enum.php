<?php

namespace Hoo\ProductFeeds\Application\Term;

enum Enum: string
{
	case Included = 'included';
	case Excluded = 'excluded';

	public function label(): string
	{
		return match ($this) {
			self::Included => __('Included', 'woocommerce-plugin-product-feeds'),
			self::Excluded => __('Excluded', 'woocommerce-plugin-product-feeds'),
		};
	}

	public static function labels(): array
	{
		foreach (self::cases() as $case) {
			$labels[$case->value] = $case->label();
		}
		return $labels;
	}
}