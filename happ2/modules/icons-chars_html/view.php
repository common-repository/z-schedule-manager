<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Icons_Chars_Html_View_HC_MVC extends Html_View_Element_HC_MVC
{
	private $convert = array(
		'arrow-up'		=> '&uarr;',
		'arrow-down'	=> '&darr;',
		'arrow-left'	=> '&larr;',
		'arrow-right'	=> '&rarr;',
		'cog'			=> '&#9881;',
		// 'user'			=> '&#9863;',
		'user'			=> '',
		'exclamation'	=> '&#9888;',
		'coffee'		=> '&#9749;',
		'bar-chart'		=> '&#9776;',
		'work'			=> '&#9874;',
		'star'			=> '&#9734;',
		'grid'			=> '&#9783;',
		'list'			=> '&#9783;',
		'check'			=> '&#9745;',
		'caret-down'	=> '&#9662;',
		'plus'			=> '&#43;',
		'times'			=> '&#9746;',
		'printer'		=> '&#9776;',
		'home'			=> '&#9750;',
		'spin'			=> '&#9788;',

		'purchase'		=> '&#x25b7;',
		'sale'			=> '&#x25c1;',
	);

	public function extend_render($icon, $params, $src)
	{
		if( ! is_string($icon) ){
			return $icon;
		}

		$return = NULL;

		if( isset($this->convert[$icon]) ){
			$icon = $this->convert[$icon];
			if( $icon && strlen($icon) ){
				$return = $this->make('/html/view/element')->tag('span')
					->add( $icon )
					->add_attr('class', 'hc-mr1')
					->add_attr('class', 'hc-ml1')
					->add_attr('class', 'hc-char')
					;
			}
			else {
				$return = '';
			}
		}
		else {
			$return = '';
		}

		return $return;
	}
}