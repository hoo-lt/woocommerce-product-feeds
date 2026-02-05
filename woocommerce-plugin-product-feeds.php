<?php
/**
 * Plugin Name: Product feeds
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
	exit;
}

require __DIR__ . '/vendor/autoload.php';

use Hoo\ProductFeeds\Presentation;
use Hoo\ProductFeeds\Infrastructure;

new Presentation\Taxonomy\Controller(new Infrastructure\Term\Repository)('product_brand');
new Presentation\Taxonomy\Controller(new Infrastructure\Term\Repository)('product_cat');