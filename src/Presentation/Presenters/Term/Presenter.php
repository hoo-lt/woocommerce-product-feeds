<?php

namespace Hoo\ProductFeeds\Presentation\Presenters\Term;

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Presentation;

class Presenter
{
	public function __construct(
		protected readonly Presentation\View\ViewInterface $view,
		protected readonly Presentation\Mappers\TermMeta\Mapper $termMetaMapper,
		protected readonly Domain\Repositories\TermMeta\RepositoryInterface $termMetaRepository,
	) {
	}

	public function view(int $id): string
	{
		return ($this->view)('/Term', [
			'icon' => $this->termMetaMapper->icon(
				$this->termMetaRepository->get($id)
			),
		]);
	}

	public function addView(): string
	{
		return ($this->view)('/Term/Add', [
			'options' => $this->termMetaMapper->options()
		]);
	}

	public function editView(int $id): string
	{
		return ($this->view)('/Term/Edit', [
			'selected' => $this->termMetaMapper->option(
				$this->termMetaRepository->get($id)
			),
			'options' => $this->termMetaMapper->options()
		]);
	}

	public function add(int $id, string $value): void
	{
		$this->termMetaRepository->set($id, Domain\TermMeta::from($value));
	}

	public function edit(int $id, string $value): void
	{
		$this->termMetaRepository->set($id, Domain\TermMeta::from($value));
	}
}
