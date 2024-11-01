<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Validator_Form_SM_HC_MVC extends _HC_Validator
{
	public function prepare( $values )
	{
		$return = array();
		$id = isset($values['id']) ? $values['id'] : NULL;

		$return['date_start'] = array(
			'required'	=> array( $this->make('/validate/required') ),
			);

		$return['date_end'] = array(
			'required'			=> array( $this->make('/validate/required') ),
			);

		$return['date_end']['date_start_end']	= array( $this->make('validate/date-start-end'),
			isset($values['date_start']) ? $values['date_start'] : NULL,
			isset($values['recur_until']) ? $values['recur_until'] : NULL
			);

		$return['recur'] = array(
			'required'	=> array( $this->make('/validate/required') ),
			);

		$return['recur_until'] = array(
			'required'	=> array( $this->make('/validate/required') ),
			);

		return $return;
	}
}