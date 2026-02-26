<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\TermRelationships;

class Mapper
{
	public function __invoke(array $table): array
	{
		return array_map(fn($row) => (int) $row['object_id'], $table);
	}
}