<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$alias['/users/model'] = 'model/user';

// user list sidebar
$after['/users/view/index/menubar@render']	= 'view/index/menubar@extend-render';
$after['/users/view/zoom/menubar@render']	= 'view/zoom/menubar@extend-render';

$after['/users/form/edit@-init']	= 'form@extend';
