<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class MVC_Factory_HC_System
{
	private $modules = array();
	private $aliases = array();
	private $registry = array(); // keep singletons

	public function __construct( $modules = array(), $aliases = array() )
	{
		$this->modules = $modules;
		foreach( $aliases as $k => $va ){
			$this->aliases[$k] = array_shift($va);
		}
	}

	public function register( $object )
	{
		$class_name = strtolower(get_class($object));
		$this->registry[ $class_name ] = $object;
		return $this;
	}

	public function make_classname_path( $slug )
	{
		$_no_folders = TRUE;

		static $slug2class = array();
		if( isset($slug2class[$slug]) ){
			$return = $slug2class[ $slug ];
			return $return;
		}

		$slug_for_name = $slug;
		$slug_for_name = str_replace('-', '_', $slug_for_name);
		$slug_for_name_array = explode('/', $slug_for_name);

		$slug_for_name = str_replace('/', '_', $slug_for_name);
		$slug_for_name = str_replace(':', '_', $slug_for_name);

		$module_for_name = array_shift( $slug_for_name_array );
		$return_parent_module = '';

		$start_path = '';
		if( strpos($module_for_name, ':') !== FALSE ){
			$module_for_name_parts = explode(':', $module_for_name);
			$module_for_name = array_pop( $module_for_name_parts );

			$return_parent_module = array();
			foreach( $module_for_name_parts as $mfnp ){
				$return_parent_module[] = str_replace('_', '-', $mfnp);
			}
			$return_parent_module = join('_', $return_parent_module);
		}

		// $skip_s = array('api');
		// if( ! in_array($slug_for_name_array[0], $skip_s) ){
			// if( ! $_no_folders ){
				// $slug_for_name_array[0] = $slug_for_name_array[0] . 's';
			// }
		// }

		$path_array = $slug_for_name_array;
		if( count($path_array) < 2 ){
			$use_own_name = array('libs', 'lib');
			// $append_name = in_array($path_array[0], $use_own_name) ? $module_for_name : 'index';
			// $append_name = in_array($path_array[0], $use_own_name) ? '' : 'index';
			$append_name = '';
			if( $append_name ){
				$path_array[] = $append_name;
			}
		}
		// $return_path = join('/', $path_array);
		$return_path = join('_', $path_array);
		$return_path .= '.php';

		// $return_classname = $slug_for_name . '_hc_mvc';
		$return_classname = $slug_for_name;

		$return = array( $return_classname, $return_path, $return_parent_module );

		$slug2class[ $slug ] = $return;
		return $return;
	}

	public function make_full_class_name( $return, $this_class_add = '' )
	{
		$prefix = '';
		$suffix = '_hc_mvc';

		if( strlen($this_class_add) ){
			// $prefix = $this_class_add . '_';
			$suffix = '_' . $this_class_add . $suffix;
		}
		$return = $prefix . $return . $suffix;
		return $return;
	}
	
	
// should look like this: module/model|view|controller|lib/path
// for example
// locations/controller/admin
// locations/view/admin/index
// locations/view/admin/edit/delete

// RETURNS A BARE MVC OBJECT
	public function make( $original_slug )
	{
// echo "<h4>$original_slug</h4>";
		$slug = strtolower($original_slug);
		$slug = trim($slug);

		if( isset($this->aliases[$slug]) ){
			// echo "USE ALIAS FOR '$slug'<br>";
			$slug = $this->aliases[$slug];
		}

		$slug = trim($slug, '/');

		$method = NULL;
		if( strpos($slug, '@') !== FALSE ){
			list( $slug, $method ) = explode('@', $slug);
		}

		$slug_array = explode('/', $slug);
		$module = array_shift( $slug_array );

		if( strpos($module, ':') !== FALSE ){
			$module_parts = explode(':', $module);
			$module = array_pop( $module_parts );
		}

// echo "$slug<br>";
// _print_r( $this->make_classname_path( $slug ) );
// exit;

		list( $class_name, $path, $parent_module ) = $this->make_classname_path( $slug );
// echo "CLASS NAME = '$class_name', PATH = '$path', PM = '$parent_module'<br>";

	// TRY TO FIND OR LOAD THIS CLASS

		// trying find
		$class_found = FALSE;
		$full_module = $parent_module ? $parent_module . '_' . $module : $module;

		$this_dirs = isset($this->modules[$full_module]) ? $this->modules[$full_module] : array();

		reset( $this_dirs );
		foreach( $this_dirs as $this_dir_array ){
			list( $this_dir, $this_class_prefix ) = $this_dir_array;
			$full_class_name = $this->make_full_class_name( $class_name, $this_class_prefix );
			if( class_exists($full_class_name) ){
				$class_name = $full_class_name;
				$class_found = TRUE;
				break;
			}
		}

		// trying to load
		if( ! $class_found ){
			reset( $this_dirs );
			foreach( $this_dirs as $this_dir_array ){
				list( $this_dir, $this_class_prefix ) = $this_dir_array;
				$file = $this_dir . '/' . $path;
				$full_class_name = $this->make_full_class_name( $class_name, $this_class_prefix );

				if( file_exists($file) ){
// echo "FOR CLASS: '$class_name' FULL CLASS '$full_class_name' GOT FILE: '$file'<br>";
					require $file;
					if( class_exists($full_class_name) ){
						$class_name = $full_class_name;
						$class_found = TRUE;
						break;
					}
				}
			}
		}

		if( ! $class_found ){
			// _print_r( $this_dirs );
			$error_msg = array();
			$error_msg[] = "Can't locate class for '$slug'<br>";

			if( defined('NTS_DEVELOPMENT2') ){
				reset( $this_dirs );
				foreach( $this_dirs as $this_dir_array ){
					list( $this_dir, $this_class_prefix ) = $this_dir_array;
					$file = $this_dir . '/' . $path;
					$full_class_name = $this->make_full_class_name( $class_name, $this_class_prefix );

					$error_msg[] = "tried: '" . $full_class_name . "' in '" . $file;
				}

				// $error_msg[] = "slug '$slug'";
				// $error_msg[] = "tried path: '$path'";
				$error_msg[] = "full_module: '$full_module'";
				$error_msg[] = "module: '$module'";
				$error_msg[] = "parent_module: '$parent_module'";
			}

			$error_msg = join('<br>', $error_msg);
			hc_show_error( $error_msg );
		}

	// FIND MVC OBJECT
		if( method_exists($class_name, 'get_instance')){
			$return = call_user_func(array($class_name, 'get_instance'));
		}
		elseif( method_exists($class_name, 'single_instance') ){
			if( ! isset($this->registry[$class_name]) ){
				$this->registry[$class_name] = new $class_name;
			}
			$return = $this->registry[$class_name];
		}
		else {
			$return = new $class_name;
		}

		if( $method ){
			$real_method = str_replace('-', '_', $method);
			if( ! method_exists($return, $real_method) ){
				hc_show_error("Can't locate mvc for '$slug' @ '$method' (1)<br>");
			}
		}

		return $return;
	}
}