<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Mapper\Attribute;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Attributes
	{
		$attributes = new Domain\Attributes();

		foreach ($table as [
			'attribute_name' => $attributeName,
			'attribute_slug' => $attributeSlug,
			'term_name' => $termName,
			'term_slug' => $termSlug,
		]) {
			$attributeSlug = new Domain\Attributes\Attribute\Slug(
				$attributeSlug,
			);

			if ($attributes->has($attributeSlug)) {
				$attribute = $attributes->get($attributeSlug);
			} else {
				$attribute = new Domain\Attributes\Attribute(
					$attributeSlug,
					$attributeName,
				);

				$attributes->add($attribute);
			}

			$termSlug = new Domain\Attributes\Attribute\Terms\Term\Slug(
				$termSlug,
			);

			if ($attribute->terms->has($attributeSlug)) {
				$term = $attribute->terms->get($attributeSlug);
			} else {
				$term = new Domain\Attributes\Attribute\Terms\Term(
					$termSlug,
					$termName,
				);

				$attribute->terms->add($term);
			}
		}

		return $attributes;
	}
}