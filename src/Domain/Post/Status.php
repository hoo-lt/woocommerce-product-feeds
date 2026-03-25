<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Domain\Post;

enum Status: string
{
	case Publish = 'publish';
	case Future = 'future';
	case Draft = 'draft';
	case Pending = 'pending';
	case Private = 'private';
	case Trash = 'trash';
	case AutoDraft = 'auto-draft';
	case Inherit = 'inherit';
}