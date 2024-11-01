<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_View_Body_HC_MVC extends _HC_MVC
{
	private $content = NULL;
	public function set_content( $content )
	{
		$this->content = $content;
		return $this;
	}
	public function content()
	{
		return $this->content;
	}

	public function top_header()
	{
		$current_slug = $this->make('/http/lib/uri')->slug();

		$slug = explode('/', $current_slug);
		$module = array_shift($slug);
		if( in_array($module, array('setup')) ){
			return;
		}

		$return = $this->make('/html/view/element')->tag('div')
			->add_attr('class', 'print-hide')
			;

	// profile - blank so far
		$return->add( 'profile', NULL );

		return $return;
	}

	public function render()
	{
		$out = $this->make('/html/view/container');

		$nts = $this->make('/html/view/element')->tag('div')
			->add_attr('class', 'hc-container')
			;

		$top_header = $this->run('top-header');

		if( isset($brand) ){
			$top_header->add( $brand );
		}
		if( isset($header) ){
			$top_header->add( $header );
		}
		if( isset($header_ajax) ){
			$top_header->add( $header_ajax );
		}

		$nts
			->add( 'top-header', $top_header )
			->add( 'content', $this->content() )
			;

		$nts = $this->make('/html/view/element')->tag('div')
			->add_attr('id', 'nts')
			->add_attr('class', 'wrap')
			->add( $nts )
			;

		$out->add( $nts );
		if( isset($js_footer) ){
			$out->add( $js_footer );
		}
		if( isset($theme_footer) ){
			$out->add( $theme_footer );
		}

		return $out;
	}
}