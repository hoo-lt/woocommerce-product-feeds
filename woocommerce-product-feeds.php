<?php

/**
 * Plugin Name: WooCommerce product feeds
 * Plugin URI: https://github.com/hoo-lt/woocommerce-product-feeds
 * Description:
 * Version: 1.0.0
 * Requires at least: 6.9
 * Requires PHP: 8.2
 * Author: HOO
 * Author URI: https://github.com/hoo-lt
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: product-feeds
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
	die();
}

require __DIR__ . '/vendor/autoload.php';

const WOOCOMMERCE_PRODUCT_FEEDS = true;

use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Infrastructure;

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
	Application\Controllers\Term\ControllerInterface::class => DI\get(Application\Controllers\Term\Controller::class),
	Application\Mappers\Term\MapperInterface::class => DI\get(Infrastructure\Mappers\Term\Mapper::class),
	Application\Repositories\Term\RepositoryInterface::class => DI\get(Infrastructure\Repositories\Term\Repository::class),
	Application\TemplateInterface::class => DI\get(Infrastructure\Template::class),
	Infrastructure\Hook::class => DI\create()
		->constructor(
			DI\get(Application\Controllers\Term\ControllerInterface::class),
			DI\get(Application\Controllers\Feed\Kaina24Lt\Controller::class),
			DI\get(Application\Controllers\Feed\KainosLt\Controller::class),
			DI\get(Application\Controllers\Feed\KainotekaLt\Controller::class)
		)
]);

$container = $containerBuilder->build();

$hook = $container->get(Infrastructure\Hook::class);
$hook();

register_activation_hook(__FILE__, function () use ($hook) {
	$hook->add_feeds();
	$hook->flush_rewrite_rules();
});

function get()
{
global $wpdb;

( $wpdb->get_results( $wpdb->prepare(
    "WITH RECURSIVE excluded_tree AS (
        SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key = %s AND meta_value = %s
        UNION ALL
        SELECT tt.term_id FROM {$wpdb->term_taxonomy} tt
        INNER JOIN excluded_tree et ON tt.parent = et.term_id
    )
    SELECT DISTINCT p.ID, p.post_title
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
    WHERE p.post_type = 'product'
      AND p.post_status = 'publish'
      AND p.ID NOT IN (
          SELECT tr2.object_id
          FROM {$wpdb->term_relationships} tr2
          WHERE tr2.term_taxonomy_id IN (SELECT term_id FROM excluded_tree)
      )",
    'product_feeds', // Сюда СТРОКУ руками для теста
    'exclude'           // И сюда СТРОКУ руками
),ARRAY_A ));
}

get();