<?php

namespace Hoo\ProductFeeds\Presentation\Presenters\Term;

use Hoo\WordPressPluginFramework\Http\RequestInterface;
use Hoo\WordPressPluginFramework\View\ViewInterface;
use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Presentation;

class Presenter
{
	public function __construct(
		protected readonly RequestInterface $request,
		protected readonly ViewInterface $view,
		protected readonly Presentation\Mapper\TermMeta\Mapper $termMetaMapper,
		protected readonly Domain\Repository\TermMeta\RepositoryInterface $termMetaRepository,
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

	public function add(int $id): void
	{
		$value = $this->request->post(Domain\TermMeta::KEY);
		if ($value) {
			$this->termMetaRepository->set($id, Domain\TermMeta::from($value));
		} else {
			//$this->termMetaRepository->delete?
		}
	}

	public function edit(int $id): void
	{
		$value = $this->request->post(Domain\TermMeta::KEY);
		if ($value) {
			$this->termMetaRepository->set($id, Domain\TermMeta::from($value));
		} else {
			//$this->termMetaRepository->delete?
		}
	}
}
