<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Datepicker_View_Head_HC_MVC extends _HC_MVC
{
	public function extend_js( $params )
	{
		$params = array(
			'datepicker' => 'happ2/modules/datepicker/assets/js/hc-datepicker2.js'
			)
			+ $params
			;
		return $params;
	}

	public function extend_css( $params )
	{
		$params = array_merge( $params, array(
			'datepicker' => 'happ2/modules/datepicker/assets/css/hc-datepicker2.css'
			)
		);
		return $params;
	}
}