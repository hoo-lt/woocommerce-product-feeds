<?php

namespace Hoo\ProductFeeds\Infrastructure\Hooks;

use Hoo\ProductFeeds\Application;

class ActionHooks
{
	//protected readonly array $productFeedControllers;

	public function __construct(
		//Application\Controllers\ProductFeed\ControllerInterface ...$productFeedControllers,
	) {
		//$this->productFeedControllers = $productFeedControllers;
	}

	public function __invoke(): void
	{
		add_action('admin_enqueue_scripts', [
			$this,
			'admin_enqueue_scripts'
		], PHP_INT_MAX, 0);

		/*
		add_action('init', [
			$this,
			'add_feeds'
		], PHP_INT_MAX, 0);
		*/
	}

	public function admin_enqueue_scripts(): void
	{
		wp_enqueue_style(
			'product-feeds-admin',
			plugins_url('/assets/css/admin.css', __DIR__ . '/../../../woocommerce-product-feeds.php')
		);
	}

	public function add_feeds(): void
	{
		foreach ($this->productFeedControllers as $productFeedController) {
			add_feed($productFeedController->path(), function () use ($productFeedController) {
				echo $productFeedController();
			});
		}
	}
}