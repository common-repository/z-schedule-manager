<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/view_list_inline.php' );
class Html_View_Menubar_HC_MVC extends Html_View_List_Inline_HC_MVC
{
	public function _init()
	{
		$this
			->set_gutter(2)
			;
		return $this;
	}

	public function render()
	{
		$items = $this->children();
		$keys = array_keys($items);

		foreach( $keys as $k ){
			if( is_object($items[$k]) ){
				if( method_exists($items[$k], 'add_attr') ){
					$items[$k]
						->add_attr('class', 'hc-theme-tab-link')
						;
				}
				if( method_exists($items[$k], 'admin') ){
					$items[$k]
						->admin()
						;
				}
			}
		}

		$this->set_children( $items );
		return parent::render();
	}
}