<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\Product;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Products
	{
		$products = new Domain\Products();

		foreach ($table as $row) {
			if ($products->has((int) $row['id'])) {
				$product = $products->get((int) $row['id']);
			} else {
				$product = new Domain\Products\Product(
					(int) $row['id'],
					$row['name'],
					$row['slug'],
					(float) $row['price'],
					(int) $row['stock'],
					$row['gtin']
				);
				$products->add($product);
			}

			if (isset($row['attribute_taxonomy'])) {
				$slug = strtr($row['attribute_taxonomy'], [
					'pa_' => '',
				]);

				if ($product->attributes->has($slug)) {
					$attribute = $product->attributes->get($slug);
				} else {
					$attribute = new Domain\Products\Product\Attributes\Attribute(
						$slug,
					);
					$product->attributes->add($attribute);
				}

				if (isset($row['term_id'])) {
					if (!$attribute->terms->has($row['term_id'])) {
						$attribute->terms->add(new Domain\Products\Product\Attributes\Attribute\Terms\Term(
							(int) $row['term_id'],
						));
					}
				}
			}

			if (isset($row['brand_id'])) {
				if (!$product->brands->has((int) $row['brand_id'])) {
					$product->brands->add(new Domain\Products\Product\Brands\Brand(
						(int) $row['brand_id'],
					));
				}
			}

			if (isset($row['category_id'])) {
				if (!$product->categories->has((int) $row['category_id'])) {
					$product->categories->add(new Domain\Products\Product\Categories\Category(
						(int) $row['category_id'],
					));
				}
			}
		}

		return $products;
	}
}