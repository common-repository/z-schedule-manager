<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/view_container.php' );
class Html_View_Tabs_HC_MVC extends Html_View_Element_HC_MVC
{
	protected $tabs = array();
	protected $active = NULL;
	protected $id = NULL;

	function __construct()
	{
		parent::__construct();
		$id = 'nts' . hc_random();
		$this->set_id( $id );
	}

	function set_id($id)
	{
		$this->id = $id;
	}
	function id()
	{
		return $this->id;
	}

	function set_active( $active )
	{
		$this->active = $active;
	}
	function active()
	{
		$return = NULL;
		if( $this->active ){
			$return = $this->active;
		}
		elseif( count($this->tabs) ){
			$tabs = array_keys($this->tabs);
			$return = $tabs[0];
		}
		return $return;
	}

	function add_tab( $key, $label, $content )
	{
		$this->tabs[ $key ] = array( $label, $content );
	}
	function tabs()
	{
		return $this->tabs;
	}

	function render_content()
	{
		$active = $this->active();
		$my_tabs = $this->tabs();

		$content = $this->make('view/element')->tag('div')
			->add_attr('class', 'hcj2-tab-content')
			->add_attr('style', 'overflow: visible;')
			;
		reset( $my_tabs );
		foreach( $my_tabs as $key => $tab_array ){
			list( $tab_label, $tab_content ) = $tab_array;
			$tab = $this->make('view/element')->tag('div')
				->add_attr('class', 'hcj2-tab-pane')
				->add_attr('id', $key)
				->add_attr('data-tab-id', $key)
				;
			if( $active == $key ){
				$tab->add_attr('class', 'hc-active');
			}
			$tab->add( $tab_content );
			$content->add( $tab );
		}
		return $content;
	}

	function render()
	{
		$return = '';
		$my_tabs = $this->tabs();

		if( count($my_tabs) == 1 ){
			foreach( $my_tabs as $key => $tab_array ){
				list( $tab_label, $tab_content ) = $tab_array;
				$return = $tab_content;
				break;
			}
			return $return;
		}

	/* tabs */
		$id = $this->id();

		$tabs = $this->make('view/list-inline')
			->add_attr('id', $id) 
			->add_attr('class', array('hcj2-tab-links'))

			->add_attr('class', 'hc-mb1')
			->add_attr('class', 'hc-py2')
			->add_attr('class', 'hc-px1')
			;

		$active = $this->active();
		reset( $my_tabs );
		foreach( $my_tabs as $key => $tab_array ){
			list( $tab_label, $tab_content ) = $tab_array;
			if( ! is_object($tab_label) ){
				$tab_label = $this->make('view/element')->tag('a')
					->add( $tab_label )
					->add_attr('title', $tab_label)
					;
			}

			$tab_label
				->add_attr('href', '#' . $key)
				->add_attr('class', 'hcj2-tab-toggler')
				->add_attr('data-toggle-tab', $key)

				->add_attr('class', 'hc-btn')
				->add_attr('class', 'hc-m0')
				->add_attr('class', 'hc-px3')
				->add_attr('class', 'hc-py2')
				;

			$tab_label->add_attr('class', 'hc-border-bottom');
			if( $active == $key ){
				$tab_label->add_attr('class', 'hc-active');
			}

			$tabs->add( $key, $tab_label );
		}

	/* content */
		$content = $this->render_content();

	/* out */
		$out = $this->make('view/list')
			->add_attr('class', array('hcj2-tabs'))
			;
		$out->add( $tabs );
		$out->add( $content );

		return $out;
	}
}