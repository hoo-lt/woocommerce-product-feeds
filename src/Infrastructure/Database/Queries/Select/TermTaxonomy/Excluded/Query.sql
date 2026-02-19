WITH RECURSIVE cte AS (
	SELECT
		term_taxonomy.term_taxonomy_id

	FROM :term_taxonomy AS term_taxonomy

	JOIN :termmeta AS termmeta
		ON termmeta.term_id = term_taxonomy.term_id

	WHERE termmeta.meta_key = %s
		AND termmeta.meta_value = %s

	UNION ALL

	SELECT
		term_taxonomy.term_taxonomy_id

	FROM :term_taxonomy AS term_taxonomy

	JOIN cte
		ON cte.term_taxonomy_id = term_taxonomy.parent
)

SELECT DISTINCT
	term_taxonomy.term_taxonomy_id

FROM cte AS term_taxonomy