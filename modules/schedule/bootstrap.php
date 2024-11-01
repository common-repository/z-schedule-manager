<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Schedule_Bootstrap_SM_HC_MVC extends _HC_MVC
{
	public function run()
	{
		$link = $this->make('/html/view/link')
			->to('/schedule')
			->add( HCM::__('All Schedules') )
			;
		$top_menu = $this->make('/html/view/top-menu')
			->add( $link )
			;

		$this->register_types();

		add_action(
			'add_meta_boxes',
			array($this, 'add_meta_boxes')
			);

		$this_type = $this->app_short_name() . '-schedule';
		$columns_view = $this->make('view/admin/columns');

		add_filter( 
			'manage_edit-' .	$this_type . '_sortable_columns',
			array($columns_view, 'sortable_columns')
			);
		add_filter( 
			'manage_' .  		$this_type . '_posts_columns',
			array($columns_view, 'columns')
			);
		add_action( 
			'manage_' . 		$this_type . '_posts_custom_column',
			array($columns_view, 'custom_columns'), 10, 2
			);

		add_filter( 
			'post_row_actions',
			array($this, 'row_actions'), 10, 2
			);

		// add_action( 'admin_menu', array($this, 'admin_menu') );

		$front_view = $this->make('controller/front');
		add_shortcode( 'z-schedule-manager', array($front_view, 'route_index'));
	}

	public function admin_menu()
	{
		// show only published
		global $submenu;

		$this_type = $this->app_short_name() . '-schedule';
		$my_link = 'edit.php?post_type=' . $this_type;
		if( isset($submenu[$my_link]) ){
			reset( $submenu[$my_link] );
			foreach( $submenu[$my_link] as $k => $v ){
				if( isset($v[2]) && ($v[2] == $my_link) ){
					$submenu[$my_link][$k][2] = $my_link . '&post_status=publish';
				}
			}
		}
	}

	public function row_actions( $actions, $post )
	{
		$this_type = $this->app_short_name() . '-schedule';
		if( $post->post_type != $this_type ){
			return $actions;
		}

		$return = array(
			'edit'	=> $actions['edit'],
			'trash'	=> $actions['trash'],
			);
		return $return;
	}
	
	
	public function register_types()
	{
		$app_title = isset($this->app->app_config['nts_app_title']) ? $this->app->app_config['nts_app_title'] : 'Z Schedule Manager';

	// register custom types
		register_post_type( 
			$this->app_short_name() . '-' . 'schedule',
			array(
				'labels' => array(
					'menu_name'		=> $app_title,
					'name'			=> HCM::__('Class Schedule'),
					'singular_name'	=> HCM::__('Class Schedule'),
					'not_found'		=> HCM::__('No Schedule Found'),
					'new_item'		=> HCM::__('New Schedule'),
					'add_new' 		=> HCM::__('Add New Schedule'),
					'add_new_item'	=> HCM::__('Add New Schedule'),
					'edit_item'		=> HCM::__('Edit Schedule'),
					'all_items'		=> HCM::__('All Schedules'),
					'search_items'	=> HCM::__('Search Schedules'),
					'view_item'		=> HCM::__('View Schedule'),
					),
				'public' => true,
				'has_archive' => false,
				'exclude_from_search' => true,
				'menu_icon'		=> 	'dashicons-calendar',
				'show_in_menu' => $this->app_short_name(),

				// 'supports' => array('thumbnail','comments')
				// 'supports' => array('comments'),
				'supports' => array(''),
				'hierarchical' 			=> false,
				'show_ui' 				=> true,
				'show_in_nav_menus' 	=> false
				)
			);
	}

	public function add_meta_boxes()
	{
		$view_zoom = $this->make('view/zoom');
		add_meta_box(
			$this->app_short_name() . '-' . 'schedule-details',	// id
			HCM::__('Schedule Details'),					// title
			array($view_zoom, 'echo_render'),				// callback
			$this->app_short_name() . '-' . 'schedule',			// screen
			'normal',										// context
			'high'											// priority
			);
	}
}