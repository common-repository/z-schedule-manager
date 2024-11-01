<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Wordpress_Layout_Bootstrap_HC_MVC extends _HC_MVC
{
	public function run()
	{
		add_action( 'admin_menu', array($this, 'admin_menu') );
		add_filter( 'parent_file', array($this, 'set_current_menu') );
		return $this;
	}

	public function admin_menu()
	{
		$app_short_name = $this->app_short_name();
		$top_menu = $this->make('/html/view/top-menu');
		$menu_items = $top_menu->children();

		$my_submenu_count = 0;

		global $submenu;

		foreach( $menu_items as $child_key => $child ){
			if( ! method_exists($child, 'href') ){
				continue;
			}
			if( ! method_exists($child, 'content') ){
				continue;
			}

			$child->admin();
			$child->set_persist( FALSE );
			$href = $child->href( TRUE ); // relative
			if( ! strlen($href) ){
				continue;
			}

			$page_title = '';
			$menu_title = $child->run('content');

			remove_submenu_page( $app_short_name, $href );

			$ret = add_submenu_page(
				$app_short_name,			// parent
				$page_title,				// page_title
				$menu_title,				// menu_title
				'read',						// capability
				$this->app_short_name() . '-' . $child_key,		// menu_slug
				'__return_null'
				);

			$my_submenu = $submenu[$app_short_name];
			$my_submenu_ids = array_keys($my_submenu);
			$my_submenu_id = array_pop($my_submenu_ids);
			$submenu[$app_short_name][$my_submenu_id][2] = $href;

			$my_submenu_count++;
		}

		if( isset($submenu[$app_short_name][0]) && ($submenu[$app_short_name][0][2] == $app_short_name) ){
			unset($submenu[$app_short_name][0]);
		}

		if( ! $my_submenu_count ){
			remove_menu_page( $app_short_name );
		}
	}

	public function set_current_menu( $parent_file )
	{
		global $submenu_file, $current_screen, $pagenow;

		$app_short_name = $this->app_short_name();

		$my = FALSE;
		if( $current_screen->base == 'toplevel_page_' . $app_short_name ){
			$my = TRUE;
		}
		if( substr($current_screen->post_type, 0, strlen($app_short_name)) == $app_short_name ){
			$my = TRUE;
		}

		if( ! $my ){
			return $parent_file;
		}

		switch( $pagenow ){
			case 'edit-tags.php':
				$submenu_file = 'edit-tags.php?taxonomy=' . $current_screen->taxonomy . '&post_type=' . $current_screen->post_type;
				break;

			case 'admin.php':
				$uri = $this->make('/http/lib/uri');
				$slug = $uri->slug();
				$submenu_file = 'admin.php?page=' . $app_short_name . '&' . $uri->hca_param() . '=' . $slug;
				break;

			default:
				break;
		}

		$parent_file = $app_short_name;
		return $parent_file;
	}

	public function display()
	{
		$view = $this->app->handle_request();
		echo $this->app->display_view( $view );
		exit;
	}
}