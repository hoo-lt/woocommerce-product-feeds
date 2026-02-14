WITH RECURSIVE term_taxonomy AS (
	SELECT
		wp_term_taxonomy.term_taxonomy_id,
		wp_term_taxonomy.term_id

	FROM wp_term_taxonomy

	JOIN wp_termmeta
		ON wp_termmeta.term_id = wp_term_taxonomy.term_id

	WHERE wp_termmeta.meta_key = 'product_feeds'
		AND wp_termmeta.meta_value = 'excluded'

	UNION ALL

	SELECT
		wp_term_taxonomy.term_taxonomy_id,
		wp_term_taxonomy.term_id

	FROM wp_term_taxonomy

	JOIN term_taxonomy
		ON term_taxonomy.term_id = wp_term_taxonomy.parent
),

term_relationships AS (
	SELECT DISTINCT
		wp_term_relationships.object_id

	FROM wp_term_relationships

	WHERE wp_term_relationships.term_taxonomy_id IN (
		SELECT
			term_taxonomy_id

		FROM term_taxonomy
	)
),






posts AS (
	SELECT
		wp_posts.ID,
		wp_posts.post_title

	FROM wp_posts

	JOIN wp_term_relationships
		ON wp_term_relationships.object_id = wp_posts.ID
	JOIN wp_term_taxonomy
		ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
		AND wp_term_taxonomy.taxonomy = 'product_type'
	JOIN wp_terms
		ON wp_terms.term_id = wp_term_taxonomy.term_id
		AND wp_terms.slug = 'simple'

	LEFT JOIN term_relationships
		ON term_relationships.object_id = wp_posts.ID

	WHERE wp_posts.post_type = 'product'
		AND wp_posts.post_status = 'publish'
		AND term_relationships.object_id IS NULL
),

woocommerce_attribute_taxonomies AS (
  SELECT
    CONCAT('pa_', attribute_name) AS attribute_name,
    attribute_label

  FROM wp_woocommerce_attribute_taxonomies
),

terms AS (
	SELECT
		wp_term_relationships.object_id,
		wp_term_taxonomy.taxonomy,
		wp_terms.name

	FROM posts

	STRAIGHT_JOIN wp_term_relationships
		ON wp_term_relationships.object_id = posts.ID
	STRAIGHT_JOIN wp_term_taxonomy
		ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
	STRAIGHT_JOIN wp_terms
		ON wp_terms.term_id = wp_term_taxonomy.term_id

	WHERE wp_term_taxonomy.taxonomy IN (
		'product_brand',
		'product_cat'
	)
),

attribute AS (
	SELECT
		wp_term_relationships.object_id,
		wp_term_taxonomy.taxonomy,
		wp_terms.name,
		woocommerce_attribute_taxonomies.attribute_label

	FROM posts

	STRAIGHT_JOIN wp_term_relationships
		ON wp_term_relationships.object_id = posts.ID
	STRAIGHT_JOIN wp_term_taxonomy
		ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
	STRAIGHT_JOIN wp_terms
		ON wp_terms.term_id = wp_term_taxonomy.term_id
	STRAIGHT_JOIN woocommerce_attribute_taxonomies
		ON woocommerce_attribute_taxonomies.attribute_name = wp_term_taxonomy.taxonomy
)

SELECT
	posts.ID AS id,
	posts.post_title AS name,
	price.meta_value AS price,
	stock.meta_value AS stock,
	ean.meta_value AS ean,
	brand.name AS brand_name,
	category.name AS category_name,
	attribute.attribute_label AS attribute_name,
	attribute.name AS term_name

FROM posts

JOIN wp_postmeta AS price
	ON price.post_id = posts.ID
	AND price.meta_key = '_price'

LEFT JOIN wp_postmeta AS stock
	ON stock.post_id = posts.ID
	AND stock.meta_key = '_stock'
LEFT JOIN wp_postmeta AS ean
	ON ean.post_id = posts.ID
	AND ean.meta_key = '_global_unique_id'
LEFT JOIN terms AS brand
	ON brand.object_id = posts.ID
	AND brand.taxonomy = 'product_brand'
LEFT JOIN terms AS category
	ON category.object_id = posts.ID
	AND category.taxonomy = 'product_cat'
LEFT JOIN attribute
	ON attribute.object_id = posts.ID



















-- работающий код ниже

