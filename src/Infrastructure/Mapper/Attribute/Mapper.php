<?php

namespace Hoo\ProductFeeds\Infrastructure\Mapper\Attribute;

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
			$slug = new Domain\Attributes\Attribute\Slug(
				$slug
			);

			if ($attributes->has($slug)) {
				continue;
			}

			$attributes->add(new Domain\Attributes\Attribute(
				$slug,
				$name,
			));
		}

		return $attributes;
	}
}