WITH cte_posts AS (
    SELECT
        posts.ID AS id,
        posts.post_parent AS parent_id, -- Сохраняем ID родителя для джойнов таксономий
        posts.post_title AS name,
        posts.post_name AS slug
    FROM :posts AS posts
    -- Проверяем, что это вариация и что она опубликована
    WHERE posts.post_type = 'product_variation'
        AND posts.post_status = 'publish'
        :AND posts.post_parent IN ()
),

cte_term_taxonomy AS (
    SELECT
        term_relationships.object_id, -- Это будет parent_id из cte_posts
        term_taxonomy.term_taxonomy_id,
        term_taxonomy.term_id,
        term_taxonomy.taxonomy
    FROM :term_relationships AS term_relationships
    JOIN :term_taxonomy AS term_taxonomy
        ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
    -- Фильтруем сразу здесь, чтобы не ворочать всю базу
    WHERE term_relationships.object_id IN (SELECT parent_id FROM cte_posts)
)

SELECT
    cte_posts.id,
    cte_posts.name,
    cte_posts.slug,
    -- Эти данные берем у самой ВАРИАЦИИ
    price.meta_value AS price,
    stock.meta_value AS stock,
    gtin.meta_value AS gtin,

    -- Эти данные тянем от РОДИТЕЛЯ через cte_term_taxonomy
    brand.term_taxonomy_id AS brand_id,
    category.term_taxonomy_id AS category_id,

    -- Атрибуты у вариаций тоже часто лежат на родителе,
    -- либо в метаполях (но это отдельный ад)
    attribute.taxonomy AS attribute_taxonomy,
    attribute.term_id AS term_id

FROM cte_posts

-- Данные вариации (цены, остатки)
JOIN :postmeta AS price
    ON price.post_id = cte_posts.id
    AND price.meta_key = '_price'
LEFT JOIN :postmeta AS stock
    ON stock.post_id = cte_posts.id
    AND stock.meta_key = '_stock'
LEFT JOIN :postmeta AS gtin
    ON gtin.post_id = cte_posts.id
    AND gtin.meta_key = '_global_unique_id'

-- Джойним таксономии по PARENT_ID
LEFT JOIN cte_term_taxonomy AS brand
    ON brand.object_id = cte_posts.parent_id
    AND brand.taxonomy = 'product_brand'

LEFT JOIN cte_term_taxonomy AS category
    ON category.object_id = cte_posts.parent_id
    AND category.taxonomy = 'product_cat'

LEFT JOIN cte_term_taxonomy AS attribute
    ON attribute.object_id = cte_posts.parent_id
    AND attribute.taxonomy NOT IN (
        'product_brand',
        'product_cat',
        'product_tag'
    )