WITH RECURSIVE term_taxonomy_hierarchy AS (
    -- 1. Сборка путей + фильтрация exclude в ОДИН проход
    SELECT
        tt.term_taxonomy_id,
        tt.term_id,
        tt.parent,
        tt.taxonomy,
        CAST(t.slug AS CHAR(500)) as full_path
    FROM wp_term_taxonomy tt
    JOIN wp_terms t ON tt.term_id = t.term_id
    LEFT JOIN wp_termmeta tm ON t.term_id = tm.term_id
        AND tm.meta_key = 'product_feeds'
        AND tm.meta_value = 'excluded'
    WHERE tt.parent = 0 AND tm.meta_id IS NULL

    UNION ALL

    SELECT
        tt.term_taxonomy_id,
        tt.term_id,
        tt.parent,
        tt.taxonomy,
        CONCAT(tth.full_path, '/', t.slug)
    FROM wp_term_taxonomy tt
    JOIN wp_terms t ON tt.term_id = t.term_id
    JOIN term_taxonomy_hierarchy tth ON tt.parent = tth.term_id
    LEFT JOIN wp_termmeta tm ON t.term_id = tm.term_id
        AND tm.meta_key = 'product_feeds'
        AND tm.meta_value = 'exclude'
    WHERE tm.meta_id IS NULL
),

-- Больше никакой рекурсии здесь. Просто берем тех, кто НЕ попал в чистую иерархию
excluded_terms AS (
	SELECT tt.term_taxonomy_id, tt.term_id
	FROM wp_term_taxonomy tt
    WHERE tt.term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM term_taxonomy_hierarchy)
),

term_relationships AS (
	SELECT DISTINCT object_id
	FROM wp_term_relationships
	WHERE term_taxonomy_id IN (SELECT term_taxonomy_id FROM excluded_terms)
),

posts AS (
	SELECT
		wp_posts.ID,
		wp_posts.post_title
	FROM wp_posts
	JOIN wp_term_relationships ON wp_term_relationships.object_id = wp_posts.ID
	JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
		AND wp_term_taxonomy.taxonomy = 'product_type'
	JOIN wp_terms ON wp_terms.term_id = wp_term_taxonomy.term_id
		AND wp_terms.slug = 'simple'
	LEFT JOIN term_relationships ON term_relationships.object_id = wp_posts.ID
	WHERE wp_posts.post_type = 'product'
		AND wp_posts.post_status = 'publish'
		AND term_relationships.object_id IS NULL
),

terms_with_paths AS (
	SELECT
		tr.object_id,
		tth.taxonomy,
		tth.full_path,
		t.name
	FROM wp_term_relationships tr
	JOIN term_taxonomy_hierarchy tth ON tr.term_taxonomy_id = tth.term_taxonomy_id
    JOIN wp_terms t ON tth.term_id = t.term_id
	WHERE tth.taxonomy IN ('product_brand', 'product_cat')
),

woocommerce_attribute_taxonomies AS (
  SELECT CONCAT('pa_', attribute_name) AS attribute_name, attribute_label
  FROM wp_woocommerce_attribute_taxonomies
),

attribute AS (
	SELECT
		wp_term_relationships.object_id,
		wp_term_taxonomy.taxonomy,
		wp_terms.name,
		wat.attribute_label
	FROM posts
	STRAIGHT_JOIN wp_term_relationships ON wp_term_relationships.object_id = posts.ID
	STRAIGHT_JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
	STRAIGHT_JOIN wp_terms ON wp_terms.term_id = wp_term_taxonomy.term_id
	STRAIGHT_JOIN woocommerce_attribute_taxonomies wat ON wat.attribute_name = wp_term_taxonomy.taxonomy
)

SELECT
	p.ID AS id,
	p.post_title AS name,
	price.meta_value AS price,
	stock.meta_value AS stock,
	ean.meta_value AS ean,
	brand.name AS brand_name,
	brand.full_path AS brand_url_path,
	category.name AS category_name,
	category.full_path AS category_url_path,
	attr.attribute_label AS attribute_name,
	attr.name AS term_name
FROM posts p
JOIN wp_postmeta AS price ON price.post_id = p.ID AND price.meta_key = '_price'
LEFT JOIN wp_postmeta AS stock ON stock.post_id = p.ID AND stock.meta_key = '_stock'
LEFT JOIN wp_postmeta AS ean ON ean.post_id = p.ID AND ean.meta_key = '_global_unique_id'
LEFT JOIN terms_with_paths AS brand ON brand.object_id = p.ID AND brand.taxonomy = 'product_brand'
LEFT JOIN terms_with_paths AS category ON category.object_id = p.ID AND category.taxonomy = 'product_cat'
LEFT JOIN attribute AS attr ON attr.object_id = p.ID;