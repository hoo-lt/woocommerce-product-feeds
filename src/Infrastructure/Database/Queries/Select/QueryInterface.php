<?php

namespace Hoo\ProductFeeds\Infrastructure\Database\Queries\Select;

interface QueryInterface
{
	public function __invoke(): string;
}