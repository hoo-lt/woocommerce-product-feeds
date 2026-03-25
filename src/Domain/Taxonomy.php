<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain;

enum Taxonomy: string
{
	case Brand = 'product_brand';
	case Category = 'product_cat';
	case Tag = 'product_tag';
}