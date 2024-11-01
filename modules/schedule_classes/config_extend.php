<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
// $after['/schedule/validator@prepare'] = 'validator@extend-prepare';
// $after['/schedule/validator/form@prepare'] = 'validator@extend-prepare';

$after['/schedule/form@-init'] = 'form@extend-init';
$after['/schedule/form@to-model'] = 'form@extend-to-model';

$after['/schedule/view/front@prepare-header'] = 'view/front@extend-prepare-header';
$after['/schedule/view/front@prepare-row'] = 'view/front@extend-prepare-row';

$after['/schedule/view/admin/columns@run-columns'] = 'view/admin/columns@extend-columns';
$after['/schedule/view/admin/columns@run-sortable-columns'] = 'view/admin/columns@extend-sortable-columns';
$after['/schedule/view/admin/columns@run-custom-columns'] = 'view/admin/columns@extend-custom-columns';