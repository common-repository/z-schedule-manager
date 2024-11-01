<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Classes_Validator_SM_HC_MVC extends _HC_Validator
{
	public function extend_prepare( $return, $args, $src )
	{
		$values = array_shift( $args );

		$return['class'] = array(
			'min'	=> array( $this->make('/validate/min'), 1, HCM::__('Required field') )
			);

		return $return;
	}
}