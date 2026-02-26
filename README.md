# WooCommerce Product Feeds by HOO

A lightweight WordPress plugin that generates XML product feeds for WooCommerce stores. The
library is built with clean architecture principles (domain, infrastructure, presentation) and
is easy to extend with custom feed presenters.

---

## 🚀 What it does

- Registers custom feeds via `add_feed()` on WordPress `init`.
- Exposes product data (brands, categories, attributes, stock, GTIN, etc.) in XML format
	suitable for external marketplaces or comparison engines.
- Adds a **Product feeds** column and term‑meta controls to all taxonomies defined by
	WooCommerce (categories, tags, brands, etc.).
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

| Layer | Responsibility |
|-------|----------------|
| **Domain** | Entities (`Product`, `Category`, `Brand`, `TermMeta`), value objects and repository interfaces. |
| **Infrastructure** | Database queries, concrete repository implementations, WordPress hooks wiring and caching. |
| **Presentation** | XML mappers, view rendering, presenters (feed generators & admin term meta presenters). |

Everything is resolved through [PHP-DI](https://php-di.org/) which allows injection of custom implementations.

---

## ✨ Extending with new feed presenters

Feed presenters are responsible for returning an XML string and providing a path used by `add_feed()`.

### 1. Create a presenter

Implement `\Hoo\ProductFeeds\Presentation\Presenters\Feed\PresenterInterface`:

```php
use Hoo\ProductFeeds\Presentation\Presenters\Feed\PresenterInterface;
use Hoo\ProductFeeds\Domain; // for repositories etc.

class MyMarketplacePresenter implements PresenterInterface
{
		public function __construct(
				Domain\Repositories\Product\RepositoryInterface $productRepo,
				// ... other dependencies
		) {}

		public function path(): string
		{
				return 'my-marketplace.xml';
		}

		public function present(): string
		{
				header('Content-Type: application/xml; charset=utf-8');
				// build XML using mappers or manually
				return '<feed>…</feed>';
		}
}
```

Alternatively, extend an existing mapper or create a completely new one under
`src/Presentation/Mappers/Feed/…`.

### 2. Register the presenter via filter

The bootstrap file applies a filter when building the `ActionHooks` object:

```php
apply_filters('woocommerce_product_feeds_add_feed_presenters', []);
```

You can add your presenter in your theme or another plugin:

```php
function my_plugin_add_feed_presenter() {
		return [
				new \My\Namespace\MyMarketplacePresenter(),
		];
}
add_filter('woocommerce_product_feeds_add_feed_presenters', 'my_plugin_add_feed_presenter');
```

Because the DI container is in use, you may prefer to register your presenter through the
container or use `DI\factory` to resolve it.

### 3. Clear rewrite rules

Flush rewrites (either programmatically with `flush_rewrite_rules()` or by re-saving permalinks)
so that your new feed path is recognised.

### 4. Test the feed

Visit `https://your-site.com/?feed=my-marketplace.xml` or the appropriate rewrite-friendly URL.

---

## 📌 Taxonomy-term metadata

The plugin adds a new column and input field on taxonomy term add/edit screens. This allows you to
configure an icon or other metadata used by feeds (currently used by Kaina24.lt).

* The `Term\termmMetaMapper` handles options and output for the admin UI.
* Repositories under `Domain\Repositories\TermMeta` persist the values in term meta.

You can extend the mapper or presenter to use additional term metadata for filtering products or
customizing feed elements.

### Hooks provided

```php
// modify the term meta options dropdown
add_filter('woocommerce_product_feeds_term_meta_options', function(array $options){
		$options['my_custom'] = 'My custom option';
		return $options;
});
```

---

## 💡 Developer tips

- Use the existing `Infrastructure\Repositories` classes as examples when writing new
data-access code.
- All database operations go through `Infrastructure\Database\Queries` objects; you can adapt or
	extend them for custom filtering. The query classes receive `homeUrl` and `permalink` values at
	construction.
- If you need caching, the `Infrastructure\Cache\CacheInterface` is registered; the concrete
	implementation simply wraps the WP object cache.
- For view rendering on the admin side, `Presentation\View\View` is a very thin wrapper around
	`load_template()` – drop your templates in `src/Presentation/View/Views/...`.

---

## 📝 License

This project is licensed under the [GPL‑3.0](https://www.gnu.org/licenses/gpl-3.0.html).

---

If you want help writing a new presenter or have questions about the architecture, feel free to
open an issue or submit a pull request on the [GitHub repository](https://github.com/hoo-lt/woocommerce-product-feeds).

