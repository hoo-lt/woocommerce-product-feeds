WITH RECURSIVE cte AS (
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

		CONCAT(cte.url_path, '/', terms.slug) AS url_path

	FROM :term_taxonomy AS term_taxonomy

	JOIN :terms AS terms
		ON terms.term_id = term_taxonomy.term_id

	JOIN cte
		ON cte.term_id = term_taxonomy.parent
)

SELECT DISTINCT
	term_id AS id,
	term_taxonomy.parent AS parent_id,
	name,
	CONCAT(%s, '/', %s, '/', url_path, '/') AS url

FROM cte