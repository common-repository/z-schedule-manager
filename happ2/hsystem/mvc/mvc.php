<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
abstract class _HC_MVC
{
	public $app = NULL;
	public $slug = NULL;
	public $module = NULL;
	protected $temp = array();

	public function __toString()
	{
		return '' . $this->run('render');
	}

	public function set_temp( $k, $v )
	{
		$this->temp[$k] = $v;
		return $this;
	}

	public function get_temp( $k )
	{
		$return = isset($this->temp[$k]) ? $this->temp[$k] : NULL;
		return $return;
	}

	public function force_module( $module ){
		$this->module = $module;
		return $this;
	}

	public function app_name()
	{
		return $this->app->app_name();
	}

	public function app_short_name()
	{
		return $this->app->app_short_name();
	}

	public function slug()
	{
		return $this->slug;
	}

	public function module()
	{
		if( $this->module ){
			$return = $this->module;
		}
		else {
			$slug = $this->slug;

			$slug = trim($slug, '/');
			$slug = explode('/', $slug);
			$return = array_shift( $slug );
		}
		return $return;
	}

	public function _const( $cname )
	{
		$name = get_class($this) . '::' . $cname;
		$return = constant($name);
		return $return;
	}

	public function make( $slug )
	{
		if( substr($slug, 0, 1) != '/' ){
			// append this module path
			$slug = '/' . $this->module() . '/' . $slug;
		}

		$return = $this->app->make( $slug );
// sort of temporary
		if( $slug == '/html/view/link' ){
			$return->force_module( $this->module() );
		}

		return $return;
	}

	public function run()
	{
		$args = func_get_args();
		$method = array_shift( $args );
		return $this->app->run( $this, $method, $args );
	}
}