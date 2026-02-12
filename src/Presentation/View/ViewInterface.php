<?php

namespace Hoo\ProductFeeds\Presentation\View;

interface ViewInterface
{
	public function __invoke(string $view, array $array): string;
}