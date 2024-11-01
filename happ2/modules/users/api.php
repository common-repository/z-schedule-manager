<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Api_HC_MVC extends _HC_MVC
{
	public function __call( $what, $args )
	{
		$pass = array('get', 'post', 'put', 'delete');
		if( 
			( in_array($what, $pass) )
			){
				$generic_api = $this->make('/code-snippets/api')
					->force_module( $this->module() )
					;

				return call_user_func_array( 
					array($generic_api, $what),
					$args
					);
		}
		return parent::__call( $what, $args );
	}
}