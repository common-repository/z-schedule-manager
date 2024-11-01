<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$after['/layout/view/body@render'] = array(
	'view/full@extend-body'
	);

$after['/layout/view/assets@css']	= array(
	80	=> 'view/assets@extend-css',
	);
