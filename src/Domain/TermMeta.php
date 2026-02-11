<?php

namespace Hoo\ProductFeeds\Domain;

enum TermMeta: string
{
	public const KEY = 'product_feeds';

	case Included = 'included';
	case Excluded = 'excluded';
}