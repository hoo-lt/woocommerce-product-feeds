<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain;

enum TermMeta: string
{
	public const KEY = 'product_feeds';

	case Included = 'included';
	case Excluded = 'excluded';
}