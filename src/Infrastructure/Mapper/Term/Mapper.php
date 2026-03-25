<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Mapper\Term;

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

class Mapper
{
	public function all(array $table): Domain\Terms
	{
		$terms = new Domain\Terms();

		foreach ($table as [
			'id' => $id,
			'name' => $name,
		]) {
			$id = new Domain\Terms\Term\Id(
				$id
			);

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