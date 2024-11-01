<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class _HC_ORM_WordPress_User_Storable implements _HC_ORM_Storable_Interface
{
	protected $default_args = array();
	protected $db = NULL;

	public function set_db( $db )
	{
		$this->db = $db;
		return $this;
	}

	public function add_default_arg( $k, $v )
	{
		$this->default_args[$k] = $v;
		return $this;
	}

	public function delete_all()
	{
		return TRUE;
	}
	public function delete( $wheres = array() )
	{
		return TRUE;
	}

	public function fetch( $fields = '*', $wheres = array(), $limit = NULL, $orderby = NULL, $distinct = FALSE )
	{
		$return = array();

		$args = $this->_prepare_args( $wheres );
		$args = array_merge( $this->default_args, $args );
		$wp_users = get_users( $args );

		foreach( $wp_users as $userdata ){
			$array = $this->_from_userdata( $userdata );
			$array['_wp_userdata'] = $userdata;
			$id = $array['id'];
			$return[ $id ] = $array;
		}
		return $return;
	}

	private function _prepare_args( $wheres = array() )
	{
		$return = array();

		foreach( $wheres as $key => $key_wheres ){
			if( $key == 'id' ){
				$return['include'] = array();
			}

			foreach( $key_wheres as $where ){
				list( $how, $value, $escape ) = $where;

				if( $key == 'id' ){
					$return['include'][] = $value;
				}
			}
		}
		return $return;
	}

	private function _from_userdata( $userdata )
	{
		$return = array(
			'id'			=> $userdata->ID,
			'email'			=> $userdata->user_email,
			'display_name'	=> $userdata->display_name,
			'username'		=> $userdata->user_login,
			);
		return $return;
	}

	public function count( $wheres = array() )
	{
	}

	public function insert( $data )
	{
	}

	public function update( $data, $wheres = array() )
	{
	}

	public function fetch_distinct_prop( $field )
	{
		$return = array();
		return $return;
	}
}

// class Wordpress_Users_Model_User_HC_MVC extends _HC_ORM
class Wordpress_Users_Model_User_HC_MVC extends _HC_ORM_WP_Custom_Post
{
	private $_wp_userdata = NULL;
	private $_wp_always_admin = array('administrator', 'developer');

	public function __construct()
	{
		$this->storable = new _HC_ORM_WordPress_User_Storable();
	}

	public function wp_always_admin()
	{
		return $this->_wp_always_admin;
	}

	public function my_roles()
	{
	// fetch only admins
		$return = $this->_wp_always_admin;

		$mapping = $this->_wp_roles_mapping();
		foreach( $mapping as $mrole => $mlevel ){
			if( $mlevel == 'admin' ){
				$return[] = $mrole;
			}
		}
		$return = array_unique($return);
		return $return;
	}

	public function _init()
	{
	// fetch only admins
		$role_in = $this->run('my-roles');
		$this->storable->add_default_arg('role__in', $role_in);
		return $this;
	}

	public function is_admin()
	{
		$return = FALSE;

		$wp_userdata = $this->get('_wp_userdata');
		if( ! $wp_userdata ){
			return $return;
		}

		if( ! isset($wp_userdata->roles) ){
			return $return;
		}

		$wp_roles = $wp_userdata->roles;

		reset( $this->_wp_always_admin );
		foreach( $this->_wp_always_admin as $wp_always_admin ){
			if( in_array($wp_always_admin, $wp_roles) ){
				$return = TRUE;
				return $return;
			}
		}

		// CHECK OUR CONFIG
		$mapping = $this->_wp_roles_mapping();
		foreach( $mapping as $mrole => $mlevel ){
			if( in_array($mrole, $wp_roles) ){
				if( $mlevel == 'admin' ){
					$return = TRUE;
					break;
				}
			}
		}
		return $return;
	}

	public function wp_roles_mapping()
	{
		$return = $this->_wp_roles_mapping();
		foreach( $this->_wp_always_admin as $wp_always_admin ){
			$return[$wp_always_admin] = 'admin';
		}
		return $return;
	}

	private function _wp_roles_mapping()
	{
		$app_settings = $this->make('/app/lib/settings');
		$prefix = 'wordpress_users:role_';
		$return = array();
		$all_settings = $app_settings->get();

		foreach( $all_settings as $k => $v ){
			if( substr($k, 0, strlen($prefix)) == $prefix ){
				$name = substr($k, strlen($prefix));
				$return[ $name ] = $v;
			}
		}

		foreach( $this->_wp_always_admin as $wp_always_admin ){
			unset( $return[$wp_always_admin] );
		}

		return $return;
	}
}