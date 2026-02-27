<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\Attribute;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Attributes
	{
		$attributes = new Domain\Attributes();

		foreach ($table as [
			'name' => $name,
			'slug' => $slug,
		]) {
			if (!$attributes->has($slug)) {
				$attributes->add(new Domain\Attributes\Attribute(
					$name,
					$slug,
				));
			}
		}

		return $attributes;
	}
}