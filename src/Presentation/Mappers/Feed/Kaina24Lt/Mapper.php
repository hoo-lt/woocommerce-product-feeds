<?php

namespace Hoo\ProductFeeds\Presentation\Mappers\Feed\Kaina24Lt;

use Hoo\ProductFeeds\Domain;
use XMLWriter;

class Mapper
{
	public function all(
		Domain\Brands $brands,
		Domain\Categories $categories,
		Domain\Products $products
	): string {
		$xmlWriter = new XMLWriter;
		$xmlWriter->openMemory();

		$xmlWriter->startDocument('1.0', 'UTF-8');

		$this->products(
			$brands,
			$categories,
			$products,
			$xmlWriter
		);

		$xmlWriter->endDocument();

		return $xmlWriter->outputMemory();
	}

	protected function products(
		Domain\Brands $brands,
		Domain\Categories $categories,
		Domain\Products $products,
		XMLWriter $xmlWriter
	): void {
		$xmlWriter->startElement('products');

		foreach ($products as $product) {
			$this->product(
				$brands,
				$categories,
				$product,
				$xmlWriter
			);
		}

		$xmlWriter->endElement();
	}

	protected function product(
		Domain\Brands $brands,
		Domain\Categories $categories,
		Domain\Products\Product $product,
		XMLWriter $xmlWriter
	): void {
		$xmlWriter->startElement('product');
		$xmlWriter->writeAttribute('id', $product->id);

		$this->cdata('title', $product->name, $xmlWriter);
		$this->text('price', (string) $product->price, $xmlWriter); //temp typecasting
		$this->text('condition', 'new', $xmlWriter);
		if ($product->stock) {
			$this->text('stock', (string) $product->stock, $xmlWriter); //temp typecasting
		}
		if ($product->gtin) {
			$this->text('ean_code', $product->gtin, $xmlWriter);
		}

		$brand = $product->brands->first();
		if ($brand) {
			$this->manufacturer(
				$brands,
				$brand,
				$xmlWriter
			);
		}

		$category = $product->categories->first();
		if ($category) {
			$this->category(
				$categories,
				$category,
				$xmlWriter
			);
		}

		$this->specs($product->attributes, $xmlWriter);

		$xmlWriter->endElement();
	}

	protected function manufacturer(
		Domain\Brands $brands,
		Domain\Products\Product\Brands\Brand $brand,
		XMLWriter $xmlWriter
	): void {
		$brand = $brands->get($brand->id);

		$this->cdata('manufacturer', $brand->name, $xmlWriter);
	}

	protected function category(
		Domain\Categories $categories,
		Domain\Products\Product\Categories\Category $category,
		XMLWriter $xmlWriter
	): void {
		$category = $categories->get($category->id);

		$this->text('category_id', $category->id, $xmlWriter);
		$this->cdata('category_name', $category->name, $xmlWriter);
		$this->cdata('category_link', $category->url, $xmlWriter);
	}

	protected function specs(Domain\Products\Product\Attributes $attributes, XMLWriter $xmlWriter): void
	{
		$xmlWriter->startElement('specs');

		foreach ($attributes as $attribute) {
			$this->spec($attribute, $xmlWriter);
		}

		$xmlWriter->endElement();
	}

	protected function spec(Domain\Products\Product\Attributes\Attribute $attribute, XMLWriter $xmlWriter): void
	{
		$xmlWriter->startElement('spec');
		$xmlWriter->writeAttribute('name', $attribute->name);
		$xmlWriter->writeCData(implode(', ', array_map(fn($term) => $term->name, $attribute->terms->all())));
		$xmlWriter->endElement();
	}

	protected function cdata(string $name, string $content, XMLWriter $xmlWriter)
	{
		$xmlWriter->startElement($name);
		$xmlWriter->writeCdata($content);
		$xmlWriter->endElement();
	}

	protected function text(string $name, string $content, XMLWriter $xmlWriter)
	{
		$xmlWriter->startElement($name);
		$xmlWriter->text($content);
		$xmlWriter->endElement();
	}
}