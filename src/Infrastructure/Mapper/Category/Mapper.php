<?php

namespace Hoo\ProductFeeds\Infrastructure\Mapper\Category;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Categories
	{
		$categories = new Domain\Categories();

		foreach ($table as [
			'id' => $id,
			'parent_id' => $parentId,
			'name' => $name,
			'url' => $url,
		]) {
			$id = new Domain\Categories\Category\Id(
				$id
			);

			if ($categories->has($id)) {
				continue;
			}

			$parentId = $parentId ? new Domain\Categories\Category\Id(
				$parentId
			) : null;

			$categories->add(new Domain\Categories\Category(
				$id,
				$parentId,
				$name,
				$url,
			));
		}

		return $categories;
	}
}