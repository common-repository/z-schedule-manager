<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/view_checkbox_set.php' );
class Form_View_Weekdays_HC_MVC extends Form_View_Checkbox_Set_HC_MVC
{
	public function _init()
	{
		$t = $this->make('/app/lib')->run('time');
		$wkds = $t->getWeekdays();

		foreach( $wkds as $wkd => $label ){
			$this->add_option($wkd, $label);
		}
		return $this;
	}
}