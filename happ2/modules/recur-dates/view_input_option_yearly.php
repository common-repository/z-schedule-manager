<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Recur_Dates_View_Input_Option_Yearly_HC_MVC extends Recur_Dates_View_Input_Option_HC_MVC implements Recur_Dates_View_Input_HC_MVC_Option_Interface
{
	public function label()
	{
		return HCM::__('Yearly');
	}

	public function grab( $post )
	{
		$r = $this->make('lib/when');
		$r
			->freq('yearly')
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
		if( $freq == 'YEARLY' ){
			$return = TRUE;
		}

		return $return;
	}

	public function set_value( $value )
	{
		return $this;
	}
}