<?php
/**
 * Plugin Name: Product feeds
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
	exit;
}

require __DIR__ . '/vendor/autoload.php';

const PRODUCT_FEEDS = true;

use Hoo\ProductFeeds\Presentation;
use Hoo\ProductFeeds\Application;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;

$taxonomyController = new Application\Taxonomy\Controller(
	new Infrastructure\Term\Repository,
	new Infrastructure\Template(plugin_dir_path(__FILE__))
);
$taxonomyController(Domain\Taxonomy::from('product_brand'));
$taxonomyController(Domain\Taxonomy::from('product_cat'));