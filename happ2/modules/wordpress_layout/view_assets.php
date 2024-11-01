<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Wordpress_Layout_View_Assets_HC_MVC extends _HC_MVC
{
	/* sets full url for assets files */
	public function extend_setpath( $array )
	{
		if( defined('NTS_DEVELOPMENT2') && NTS_DEVELOPMENT2 ){
			$assets_web_dir = $this->app->web_dir . '/';
			$assets_happ_web_dir = plugins_url('happ2') . '/';
		}
		else {
			$assets_web_dir = $this->app->web_dir . '/';
			$assets_happ_web_dir = $assets_web_dir . 'happ2/';
		}

		$keys = array_keys( $array );
		foreach( $keys as $k ){
			if( substr($k, 0, strlen('localize_')) == 'localize_' ){
				continue;
			}

			$full_href = $array[$k];
			if( ! HC_Lib2::is_full_url($full_href) ){
				if( substr($full_href, 0, strlen('happ2/')) == 'happ2/' ){
					$full_href = $assets_happ_web_dir . substr($full_href, strlen('happ2/'));
				}
				else {
					$full_href = $assets_web_dir . $full_href;
				}
				$array[$k] = $full_href;
			}
		}

		return $array;
	}

	public function extend_css( $params )
	{
		if( isset($params['hc']) ){
			$params['hc'] = str_replace('/hc.css', '/hc-wp.css', $params['hc']);
		}

		$unset = array('reset', 'style', 'form', 'font');
		reset( $unset );
		foreach( $unset as $k ){
			if( isset($params[$k]) ){
				unset($params[$k]);
			}
		}

		return $params;
	}
}