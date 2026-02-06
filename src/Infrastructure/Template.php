<?php

namespace Hoo\ProductFeeds\Infrastructure;

use Hoo\ProductFeeds\Application;

class Template implements Application\TemplateInterface
{
	protected string $path;

	public function __construct(string $path)
	{
		$this->path = "{$path}/src/Presentation/Template";
	}

	public function __invoke(string $template, array $array): void
	{
		$path = "{$this->path}/$template.php";
		if (!file_exists($path)) {
			trigger_error("Template not found: $path", E_USER_WARNING);
		}

		extract($array);

		require($path);
	}
}