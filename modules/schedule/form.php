<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Form_SM_HC_MVC extends _HC_Form
{
	public function _init()
	{
		$inputs = array();

		$inputs['time_start'] = $this->make('/form/view/time')
			->set_label( HCM::__('Time') )
			;

		$inputs['duration'] = $this->make('/form/view/duration2')
			->set_label( HCM::__('Duration') )
			->set_allowed_options( array('minutes', 'hours') )
			;

		$inputs['date_start'] = $this->make('/form/view/date')
			->set_label( HCM::__('From Date') )
			;

		$inputs['recur'] = $this->make('/recur-dates/view/input')
			->set_label( HCM::__('Recurrence') )
			// ->set_observe('when=always when=todate when=fromdate when=daterange')
			;

		$inputs['recur_until'] = $this->make('/form/view/radio')
			->set_label( HCM::__('Repeat Until') )
			->add_option('date', HCM::__('Date'))
			->add_option('qty', HCM::__('Number of events'))
			->set_inline()
			->set_default('date')
			;

		$inputs['date_end'] = $this->make('/form/view/date')
			->set_label( HCM::__('To Date') )
			->set_observe('recur_until=date')
			;

		$inputs['recur_qty'] = $this->make('/form/view/select')
			->set_label( HCM::__('Number of events') )
			->set_observe('recur_until=qty')
			->set_options_flat( range(2, 30) )
			;

		foreach( $inputs as $k => $input ){
			$this
				->set_input($k, $input)
				;
		}

	// add input validators
		$validator = $this->make('validator/form');
		$this->add_validator( $validator );

		return $this;
	}

	public function from_model( $values )
	{
		$return = $values;

		if( array_key_exists('id', $values) ){
			$return['id'] = $values['id'];
		}

		if( isset($values['recur']) ){
			$return['recur'] = $values['recur'];
		}

		$return['recur'] = isset($values['recur']) ? $values['recur'] : '';
		$return['time_start'] = isset($values['time_start']) ? $values['time_start'] : '';
		$return['date_start'] = isset($values['date_start']) ? $values['date_start'] : '';
		$return['duration'] = isset($values['duration']) ? $values['duration'] : '';

		if( isset($values['date_end']) && $values['date_end'] ){
			$return['recur_until'] = 'date';
		}
		elseif( isset($values['qty']) && $values['qty'] ) {
			$return['recur_until'] = 'qty';
			$return['recur_qty'] = $values['qty'];
		}

		return $return;
	}

	public function to_model( $values )
	{
		$return = array();

		if( array_key_exists('id', $values) ){
			$return['id'] = $values['id'];
		}

		$return['recur'] = $values['recur'];
		$return['time_start'] = $values['time_start'];
		$return['date_start'] = $values['date_start'];

		$return['duration'] = $values['duration'];

		list( $duration_qty, $duration_units ) = explode(' ', $values['duration']);
		if( ! in_array($duration_units, array('minutes', 'hours')) ){
			$return['time_start'] = NULL;
		}

		switch( $values['recur_until'] ){
			case 'date':
				$return['date_end'] = $values['date_end'];
				$return['qty'] = NULL;
				break;
			case 'qty':
				$return['date_end'] = NULL;
				$return['qty'] = $values['recur_qty'];
				break;
		}

		return $return;
	}
}