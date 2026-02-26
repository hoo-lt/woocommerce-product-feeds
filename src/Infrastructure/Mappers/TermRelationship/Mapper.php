<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\TermRelationship;

class Mapper
{
	public function objectIds(array $table): array
	{
		return array_map(fn($row) => (int) $row['object_id'], $table);
	}
}