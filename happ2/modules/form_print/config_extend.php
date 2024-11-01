<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$before['/form/view/*@render'] = 'view/input@print-view';
$before['/*/form@-init'] = 'view/form@print-view';
$before['/*/form/*@-init'] = 'view/form@print-view';
