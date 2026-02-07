<?php

namespace Hoo\ProductFeeds\Infrastructure\Queries;

interface QueryInterface
{
	public function __invoke(): string;
}