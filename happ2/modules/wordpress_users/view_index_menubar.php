<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Wordpress_Users_View_Index_Menubar_HC_MVC extends _HC_MVC 
{
	public function extend_render( $menubar )
	{
	// SETTINGS
		$menubar->add(
			'settings',
			$this->make('/html/view/link')
				->to('/conf', array('--tab' => 'wordpress-users'))
				->add( HCM::__('Access Permissions') )
			);

	// ADD
		if( current_user_can('create_users') ){
			$link = admin_url( 'user-new.php' );
			$menubar->add(
				'add',
				$this->make('/html/view/link')
					->to($link)
					->add_attr('target', '_blank')
					->add( HCM::__('Add New User') )
				);
		}

		return $menubar;
	}
}