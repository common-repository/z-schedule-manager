<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_View_Checkbox_Set_HC_MVC extends HC_Form_Input2
{
	protected $options = array();
	protected $more = array();
	protected $readonly = array();
	protected $value = array();
	protected $inline = TRUE;

	function add_option( $value, $label = NULL, $more = '' )
	{
		$this->options[$value] = $label;
		if( $more ){
			$this->more[$value] = $more;
		}
		return $this;
	}
	function set_value( $value )
	{
		if( ! is_array($value) ){
			if( strlen($value) ){
				$value = array( $value );
			}
			else {
				$value = array();
			}
		}
		return parent::set_value( $value );
	}

	function set_options( $options )
	{
		$this->options = $options;
		return $this;
	}
	function options()
	{
		return $this->options;
	}
	function more()
	{
		return $this->more;
	}

	function set_inline( $inline = TRUE )
	{
		$this->inline = $inline;
		return $this;
	}
	function inline()
	{
		return $this->inline;
	}

	function set_readonly( $value = TRUE )
	{
		$args = func_get_args();
		$value = array_shift( $args );
		$ro = array_shift( $args );

		$this->readonly[$value] = $ro;
		return $this;
	}
	function readonly( $value = NULL )
	{
		if( $value === NULL ){
			$return = $this->readonly;
		}
		else {
			$return = 
				( array_key_exists($value, $this->readonly) && $this->readonly[$value] )
				? TRUE
				: FALSE
			;
		}
		return $return;
	}

	function grab( $post )
	{
		$name = $this->name();
		$value = array();
		if( isset($post[$name]) ){
			$value = $post[$name];
		}
		$this->set_value( $value );
		return $this;
	}

	public function render_one( $value, $decorate = FALSE )
	{
		$options = $this->options();
		$full_value = $this->value();
		$label = $options[$value];
		$inline = $this->inline();

// echo "SETTING NAME TO: '" . $this->name() . "'<br>";
		$sub_el = $this->make('view/checkbox')
			->set_name($this->name() . '[]')
			->set_my_value($value)
			;
		if( $this->readonly($value) ){
			$sub_el->set_readonly();
		}
		if( strlen($label) ){
			$sub_el->set_label( $label );
		}
		if( in_array($value, $full_value) ){
			$sub_el->set_value(1);
		}

		if( $inline ){
			// $sub_el->add_attr('style', 'height: 1.5rem;');
		}

		if( $decorate ){
			$return = $this->decorate( $sub_el );
		}
		else {
			// $return = $sub_el->render($decorate);
			$return = $sub_el;
		}
		return $return;
	}

	function render()
	{
		$options = $this->options();
		$full_value = $this->value();
		$inline = $this->inline();

		if( $inline ){
			$el = $this->make('/html/view/list-inline')
				->set_gutter(1)
				;
		}
		else {
			$el = $this->make('/html/view/list');
		}

		$el
			->add_attr('class', 'hc-form-control-static')
			;

		$attr = $this->attr();
		foreach( $attr as $key => $val ){
			$el->add_attr($key, $val);
		}
		foreach( $options as $value => $label ){
			$el->add( $this->render_one($value) );
		}

		$return = $this->decorate( $el, FALSE );

		return $return;
	}
}