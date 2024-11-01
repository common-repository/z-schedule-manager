<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Validate_Date_Start_End_SM_HC_MVC extends _HC_MVC
{
	public function validate( $value, $date_start, $recur_until )
	{
		$msg = HCM::__('The end date should be after the start date');
		$return = TRUE;

		if( $recur_until != 'date' ){
			return $return;
		}

		if( ! $value ){
			$return = $msg;
		}
		if( $date_start > $value ){
			$return = $msg;
		}
		return $return;
	}
}