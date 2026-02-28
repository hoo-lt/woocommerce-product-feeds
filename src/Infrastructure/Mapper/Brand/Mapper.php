<?php

namespace Hoo\ProductFeeds\Infrastructure\Mapper\Brand;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Brands
	{
		$brands = new Domain\Brands();

		foreach ($table as [
			'id' => $id,
			'parent_id' => $parentId,
			'name' => $name,
			'url' => $url,
		]) {
			$id = new Domain\Brands\Brand\Id(
				$id
			);

			if ($brands->has($id)) {
				continue;
			}

			$parentId = $parentId ? new Domain\Brands\Brand\Id(
				$parentId
			) : null;

			$brands->add(new Domain\Brands\Brand(
				$id,
				$parentId,
				$name,
				$url,
			));
		}

		return $brands;
	}
}