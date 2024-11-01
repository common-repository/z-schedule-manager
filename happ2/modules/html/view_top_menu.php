<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_View_Top_Menu_HC_MVC extends Html_View_Element_HC_MVC
{
	protected $current;

	public function single_instance()
	{
	}

	public function set_current( $current )
	{	
		$this->current = $current;
		return $this;
	}

	public function render()
	{
		$out = $this->make('view/element')->tag('div')
			->add_attr('class', 'hc-mb2')
			->add_attr('class', 'hc-rounded')
			;

		$out
			->add_attr('class', 'hc-bg-darkgray')
			->add_attr('class', 'hc-silver')
			;

		$children = $this->children();
		$uri = $this->make('/http/lib/uri');

		foreach( $children as $child ){
			if( is_object($child) && method_exists($child, 'add_attr') ){
				$child
					->add_attr('class', 'hc-btn')
					->add_attr('class', 'hc-px2')
					->add_attr('class', 'hc-py3')
					->add_attr('class', 'hc-mr2')
					;

				if( method_exists($child, 'href') ){
					$href = $child->href();
					$this_slug = $uri->get_slug_from_url( $href );
					$child->set_persist( FALSE );

				// active
					if( 
						( $this_slug == $this->current )
						OR
						(
							( substr($this->current, 0, strlen($this_slug)) == $this_slug ) &&
							( substr($this->current, strlen($this_slug), 1) == '/' )
						)
					){
						$child
							->add_attr('class', 'hc-bg-black')
							->add_attr('class', 'hc-silver')
							;
					}
				}
			}
			$out->add( 
				$child
				);
		}

		return $out;
	}
}