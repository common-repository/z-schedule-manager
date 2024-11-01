<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Recur_Dates_View_Input_Option_Weekly_HC_MVC extends Recur_Dates_View_Input_Option_HC_MVC implements Recur_Dates_View_Input_HC_MVC_Option_Interface
{
	public function label()
	{
		return HCM::__('Weekly');
	}

	public function _init()
	{
		$this->fields['weekdays'] = $this->make('/form/view/weekdays')
			->set_name($this->pname . '_weekdays_weekdays')
			->set_label( HCM::__('Weekdays') )
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

		if( ! $values['weekdays'] ){
			return $return;
		}

		$r = $this->make('lib/when');
		$r
			->freq('weekly')
			->byday( $r->convert_days($values['weekdays']) )
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
		if( $freq == 'WEEKLY' ){
			$return = TRUE;
		}

		return $return;
	}

	public function set_value( $value )
	{
		$r = $this->make('lib/when');
		$r->rrule( $value );

		$t = $this->make('/app/lib')->run('time');
		$t->setStartDay();

		$weekdays = array();
		for( $ii = 1; $ii <= 7; $ii++ ){
			$this_date = $t->formatDate_Db();

			if( $r->valid_date($this_date) ){
				$this_weekday = $t->getWeekday();
				$weekdays[] = $this_weekday;
			}
			$t->modify('+1 day');
		}
		sort( $weekdays );

		$this->fields['weekdays']->set_value( $weekdays );
		return $this;
	}
}