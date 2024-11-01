<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['app_version'] = '1.0.4';
$config['dbprefix_version'] = 'v1';

$config['modules'] = array(
	'app',
	'utf8',
	'http',
	'html',
	'input',
	'form',
	'validate',
	'security',
	'encrypt',
	'session',

	'datetime',
	'datepicker',

	'msgbus',
	'flashdata',
	'layout',
	'root',
	// 'acl',
	'icons',
	'icons-chars',

	'code-snippets',
	// 'conf',
	'auth',

	'users',
	'print',
	'recur-dates',
	// 'algo'
	);
