<?php

namespace Hoo\ProductFeeds\Application\Taxonomy;


use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Application;

use WP_Term;

class Controller
{
	protected const COLUMN_NAME = 'product_feeds';

	public function __construct(
		protected readonly Application\Term\RepositoryInterface $repository,
		protected readonly Application\TemplateInterface $template,
	) {
	}

	public function __invoke(Domain\Taxonomy $taxonomy): void
	{
		add_filter("manage_edit-{$taxonomy->value}_columns", $this->addColumn(...), PHP_INT_MAX, 1);
		add_filter("manage_{$taxonomy->value}_custom_column", $this->add2(...), PHP_INT_MAX, 3);
		add_action("{$taxonomy->value}_add_form_fields", $this->addFormFields(...), PHP_INT_MAX, 1);
		add_action("{$taxonomy->value}_edit_form_fields", $this->editFormFields(...), PHP_INT_MAX, 2);
		add_action("create_{$taxonomy->value}", $this->saveField(...), PHP_INT_MAX, 1);
		add_action("edited_{$taxonomy->value}", $this->saveField(...), PHP_INT_MAX, 1);
	}

	protected function addColumn(array $columns): array
	{
		$columns[self::COLUMN_NAME] = __('Product feeds', 'woocommerce-plugin-product-feeds');
		return $columns;
	}

	protected function add2(string $string, string $columnName, int $termId): string
	{
		return match ($columnName) {
			self::COLUMN_NAME => $this->repository->get($termId)->label(),
			default => $string,
		};
	}

	protected function editFormFields(WP_Term $term, string $taxonomy): void
	{
		$labels = Domain\Term::labels();

		($this->template)('/EditFormFields/Select', [
			'value' => $this->repository->get($term->term_id)->value,
			'options' => array_map(fn($value, $label) => [
				'value' => $value,
				'label' => $label,
			], array_keys($labels), array_values($labels)),
		]);
	}

	protected function addFormFields(string $taxonomy): void
	{
		$labels = Domain\Term::labels();

		($this->template)('/AddFormFields/Select', [
			'value' => null,
			'options' => array_map(fn($value, $label) => [
				'value' => $value,
				'label' => $label,
			], array_keys($labels), array_values($labels)),
		]);
	}

	protected function saveField(int $termId): void
	{
		$this->repository->set($termId, Domain\Term::from($_POST[self::COLUMN_NAME]));
	}
}
