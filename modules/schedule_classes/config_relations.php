<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['/schedule/model']['has_one']['class'] = array(
	'their_class'	=> '/classes/model',
	'my_name'		=> 'schedules',
	'relation_name'	=> 'class_schedule',
	);

$config['/classes/model']['belongs_to_many']['schedules'] = array(
	'their_class'	=> '/schedule/model',
	'my_name'		=> 'class',
	'relation_name'	=> 'class_schedule',
	);
