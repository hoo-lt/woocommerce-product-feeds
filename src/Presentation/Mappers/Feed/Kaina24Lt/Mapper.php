<?php

namespace Hoo\ProductFeeds\Presentation\Mappers\Feed\Kaina24Lt;

use Hoo\ProductFeeds\Domain;
use XMLWriter;

class Mapper
{
	public function __construct(
		protected readonly XMLWriter $xmlWriter,
	) {
	}

	public function all(
		Domain\Brands $brands,
		Domain\Categories $categories,
		Domain\Products $products,
	): string {
		$this->xmlWriter->openMemory();

		$this->xmlWriter->startDocument('1.0', 'UTF-8');

		$this->products(
			$brands,
			$categories,
			$products,
		);

		$this->xmlWriter->endDocument();

		return $this->xmlWriter->outputMemory();
	}

	protected function products(
		Domain\Brands $brands,
		Domain\Categories $categories,
		Domain\Products $products,
	): void {
		$this->xmlWriter->startElement('products');

		foreach ($products as $product) {
			$this->product(
				$brands,
				$categories,
				$product,
			);
		}

		$this->xmlWriter->endElement();
	}

	protected function product(
		Domain\Brands $brands,
		Domain\Categories $categories,
		Domain\Products\Product $product,
	): void {
		$this->xmlWriter->startElement('product');
		$this->xmlWriter->writeAttribute('id', $product->id);

		$this->cdata('title', $product->name);
		$this->text('price', (string) $product->price); //temp typecasting
		$this->text('condition', 'new');
		if ($product->stock) {
			$this->text('stock', (string) $product->stock); //temp typecasting
		}
		if ($product->gtin) {
			$this->text('ean_code', $product->gtin);
		}

		$brand = $product->brands->first();
		if ($brand) {
			$this->manufacturer(
				$brands,
				$brand,
			);
		}

		$category = $product->categories->first();
		if ($category) {
			$this->category(
				$categories,
				$category,
			);
		}

		$this->specs($product->attributes);

		$this->xmlWriter->endElement();
	}

	protected function manufacturer(
		Domain\Brands $brands,
		Domain\Products\Product\Brands\Brand $brand,
	): void {
		$brand = $brands->get($brand->id);

		$this->cdata('manufacturer', $brand->name);
	}

	protected function category(
		Domain\Categories $categories,
		Domain\Products\Product\Categories\Category $category,
	): void {
		$category = $categories->get($category->id);

		$this->text('category_id', $category->id);
		$this->cdata('category_name', $category->name);
		$this->cdata('category_link', $category->url);
	}

	protected function specs(Domain\Products\Product\Attributes $attributes): void
	{
		$this->xmlWriter->startElement('specs');

		foreach ($attributes as $attribute) {
			$this->spec($attribute);
		}

		$this->xmlWriter->endElement();
	}

	protected function spec(Domain\Products\Product\Attributes\Attribute $attribute): void
	{
		$this->xmlWriter->startElement('spec');
		$this->xmlWriter->writeAttribute('name', $attribute->name);
		$this->xmlWriter->writeCData(implode(', ', array_map(fn($term) => $term->name, $attribute->terms->all())));
		$this->xmlWriter->endElement();
	}

	protected function cdata(string $name, string $content)
	{
		$this->xmlWriter->startElement($name);
		$this->xmlWriter->writeCdata($content);
		$this->xmlWriter->endElement();
	}

	protected function text(string $name, string $content)
	{
		$this->xmlWriter->startElement($name);
		$this->xmlWriter->text($content);
		$this->xmlWriter->endElement();
	}
}