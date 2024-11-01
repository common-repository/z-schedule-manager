<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['datetime:_label'] = HCM::__('Date and Time');

$config['datetime:date_format'] = array(
	'default' 	=> 'j M Y',
	'label'		=> HCM::__('Date Format'),
	'type'		=> 'dropdown',
	'options'	=> array(
		'd/m/Y'	=> date('d/m/Y'),
		'd-m-Y'	=> date('d-m-Y'),
		'n/j/Y'	=> date('n/j/Y'),
		'Y/m/d'	=> date('Y/m/d'),
		'd.m.Y'	=> date('d.m.Y'),
		'j M Y'	=> date('j M Y'),
		'Y-m-d'	=> date('Y-m-d'),
		),
	);

$config['datetime:time_format'] = array(
	'default' 	=> 'g:ia',
	'label'		=> HCM::__('Time Format'),
	'type'		=> 'dropdown',
	'options'	=> array(
		'g:ia'	=> date('g:ia'),
		'g:i A'	=> date('g:i A'),
		'H:i'	=> date('H:i'),
		),
	);

$config['datetime:week_starts'] = array(
	'default' 	=> 0,
	'label'		=> HCM::__('Week Starts On'),
	'type'		=> 'dropdown',
	'options'	=> array(
		0	=> HCM::__('Sun'),
		1	=> HCM::__('Mon'),
		2	=> HCM::__('Tue'),
		3	=> HCM::__('Wed'),
		4	=> HCM::__('Thu'),
		5	=> HCM::__('Fri'),
		6	=> HCM::__('Sat'),
		),
	);
