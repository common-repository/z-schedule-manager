<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_View_Date_HC_MVC extends Form_View_Text_HC_MVC
{
	protected $options = array();

	function add_option( $k, $v )
	{
		$this->options[$k] = $v;
	}
	function options()
	{
		return $this->options;
	}

	function render()
	{
		$name = $this->name();
		$value = $this->value();

		$id = 'nts-' . $name;

		$t = $this->make('/app/lib')->run('time');
		if( $value ){
			$t->setDateDb( $value );
			$value = $t->formatDate_Db();
		}

		// $value ? $t->setDateDb( $value ) : $t->setNow();
		// $value = $t->formatDate_Db();

		$out = $this->make('/html/view/container');

	/* hidden field to store our value */
		$hidden = $this->make('view/hidden')
			->set_name( $name )
			->set_value( $value )
			->set_id($id)
			;
		$out->add( $hidden );

	/* text field to display */
		$display_name = $name . '_display';
		$display_id = 'nts-' . $display_name;
		$datepicker_format = $t->formatToDatepicker();
		if( $value ){
			$display_value = $t->formatDate();
		}
		else {
			$display_value = NULL;
		}

		$text = $this->make('view/text')
			->set_label( $this->label() )
			->set_name( $display_name )
			->set_value( $display_value )
			->set_id($display_id)

			->add_attr('data-date-format', $datepicker_format)
			->add_attr('data-date-week-start', $t->weekStartsOn)
			->add_attr( 'style', 'width: 8em' )
			// ->add_attr( 'style', 'width: 100%;' )
			->add_attr( 'class', 'hc-datepicker2' )
			// ->add_attr( 'readonly', 'readonly' )
			;

		$attr = $this->attr();
		foreach( $attr as $k => $v ){
			$text->add_attr( $k, $v );
		}

		$text = $this->decorate( $text->run('render') );
		$out->add( $text );

		return $out;
	}
}
