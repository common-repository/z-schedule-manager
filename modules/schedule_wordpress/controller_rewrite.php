<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Wordpress_Controller_Rewrite_SM_HC_MVC extends _HC_MVC
{
	public function extend_link_check( $return, $args, $src )
	{
		list( $slug, $params ) = $return;

		switch( $slug ){
			case 'schedule':
				$slug = admin_url('edit.php?post_type=' . $this->app_short_name() . '-schedule');
				$return = array( $slug, $params );
				break;
		}

		return $return;
	}
}