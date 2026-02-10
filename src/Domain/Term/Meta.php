<?php

namespace Hoo\ProductFeeds\Domain\Term;

enum Meta: string
{
	public const KEY = 'product_feeds';

	case Included = 'included';
	case Excluded = 'excluded';
}