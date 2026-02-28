<?php

namespace Hoo\ProductFeeds\Infrastructure\Mapper\Tag;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Tags
	{
		$tags = new Domain\Tags();

		foreach ($table as [
			'id' => $id,
			'parent_id' => $parentId,
			'name' => $name,
			'url' => $url,
		]) {
			$id = new Domain\Tags\Tag\Id(
				$id
			);

			if ($tags->has($id)) {
				continue;
			}

			$parentId = $parentId ? new Domain\Tags\Tag\Id(
				$parentId
			) : null;

			$tags->add(new Domain\Tags\Tag(
				$id,
				$parentId,
				$name,
				$url,
			));
		}

		return $tags;
	}
}