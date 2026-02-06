<?php

namespace Hoo\ProductFeeds\Domain;

enum Term: string
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

	public function icon(): string
	{
		return match ($this) {
			self::Included => 'e015',
			self::Excluded => 'e013',
		};
	}

	public static function labels(): array
	{
		foreach (self::cases() as $case) {
			$labels[$case->value] = $case->label();
		}
		return $labels;
	}

	public static function icons(): array
	{
		foreach (self::cases() as $case) {
			$icons[$case->value] = $case->icon();
		}
		return $icons;
	}
}