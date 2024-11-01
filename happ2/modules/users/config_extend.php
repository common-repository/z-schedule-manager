<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['view/index/index@render'] = array(
	'view/index/layout@extend-index',
	);
$after['view/zoom/index@render'] = array(
	'view/zoom/layout@extend-index',
	);
$after['view/new/index@render'] = array(
	'view/new/layout@extend-index',
	);
