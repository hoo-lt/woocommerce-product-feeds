<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\Brand;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Brands
	{
		$brands = new Domain\Brands();

		foreach ($table as [
			'id' => $id,
			'name' => $name,
			'url' => $url,
		]) {
			$id = new Domain\Brands\Brand\Id(
				$id
			);

			if ($brands->has($id)) {
				continue;
			}

			$brands->add(new Domain\Brands\Brand(
				$id,
				$name,
				$url,
			));
		}

		return $brands;
	}
}