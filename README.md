# WooCommerce Product Feeds by HOO

The fastest, most reliable product feed plugin for WooCommerce. Built with enterprise-grade architecture (Domain-Driven Design), raw SQL queries (no N+1 problems), built-in caching, and a minimal extension surface. Export to any marketplace format without the bloat of typical WordPress plugins.

---

## For store owners — why use this

- **Exports massive catalogs in seconds:** 100,000+ products? No problem. Your site stays fast.
- **Zero setup:** install, activate, done — feed is live with no config panels to fiddle with.
- **Smart filtering:** easily filter products by categories, brands, or tags — applies to all feeds.
- **Perfect every time:** identical exports, zero data glitches or missing products.
- **Built for production:** minimal server load, safe to run on busy stores.

How to use
1. Install into `wp-content/plugins/` and activate
2. Your feed is ready: `https://your-site.com/?feed=kaina24-lt.xml`

---

## For developers — add a custom feed

Add a new feed format in minutes using the built-in models and repositories.

You only need to know:
- **Models:** `Domain\Products\Product`, `Domain\Brands`, `Domain\Categories`
- **Repositories:** `Domain\Repositories\Product\RepositoryInterface`, `Brand\RepositoryInterface`, `Category\RepositoryInterface`
- **Hook:** `woocommerce_product_feeds_add_feed_presenters` to register your presenter

Built-in models and repositories explained

**Product Repository** — `Domain\Repositories\Product\RepositoryInterface`
- `all(): Domain\Products` — returns all products with price, stock, GTIN, attributes, and relationships to brands/categories loaded
- Each `Domain\Products\Product` has: `id`, `name`, `price`, `stock`, `gtin`, `attributes`, `brands`, `categories`

**Brand Repository** — `Domain\Repositories\Brand\RepositoryInterface`
- `all(): Domain\Brands` — returns all brands
- Lookup by ID to enrich product data with brand names/URLs

**Category Repository** — `Domain\Repositories\Category\RepositoryInterface`
- `all(): Domain\Categories` — returns all product categories
- Use to build category breadcrumbs or filter products by category

Your presenter implements `Presentation\Presenters\Feed\PresenterInterface`:
```php
public function path(): string;      // feed URL slug (e.g. "my-marketplace.xml")
public function present(): string;   // return the XML as a string
```

Example minimal presenter:

```php
use Hoo\ProductFeeds\Presentation\Presenters\Feed\PresenterInterface;
use Hoo\ProductFeeds\Domain;

class MyMarketPresenter implements PresenterInterface
{
    public function __construct(
        protected Domain\Repositories\Product\RepositoryInterface $products,
    ) {}

    public function path(): string
    {
        return 'my-marketplace.xml';
    }

    public function present(): string
    {
        header('Content-Type: application/xml; charset=utf-8');
        
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<products>\n";
        
        foreach ($this->products->all() as $product) {
            $xml .= "  <product id=\"{$product->id}\">";
            $xml .= "<title>{$product->name}</title>";
            $xml .= "<price>{$product->price}</price>";
            if ($product->stock) {
                $xml .= "<stock>{$product->stock}</stock>";
            }
            $xml .= "</product>\n";
        }
        
        $xml .= "</products>";
        return $xml;
    }
}
```

Register it:
```php
function my_add_presenters(array $presenters) {
    $presenters[] = new \My\Namespace\MyMarketPresenter(
        // inject repositories via DI or resolve manually
    );
    return $presenters;
}
add_filter('woocommerce_product_feeds_add_feed_presenters', 'my_add_presenters');
```

Why this plugin kicks ass for developers
- Uses **Domain-Driven Design:** typed models, not raw arrays — less bugs
- **Raw SQL:** repositories execute focused queries only, not WP_Query/WP_Product_Query — no N+1 problems, no OOMs on large catalogs
- **Built-in caching:** optional result caching layer to reduce database load on repeated exports
- **Built to scale:** streaming output, minimal PHP memory overhead
- **Minimal surface:** one hook, three model types, four repository interfaces — that's it

---

## License

GPL-3.0 — https://www.gnu.org/licenses/gpl-3.0.html
