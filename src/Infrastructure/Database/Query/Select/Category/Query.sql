WITH RECURSIVE cte_term_taxonomy AS (
	SELECT
		term_taxonomy.term_id,
		term_taxonomy.parent,

		terms.name,

		terms.slug AS url_path

	FROM :term_taxonomy AS term_taxonomy

	JOIN :terms AS terms
		ON terms.term_id = term_taxonomy.term_id

	WHERE term_taxonomy.parent = 0
		AND term_taxonomy.taxonomy = 'product_cat'

	UNION ALL

	SELECT
		term_taxonomy.term_id,
		term_taxonomy.parent,

		terms.name,

		CONCAT(cte_term_taxonomy.url_path, '/', terms.slug) AS url_path

	FROM :term_taxonomy AS term_taxonomy

	JOIN :terms AS terms
		ON terms.term_id = term_taxonomy.term_id

	JOIN cte_term_taxonomy
		ON cte_term_taxonomy.term_id = term_taxonomy.parent
)

SELECT DISTINCT
	term_id AS id,
	parent AS parent_id,
	name,
	CONCAT(%s, '/', %s, '/', url_path, '/') AS url

FROM cte_term_taxonomy