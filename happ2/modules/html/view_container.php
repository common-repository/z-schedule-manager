<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/view_element.php' );
class Html_View_Container_HC_MVC extends Html_View_Element_HC_MVC
{
	function render()
	{
		$out = '';

		$args = func_get_args();
		if( count($args) ){
			$items = array_shift($args);
		}
		else {
			$items = $this->children();
		}

		foreach( $items as $item ){
			$out .= $item;
		}
		return $out;
	}
}