# WooCommerce Product Feeds by HOO

A lightweight WordPress plugin that generates XML product feeds for WooCommerce stores.
The project emphasises a clean, testable architecture and ships with ready-to-use domain
models, concrete repository implementations and XML mappers so you can build custom feeds
quickly and reliably.

---

## 🚀 What it does

- Registers custom feeds via `add_feed()` on WordPress `init`.
- Exposes product data (brands, categories, attributes, stock, GTIN, etc.) in XML format
	suitable for external marketplaces or comparison engines.
- Adds small, optional admin helpers for configuring feed-related term meta.
- Provides a DI container with sensible defaults for repositories, mappers, and view handling.

Out of the box the plugin ships with a single feed presenter targeting the **Kaina24.lt** format.
You can visit the feed by visiting `example.com/?feed=kaina24-lt.xml` (or using a rewrite rule).

---

## 🛠 Installation

1. Copy the `woocommerce-product-feeds` folder into `wp-content/plugins/`.
2. Activate the plugin from the WordPress dashboard.
3. Ensure WooCommerce (6.9+ / PHP 8.2+) is installed and active.
4. Flush rewrite rules (the plugin does this on activation).

---

## 📁 Architecture overview

````markdown
# WooCommerce Product Feeds by HOO

A fast, lightweight plugin that exports your WooCommerce catalog as XML feeds for marketplaces
and comparison engines.

Strong points (for store owners)

- Performance focused: feed XML is generated efficiently (streamed with `XMLWriter` and
  backed by optimized repository queries) to keep memory and CPU usage low on large catalogs.
- Zero-configuration: activate the plugin and the default feed is available immediately.
- Minimal overhead: small runtime footprint and few external dependencies for safe operation
  on production stores.
- Compatibility and reliability: built for modern WooCommerce/PHP versions and returns
  consistent, typed data exports.

How to use (store owner)

1. Install the plugin into `wp-content/plugins/` and activate it.
2. Visit the default feed URL: `https://your-site.com/?feed=kaina24-lt.xml`.

---

## For developers — minimal, focused guidance

This project intentionally keeps extension points tiny. If you need to add or change a feed,
you only need to know about the domain models, repository interfaces and the single hook
used to register presenters.

Models you will work with

- `Domain\Products` and `Domain\Products\Product` (product aggregate: price, stock, GTIN,
  attributes, relations).
- `Domain\Brands` and `Domain\Categories` (lookupable domain collections).
- `Domain\TermMeta` (term-scoped metadata where applicable).

Repository interfaces available

- `Domain\Repositories\Product\RepositoryInterface`
- `Domain\Repositories\Brand\RepositoryInterface`
- `Domain\Repositories\Category\RepositoryInterface`
- `Domain\Repositories\TermMeta\RepositoryInterface`

Presenter contract

Presenters must implement `Presentation\Presenters\Feed\PresenterInterface` with two methods:

- `path(): string` — the feed identifier passed to `add_feed()` (for example `kaina24-lt.xml`).
- `present(): string` — returns the feed XML string (the plugin will echo it when requested).

Single hook to register presenters

Add presenters via one filter: `woocommerce_product_feeds_add_feed_presenters`. Return an array
of presenter instances (or DI factories) from that filter to register them.

Example minimal registration:

```php
function my_add_feed_presenters(array $presenters) {
    $presenters[] = new \My\Namespace\MyMarketPresenter(/* deps */);
    return $presenters;
}
add_filter('woocommerce_product_feeds_add_feed_presenters', 'my_add_feed_presenters');
```

Performance note: the built-in repository implementations execute focused SQL and map results
to typed domain objects; this minimizes PHP-side processing and memory usage for large exports.

---

## 📝 License

This project is licensed under the [GPL‑3.0](https://www.gnu.org/licenses/gpl-3.0.html).

---

If you want help writing a presenter that uses the existing repositories, say which feed
format you need and I will produce a minimal, ready-to-register example.

````
function my_add_feed_presenters(array $presenters) {

		$presenters[] = new \My\Namespace\MyMarketPresenter(/* deps */);
