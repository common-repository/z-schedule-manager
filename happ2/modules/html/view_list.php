<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/view_container.php' );
class Html_View_List_HC_MVC extends Html_View_Container_HC_MVC
{
	function render()
	{
		$args = func_get_args();
		if( count($args) ){
			$items = array_shift($args);
		}
		else {
			$items = $this->children();
		}

		$out = $this->make('view/element')->tag('ul')
			->add_attr('class', 'hc-m0')
			->add_attr('class', 'hc-p0')
			;

		$attr = $this->attr();
		foreach( $attr as $k => $v ){
			$out
				->add_attr( $k, $v )
				;
		}

		$already_shown = 0;

		foreach( $items as $key => $item ){
			$li = $this->make('view/element')->tag('li')
				->add_attr('class', 'hc-m0')
				->add_attr('class', 'hc-p0')
				;

			$li->add( $item );
			$out->add( $li );

			$already_shown++;
		}

		return $out;
	}
}