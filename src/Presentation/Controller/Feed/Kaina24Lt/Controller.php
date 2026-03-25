<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Presentation\Controller\Feed;

use Hoo\WordPressPluginFramework\Http;
use Hoo\WooCommercePlugin\LtProductFeeds\Presentation;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

readonly class Controller implements Presentation\Controller\Feed\ControllerInterface
{
	public function __construct(
		protected Domain\Repository\Attribute\RepositoryInterface $attributeRepository,
		protected Domain\Repository\Brand\RepositoryInterface $brandRepository,
		protected Domain\Repository\Category\RepositoryInterface $categoryRepository,
		protected Domain\Repository\Product\RepositoryInterface $productRepository,
		protected Presentation\Mapper\Feed\MapperInterface $mapper,
		protected string $path,
	) {
	}

	public function __invoke(): Http\ResponseInterface
	{
		return new Http\Response(
			[
				'Content-Type' => $this->mapper->contentType(),
			],
			$this->mapper->body(
				$this->attributeRepository->all(),
				$this->brandRepository->all(),
				$this->categoryRepository->all(),
				$this->productRepository->all(),
			),
		);
	}

	public function path(): string
	{
		return $this->path;
	}
}
