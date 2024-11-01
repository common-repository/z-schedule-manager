<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Classes_View_Front_SM_HC_MVC extends _HC_MVC
{
	public function extend_prepare_header( $return )
	{
		$return['class'] = HCM::__('Class');
		return $return;
	}

	public function extend_prepare_row( $return, $args )
	{
		$e = array_shift( $args );

		if( isset($e['class']) ){
			$p = $this->make('/classes/presenter')
				->set_data( $e['class'] )
				;

			$class_view = $p->present_title();
			$return['class']	= $class_view;
			$return['class_view'] = $class_view;
		}
		else {
			$return['class']	= NULL;
		}

		return $return;
	}
}