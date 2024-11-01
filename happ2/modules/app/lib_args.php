<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Lib_Args_HC_MVC extends _HC_MVC
{
	protected $args = array();

	public function get( $k )
	{
		$return = NULL;
		if( array_key_exists($k, $this->args) ){
			$return = $this->args[$k];
		}
		return $return;
	}

	public function parse( $args )
	{
		$this->args = hc2_parse_args( $args, TRUE );
		return $this;
	}
}
