<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Classes_Form_SM_HC_MVC extends _HC_Form
{
	public function extend_to_model( $return, $args, $src )
	{
		$values = array_shift( $args );

		if( isset($values['class']) ){
			$return['class'] = $values['class'];
		}

		return $return;
	}

	public function extend_init( $return )
	{
		$classes = $this->make('/http/lib/api')
			->request('/api/classes')
			->get()
			->response()
			;

		$presenter = $this->make('/classes/presenter');
		$classes_options = array(
			0	=> ' - ' . HCM::__('Select') . ' - '
			);
		foreach( $classes as $class ){
			$presenter->set_data( $class );
			$classes_options[ $class['id'] ] = $presenter->present_title();
		}

		$inputs['class'] = $this->make('/form/view/select')
			->set_label( HCM::__('Class') )
			->set_options( $classes_options )
			;

		foreach( $inputs as $ik => $iv ){
			$return->set_input( $ik, $iv );
		}

		return $return;
	}
}