<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
require( dirname(__FILE__) . '/_common.php' );

$config['nts_app_title'] = 'Z Schedule Manager';

$config['modules'] = array_merge( $config['modules'], array(
	'wordpress',
	'silentsetup',
	// 'algo',
	'ormrelations',

	'schedule',
	'classes',
	'publish',
	)
);