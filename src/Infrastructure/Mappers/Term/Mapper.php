<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\Term;

use Hoo\ProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Terms
	{
		$terms = new Domain\Terms();

		foreach ($table as [
			'id' => $id,
			'name' => $name,
		]) {
			$id = (int) $id;

			if ($terms->has($id)) {
				continue;
			}

			$terms->add(new Domain\Terms\Term(
				$id,
				$name,
			));
		}

		return $terms;
	}
}