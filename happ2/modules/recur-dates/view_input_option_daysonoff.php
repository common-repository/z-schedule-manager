<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Recur_Dates_View_Input_Option_Daysonoff_HC_MVC extends Recur_Dates_View_Input_Option_HC_MVC implements Recur_Dates_View_Input_HC_MVC_Option_Interface
{
	public function label()
	{
		return HCM::__('X Days On / Y Days Off');
	}

	public function _init()
	{
		$this->fields['dayson'] = $this->make('/form/view/text')
			->set_name($this->pname . '_dayson')
			->add_attr('size', 6)
			->set_label( HCM::__('Days On') )
			;
		$this->fields['daysoff'] = $this->make('/form/view/text')
			->set_name($this->pname . '_daysoff')
			->add_attr('size', 6)
			->set_label( HCM::__('Days Off') )
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

		if( ! $values['dayson'] ){
			return $return;
		}
		if( ! $values['daysoff'] ){
			return $return;
		}

		$r = $this->make('lib/when');
		$r
			->freq('daysonoff')
			->daysonoff( array($values['dayson'], $values['daysoff']) );
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
		if( $freq == 'DAYSONOFF' ){
			$return = TRUE;
		}

		return $return;
	}

	public function set_value( $value )
	{
		$r = $this->make('lib/when');
		$r->rrule( $value );

		$daysonoff = $r->get_daysonoff();
		$days_on = array_shift( $daysonoff );
		$days_off = array_shift( $daysonoff );

		$this->fields['dayson']->set_value( $days_on );
		$this->fields['daysoff']->set_value( $days_off );

		return $this;
	}
}