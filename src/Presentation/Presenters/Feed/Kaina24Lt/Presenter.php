<?php

namespace Hoo\ProductFeeds\Presentation\Presenters\Feed\Kaina24Lt;

use Hoo\WordPressPluginFramework\Http;
use Hoo\ProductFeeds\Presentation;
use Hoo\ProductFeeds\Domain;

class Presenter implements Presentation\Presenters\Feed\PresenterInterface
{
	public function __construct(
		protected readonly Domain\Repository\Attribute\RepositoryInterface $attributeRepository,
		protected readonly Domain\Repository\Brand\RepositoryInterface $brandRepository,
		protected readonly Domain\Repository\Category\RepositoryInterface $categoryRepository,
		protected readonly Domain\Repository\Product\RepositoryInterface $productRepository,
		protected readonly Domain\Repository\Term\RepositoryInterface $termRepository,
		protected readonly Presentation\Mapper\Feed\Kaina24Lt\Mapper $kaina24LtMappers,
	) {
	}

	public function path(): string
	{
		return 'kaina24-lt.xml';
	}

	public function present(): Http\ResponseInterface
	{
		return new Http\Response(
			$this->headers(),
			$this->body(),
		);
	}

	protected function headers(): array
	{
		return [
			'Content-Type: application/xml; charset=utf-8'
		];
	}

	protected function body(): string
	{
		return $this->kaina24LtMappers->all(
			$this->attributeRepository->all(),
			$this->brandRepository->all(),
			$this->categoryRepository->all(),
			$this->productRepository->all(),
			$this->termRepository->all(),
		);
	}
}
