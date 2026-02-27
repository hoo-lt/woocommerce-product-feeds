<?php

namespace Hoo\ProductFeeds\Infrastructure\Http;

class Request
{
	public function __construct(
		protected readonly array $get,
		protected readonly array $post,
	) {
	}
}