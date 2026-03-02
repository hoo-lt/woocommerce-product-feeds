WITH cte_posts AS (
	SELECT
		posts.ID AS id,
		posts.post_title AS name,
		posts.post_name AS slug

	FROM :posts AS posts

	JOIN :term_relationships AS term_relationships
		ON term_relationships.object_id = posts.ID
	JOIN :term_taxonomy AS term_taxonomy
		ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
		AND term_taxonomy.taxonomy = 'product_type'
	JOIN :terms AS terms
		ON terms.term_id = term_taxonomy.term_id
		AND terms.slug = 'simple'

	WHERE posts.post_type = 'product'
		AND posts.post_status = 'publish'
		:AND posts.ID IN ()
),

cte_term_taxonomy AS (
	SELECT
		cte_posts.id AS post_id,
		term_taxonomy.term_taxonomy_id,
		term_taxonomy.term_id,
		term_taxonomy.taxonomy

	FROM cte_posts

	STRAIGHT_JOIN :term_relationships AS term_relationships
		ON term_relationships.object_id = cte_posts.id
	STRAIGHT_JOIN :term_taxonomy AS term_taxonomy
		ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
)

SELECT
	cte_posts.id,
	cte_posts.name,
	CONCAT(%s, '/', %s, '/', cte_posts.slug, '/') AS url,
	price.meta_value AS price,
	stock.meta_value AS stock,
	gtin.meta_value AS gtin,

	brand.term_taxonomy_id AS brand_id,
	category.term_taxonomy_id AS category_id,

	attribute.taxonomy AS attribute_taxonomy,
	attribute.term_id AS term_id

FROM cte_posts

JOIN :postmeta AS price
	ON price.post_id = cte_posts.id
	AND price.meta_key = '_price'

LEFT JOIN :postmeta AS stock
	ON stock.post_id = cte_posts.id
	AND stock.meta_key = '_stock'
LEFT JOIN :postmeta AS gtin
	ON gtin.post_id = cte_posts.id
	AND gtin.meta_key = '_global_unique_id'

LEFT JOIN cte_term_taxonomy AS brand
	ON brand.post_id = cte_posts.id
	AND brand.taxonomy = 'product_brand'
LEFT JOIN cte_term_taxonomy AS category
	ON category.post_id = cte_posts.id
	AND category.taxonomy = 'product_cat'

LEFT JOIN cte_term_taxonomy AS attribute
	ON attribute.post_id = cte_posts.id
	AND attribute.taxonomy NOT IN (
		'product_brand',
		'product_cat',
		'product_tag'
	)