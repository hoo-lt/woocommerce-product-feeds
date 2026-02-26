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

| Layer | Responsibility |
|-------|----------------|
| **Domain** | Entities (`Product`, `Category`, `Brand`, `TermMeta`), value objects and repository interfaces. |
| **Infrastructure** | Database queries, concrete repository implementations, WordPress hooks wiring and caching. |
| **Presentation** | XML mappers, view rendering, presenters (feed generators & admin term meta presenters). |

Everything is resolved through [PHP-DI](https://php-di.org/) which allows injection of custom implementations.

---

## ✨ Strong points (why use this plugin)

- Clean architecture: domain, infrastructure and presentation layers keep feed logic
	decoupled and testable.
- Ready domain models and aggregates: `Domain\Products`, `Domain\Brands`, `Domain\Categories`
	and `Domain\TermMeta` provide rich objects (not raw arrays) so presenters operate on
	meaningful types.
- Concrete repositories out of the box: `Infrastructure\Repositories\Product\Repository`,
	`Brand\Repository`, `Category\Repository`, `TermMeta\Repository` — these perform the
	DB queries and map rows to domain objects.
- XML mappers: presentation mappers (for example `Presentation\Mappers\Feed\Kaina24Lt\Mapper`)
	encapsulate XML generation using `XMLWriter` so you can reuse or extend them.
- DI-ready: plugin bootstrap uses `PHP-DI` so you can override or add implementations
	via the container or by supplying presenters through the provided filter.

## ✨ Extending with new feed presenters (recommended)

The recommended way to create new feeds is to implement
`Presentation\Presenters\Feed\PresenterInterface`, inject the built-in repositories and
mappers, then return XML from `present()` and a unique string from `path()`.

Key building blocks you will likely use:

- `Domain\Repositories\Product\RepositoryInterface` — returns `Domain\Products` with
	`Domain\Products\Product` items (prices, stock, GTIN, attributes, relationships).
- `Domain\Repositories\Brand\RepositoryInterface` and `Domain\Repositories\Category\RepositoryInterface`
	— provide lookup and listing for brand and category domain objects.
- `Presentation\Mappers\Feed\*` — mappers that build XML from domain aggregates using `XMLWriter`.

Example presenter that reuses built-in repositories and a mapper:

```php
use Hoo\ProductFeeds\Presentation\Presenters\Feed\PresenterInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Presentation\Mappers\Feed\Kaina24Lt\Mapper as XmlMapper;

class MyMarketPresenter implements PresenterInterface
{
		public function __construct(
				protected Domain\Repositories\Product\RepositoryInterface $products,
				protected Domain\Repositories\Brand\RepositoryInterface $brands,
				protected Domain\Repositories\Category\RepositoryInterface $categories,
				protected XmlMapper $xmlMapper,
		) {}

		public function path(): string
		{
				return 'my-marketplace.xml';
		}

		public function present(): string
		{
				header('Content-Type: application/xml; charset=utf-8');
				return $this->xmlMapper->all(
						$this->brands->all(),
						$this->categories->all(),
						$this->products->all(),
				);
		}
}
```

Registering your presenter

The plugin's bootstrap constructs `Infrastructure\Hooks\ActionHooks` with default presenters
and merges additional presenters returned by the `woocommerce_product_feeds_add_feed_presenters`
filter. You can register presenters by returning them from that filter (or by altering the
DI container in more advanced use cases).

```php
function my_add_feed_presenters(array $presenters) {
		$presenters[] = new \My\Namespace\MyMarketPresenter(/* deps */);
		return $presenters;
}
add_filter('woocommerce_product_feeds_add_feed_presenters', 'my_add_feed_presenters');
```

After registering, flush rewrite rules or re-save permalinks so the feed path becomes active.

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

