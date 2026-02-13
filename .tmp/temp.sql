WITH product_list AS (
    -- Берем только базу: простые опубликованные товары
    SELECT p.ID, p.post_title
    FROM wp_posts p
    INNER JOIN wp_term_relationships tr ON p.ID = tr.object_id
    INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        AND tt.taxonomy = 'product_type'
    INNER JOIN wp_terms t ON tt.term_id = t.term_id AND t.slug = 'simple'
    WHERE p.post_type = 'product' AND p.post_status = 'publish'
),
all_categories AS (
    -- Собираем все категории для этих товаров
    SELECT tr.object_id, t.name as cat_name
    FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_cat'
    JOIN wp_terms t ON tt.term_id = t.term_id
),
all_brands AS (
    -- Собираем все бренды
    SELECT tr.object_id, t.name as brand_name
    FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_brand'
    JOIN wp_terms t ON tt.term_id = t.term_id
),
all_attributes AS (
    -- Собираем все атрибуты
    SELECT tr.object_id, tt.taxonomy as attr_slug, t.name as attr_value
    FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    JOIN wp_terms t ON tt.term_id = t.term_id
    JOIN wp_woocommerce_attribute_taxonomies wat ON tt.taxonomy = CONCAT('pa_', wat.attribute_name)
)

-- Теперь сшиваем всё в один результат
SELECT
    pl.ID AS product_id,
    pl.post_title AS product_name,
    ac.cat_name AS category,
    ab.brand_name AS brand,
    aa.attr_slug,
    aa.attr_value
FROM product_list pl
LEFT JOIN all_categories ac ON pl.ID = ac.object_id
LEFT JOIN all_brands ab ON pl.ID = ab.object_id
LEFT JOIN all_attributes aa ON pl.ID = aa.object_id
ORDER BY pl.ID;


-- сдел запрос
WITH product_list AS (
    -- База: только простые опубликованные товары
    SELECT p.ID, p.post_title
    FROM wp_posts p
    INNER JOIN wp_term_relationships tr ON p.ID = tr.object_id
    INNER JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        AND tt.taxonomy = 'product_type'
    INNER JOIN wp_terms t ON tt.term_id = t.term_id AND t.slug = 'simple'
    WHERE p.post_type = 'product' AND p.post_status = 'publish'
),
tax_data AS (
    -- Собираем все нужные таксономии одним махом
    SELECT
        tr.object_id,
        t.name AS term_name,
        tt.taxonomy
    FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    JOIN wp_terms t ON tt.term_id = t.term_id
    WHERE tt.taxonomy IN ('product_cat', 'product_brand')
)

-- Итоговая сборка
SELECT
    pl.ID AS product_id,
    pl.post_title AS product_name,
    cat.term_name AS category,
    brand.term_name AS brand
FROM product_list pl
-- Джойним одну и ту же CTE, фильтруя по типу таксономии
LEFT JOIN tax_data cat ON pl.ID = cat.object_id AND cat.taxonomy = 'product_cat'
LEFT JOIN tax_data brand ON pl.ID = brand.object_id AND brand.taxonomy = 'product_brand'
ORDER BY pl.ID;

------


WITH variation_list AS (
    SELECT p.ID AS var_id, p.post_parent AS parent_id, p.post_title AS var_name
    FROM wp_posts p
    WHERE p.post_type = 'product_variation' AND p.post_status = 'publish'
),
-- Собираем все атрибуты в одну кучу с приоритетом
combined_attributes AS (
    -- 1. Берем атрибуты вариаций (Приоритет 1)
    SELECT
        pm.post_id AS target_id,
        REPLACE(pm.meta_key, 'attribute_', '') AS attr_slug,
        COALESCE(t.name, pm.meta_value) AS attr_value,
        1 AS priority
    FROM wp_postmeta pm
    JOIN wp_terms t ON pm.meta_value = t.slug
    WHERE pm.meta_key LIKE 'attribute_pa_%' AND pm.meta_value != ''
      AND pm.post_id IN (SELECT var_id FROM variation_list)

    UNION ALL

    -- 2. Берем атрибуты родителей (Приоритет 2)
    SELECT
        vl.var_id AS target_id,
        tt.taxonomy AS attr_slug,
        t.name AS attr_value,
        2 AS priority
    FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    JOIN wp_terms t ON tt.term_id = t.term_id
    JOIN wp_woocommerce_attribute_taxonomies wat ON tt.taxonomy = CONCAT('pa_', wat.attribute_name)
    JOIN variation_list vl ON tr.object_id = vl.parent_id
),
-- Оставляем только самое важное значение для каждого слага
filtered_attributes AS (
    SELECT target_id, attr_slug, attr_value
    FROM (
        SELECT *,
               ROW_NUMBER() OVER(PARTITION BY target_id, attr_slug ORDER BY priority ASC) as rn
        FROM combined_attributes
    ) tmp
    WHERE rn = 1
),
all_categories AS (
    SELECT tr.object_id, t.name as cat_name FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_cat'
    JOIN wp_terms t ON tt.term_id = t.term_id
),
all_brands AS (
    SELECT tr.object_id, t.name as brand_name FROM wp_term_relationships tr
    JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_brand'
    JOIN wp_terms t ON tt.term_id = t.term_id
)

SELECT
    vl.var_id AS product_id,
    vl.var_name AS product_name,
    ac.cat_name AS category,
    ab.brand_name AS brand,
    fa.attr_slug,
    fa.attr_value
FROM variation_list vl
LEFT JOIN filtered_attributes fa ON vl.var_id = fa.target_id
LEFT JOIN all_categories ac ON vl.parent_id = ac.object_id
LEFT JOIN all_brands ab ON vl.parent_id = ab.object_id
ORDER BY vl.var_id;