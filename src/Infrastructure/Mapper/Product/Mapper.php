<?php

namespace Hoo\ProductFeeds\Infrastructure\Mapper\Product;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Products
	{
		$products = new Domain\Products();

		foreach ($table as [
			'id' => $id,
			'name' => $name,
			//'url' => $url,
			'price' => $price,
			'stock' => $stock,
			'gtin' => $gtin,
			'attribute_taxonomy' => $attributeTaxonomy,
			'term_id' => $termId,
			'brand_id' => $brandId,
			'category_id' => $categoryId,
			//'tag_id' => $tagId,
		]) {
			$id = new Domain\Products\Product\Id(
				$id,
			);

			if ($products->has($id)) {
				$product = $products->get($id);
			} else {
				$product = new Domain\Products\Product(
					$id,
					$name,
					'',
					$price,
					$stock,
					$gtin,
				);
				$products->add($product);
			}

			if ($attributeTaxonomy) {
				$attributeSlug = strtr($attributeTaxonomy, [
					'pa_' => '',
				]);

				$attributeSlug = new Domain\Products\Product\Attributes\Attribute\Slug(
					$attributeSlug,
				);

				if ($product->attributes->has($attributeSlug)) {
					$attribute = $product->attributes->get($attributeSlug);
				} else {
					$attribute = new Domain\Products\Product\Attributes\Attribute(
						$attributeSlug,
					);
					$product->attributes->add($attribute);
				}

				if ($termId) {
					$termId = new Domain\Products\Product\Attributes\Attribute\Terms\Term\Id(
						$termId,
					);

					if (!$attribute->terms->has($termId)) {
						$attribute->terms->add(new Domain\Products\Product\Attributes\Attribute\Terms\Term(
							$termId,
						));
					}
				}
			}

			if ($brandId) {
				$brandId = new Domain\Products\Product\Brands\Brand\Id(
					$brandId,
				);

				if (!$product->brands->has($brandId)) {
					$product->brands->add(new Domain\Products\Product\Brands\Brand(
						$brandId,
					));
				}
			}

			if ($categoryId) {
				$categoryId = new Domain\Products\Product\Categories\Category\Id(
					$categoryId,
				);

				if (!$product->categories->has($categoryId)) {
					$product->categories->add(new Domain\Products\Product\Categories\Category(
						$categoryId,
					));
				}
			}

			/*
			if ($tagId) {
				$tagId = new Domain\Products\Product\Tags\Tag\Id(
					$tagId,
				);

				if (!$product->tags->has($tagId)) {
					$product->tags->add(new Domain\Products\Product\Tags\Tag(
						$tagId,
					));
				}
			}
			*/
		}

		return $products;
	}
}