<?php

namespace Hoo\ProductFeeds\Infrastructure\Mappers\TermTaxonomy;

class Mapper
{
	public function ids(array $termTaxonomies): array
	{
		return array_map(fn($termTaxonomy) => (int) $termTaxonomy['term_taxonomy_id'], $termTaxonomies);
	}
}