<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Recur_Dates_View_Input_Option_Monthly_Weekday_HC_MVC extends Recur_Dates_View_Input_Option_HC_MVC implements Recur_Dates_View_Input_HC_MVC_Option_Interface
{
	public function label()
	{
		$return = HCM::__('Monthly') . ' [' . HCM::__('Weekday') . ']';
		return $return;
	}

	public function _init()
	{
		$r = $this->make('lib/when');
		$sequence_options = $r->sequence_options();

		$this->fields['sequence'] = $this->make('/form/view/select')
			->set_name($this->pname . '_sequence')
			->set_label( HCM::__('Sequence') )
			->set_options( $sequence_options )
			;

		$t = $this->make('/app/lib')->run('time');
		$wkds = $t->getWeekdays();
		$this->fields['weekday'] = $this->make('/form/view/select')
			->set_name($this->pname . '_weekday')
			->set_label( HCM::__('Weekday') )
			->set_options( $wkds )
			;
		return $this;
	}

	public function grab( $post )
	{
		$return = NULL;

		reset( $this->fields );
		foreach( $this->fields as $k => $f ){
			$this->fields[$k]->grab( $post );
		}

		$values = array();
		reset( $this->fields );
		foreach( array_keys($this->fields) as $k ){
			$values[$k] = $this->fields[$k]->value();
		}

		$r = $this->make('lib/when');

		$byday = array($values['weekday']);
		$byday = $r->convert_days($byday);

		$sequence = $values['sequence'];
		for( $ii = 0; $ii < count($byday); $ii++ ){
			$byday[$ii] = $sequence . $byday[$ii];
		}

		$r
			->freq('monthly')
			->byday( $byday )
			;

		$return = $r->string();
		return $return;
	}

	public function is_me( $value )
	{
		$return = FALSE;

		$r = $this->make('lib/when');
		$r->rrule( $value );

		$freq = $r->get_freq();
		if( $freq == 'MONTHLY' ){
			$return = TRUE;
		}

		return $return;
	}

	public function set_value( $value )
	{
		$r = $this->make('lib/when');
		$r->rrule( $value );

		$byday = $r->get_byday();
		$byday = array_shift($byday);

		$sequence = 0;
		if( strlen($byday) > 2 ){
			$sequence = substr($byday, 0, -2);
		}
		$byday = $r->convert_days_from( $byday );

		$this->fields['sequence']->set_value( $sequence );
		$this->fields['weekday']->set_value( $byday );

		return $this;
	}
}