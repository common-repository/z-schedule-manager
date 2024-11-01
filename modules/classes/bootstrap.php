<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Classes_Bootstrap_SM_HC_MVC extends _HC_MVC
{
	public function run()
	{
		$link = $this->make('/html/view/link')
			->to('/classes')
			->add( HCM::__('Classes') )
			;
		$top_menu = $this->make('/html/view/top-menu')
			->add( $link )
			;

		$this->register_types();

		$this_type = $this->app_short_name() . '-class';
		$columns_view = $this->make('view/admin/columns');

		add_filter( 
			'manage_' .  		$this_type . '_posts_columns',
			array($columns_view, 'columns')
			);
	}

	public function register_types()
	{
		$app_short_name = $this->app_short_name();

	// register custom types
		register_post_type( 
			$this->app_short_name() . '-' . 'class',
			array(
				'labels' => array(
					'menu_name'		=> HCM::__('Classes'),
					'name'			=> HCM::__('Classes'),
					'singular_name'	=> HCM::__('Class'),
					'not_found'		=> HCM::__('No Classes Found'),
					'new_item'		=> HCM::__('New Class'),
					'add_new' 		=> HCM::__('Add New'),
					'add_new_item'	=> HCM::__('Add New Class'),
					'edit_item'		=> HCM::__('Edit Class'),
					'all_items'		=> HCM::__('Classes'),
					'search_items'	=> HCM::__('Search Classes'),
					'view_item'		=> HCM::__('View Classes'),
					),
				'public' => true,
				'has_archive' => false,
				'exclude_from_search' => true,
				// 'show_in_menu' => 'edit.php?post_type=' . $this->app_short_name() . '-schedule',
				'show_in_menu' => $app_short_name,
				)
			);
	}
}