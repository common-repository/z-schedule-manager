<?php
/*
 * Plugin Name: Z Schedule Manager
 * Plugin URI: http://www.hitcode.com/z-schedule-manager/
 * Description: Quickly create a recurring schedule of your classes for your school, gym, academy, campus and publish on a post or a page with a shortcode.
 * Version: 1.0.4
 * Author: hitcode.com
 * Author URI: http://www.hitcode.com/
 * Text Domain: z-schedule-manager
 * Domain Path: /languages/
*/

if (! defined('ABSPATH')) exit; // Exit if accessed directly

if( file_exists(dirname(__FILE__) . '/db.php') ){
	$nts_no_db = TRUE;
	include_once( dirname(__FILE__) . '/db.php' );
	$happ_path = NTS_DEVELOPMENT2;
}
else {
	$happ_path = dirname(__FILE__) . '/happ2';
}

include_once( $happ_path . '/lib-wp/hcWpBase6.php' );

class Z_Schedule_Manager extends hcWpBase6
{
	public function __construct()
	{
		parent::__construct(
			array('z-schedule-manager', 'sm'),	// app
			__FILE__				// path
			);

		add_action(	'init', array($this, '_this_init') );
		add_action( 'admin_print_styles', array($this, 'print_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'print_styles') );
	}

	public function _this_init()
	{
		$this->hcapp_start();
		$this->init_ajax_url();
	}
}

$z_schedule_manager = new Z_Schedule_Manager();
