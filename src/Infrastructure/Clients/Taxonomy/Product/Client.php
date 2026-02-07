<?php

namespace Hoo\ProductFeeds\Infrastructure\Products;

use wpdb;

class Client
{
	protected wpdb $wpdb;

	public function __construct()
	{
		global $wpdb;

		$this->wpdb = $wpdb;
	}


	public function getIds(int ...$taxonomyIds): array
	{
		return array_unique($this->wpdb->get_col($this->wpdb->prepare(
			"SELECT object_id FROM {$this->wpdb->term_relationships} WHERE term_taxonomy_id IN (" . implode(', ', array_fill(0, count($taxonomyIds), '%d')) . ")",
			...$taxonomyIds
		)));
	}

	public function get()
	{
		global $wpdb;

		$meta_key = 'product_feeds'; // Имя ключа в termmeta
		$meta_value = 'exclude';

		$query = "
WITH RECURSIVE excluded_tree AS (
    -- Базовый случай: берем категории, где напрямую стоит 'excluded'
    SELECT term_id
    FROM {$wpdb->termmeta}
    WHERE meta_key = %s AND meta_value = %s

    UNION ALL

    -- Рекурсия: берем всех детей тех категорий, что уже в списке
    SELECT tt.term_id
    FROM {$wpdb->term_taxonomy} tt
    INNER JOIN excluded_tree et ON tt.parent = et.term_id
)
SELECT DISTINCT p.ID, p.post_title
FROM {$wpdb->posts} p
INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
WHERE p.post_type = 'product'
  AND p.post_status = 'publish'
  -- Исключаем товары, чьи категории (любые) входят в дерево исключенных
  AND p.ID NOT IN (
      SELECT tr2.object_id
      FROM {$wpdb->term_relationships} tr2
      WHERE tr2.term_taxonomy_id IN (SELECT term_id FROM excluded_tree)
  );
";

		echo $wpdb->get_results($wpdb->prepare($query, $meta_key, $meta_value));
	}
}