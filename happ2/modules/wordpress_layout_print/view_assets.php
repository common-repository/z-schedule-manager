<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Wordpress_Layout_Print_View_Assets_HC_MVC extends _HC_MVC
{
	public function extend_css( $params )
	{
		$is_print_view = $this->make('/print/controller')->run('is-print-view');
		if( $is_print_view ){
			unset( $params['javascript'] );
			unset( $params['datepicker'] );

			$new_params = array();
			$new_params['reset2'] = 'happ2/assets/css/hc-1-reset.css';

			$params = array_merge($new_params, $params);
		}
		return $params;
	}
}